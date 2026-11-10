<?php

namespace HackGreenville\EventImporter\Tests\Feature;

use App\Models\Venue;
use ArrayObject;
use HackGreenville\EventImporter\Data\VenueData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\DatabaseTestCase;

class VenueDataTest extends DatabaseTestCase
{
    public function test_same_name_with_different_coordinates_reuses_the_venue(): void
    {
        $original = Venue::factory()->create([
            'name' => 'Fireforge Crafted Beer',
            'slug' => 'fireforge-crafted-beer',
            'address' => '1 Main St',
            'lat' => 34.1,
            'lng' => -82.1,
        ]);

        $resolved = $this->venueData(
            'Fireforge Crafted Beer',
            '1 Main Street',
            34.10001,
            -82.10001,
        )->resolveVenue();

        $this->assertTrue($original->is($resolved));
        $this->assertSame('1 Main St', $resolved->fresh()->address);
        $this->assertSame('fireforge-crafted-beer', $resolved->fresh()->slug);
        $this->assertSame(1, Venue::query()->count());
    }

    public function test_custom_slug_is_reused_when_the_name_matches(): void
    {
        $original = Venue::factory()->create([
            'name' => 'Open Works',
            'slug' => 'openworks',
            'address' => '101 N Main St',
        ]);

        $resolved = $this->venueData('Open Works', '101 North Main Street')->resolveVenue();

        $this->assertTrue($original->is($resolved));
        $this->assertSame('openworks', $resolved->fresh()->slug);
        $this->assertSame('101 N Main St', $resolved->fresh()->address);
        $this->assertSame(1, Venue::query()->count());
    }

    public function test_slug_match_reuses_a_venue_whose_name_was_edited(): void
    {
        $original = Venue::factory()->create([
            'name' => 'Open Works Downtown',
            'slug' => 'open-works',
            'address' => '101 N Main St',
        ]);

        $resolved = $this->venueData('Open Works', 'Elsewhere')->resolveVenue();

        $this->assertTrue($original->is($resolved));
        $this->assertSame('Open Works Downtown', $resolved->fresh()->name);
        $this->assertSame('101 N Main St', $resolved->fresh()->address);
    }

    public function test_name_match_wins_over_an_older_slug_match(): void
    {
        Venue::factory()->create([
            'name' => 'Other Place',
            'slug' => 'open-works',
        ]);
        $custom = Venue::factory()->create([
            'name' => 'Open Works',
            'slug' => 'openworks',
        ]);

        $resolved = $this->venueData('Open Works')->resolveVenue();

        $this->assertTrue($custom->is($resolved));
        $this->assertSame(2, Venue::query()->count());
    }

    public function test_new_name_creates_one_venue_with_a_slug(): void
    {
        $resolved = $this->venueData('Zen Greenville', '5 Court St')->resolveVenue();

        $this->assertSame('Zen Greenville', $resolved->name);
        $this->assertSame('zen-greenville', $resolved->slug);
        $this->assertSame('5 Court St', $resolved->address);
        $this->assertSame('Greenville', $resolved->city);
        $this->assertSame('SC', $resolved->state);
        $this->assertSame(1, Venue::query()->count());

        $again = $this->venueData('Zen Greenville', '500 Court Street', 1.0, 2.0)->resolveVenue();

        $this->assertTrue($resolved->is($again));
        $this->assertSame('5 Court St', $again->fresh()->address);
        $this->assertSame(1, Venue::query()->count());
    }

    public function test_same_place_id_reuses_a_venue_with_a_different_name(): void
    {
        $logged = $this->captureVenueLogs();

        $original = $this->venueData(
            'SynergyMill',
            '31 Cessna Ct Ste B',
            googlePlaceId: 'ChIJ8fulZrQxWIgR8PpSefWe8t0',
            appleMapsPlaceId: 'I9BEC17763FC84469',
        )->resolveVenue();

        $resolved = $this->venueData(
            '31 Cessna Ct Ste B',
            '31 Cessna Ct Ste B',
            googlePlaceId: 'ChIJ8fulZrQxWIgR8PpSefWe8t0',
        )->resolveVenue();

        $this->assertTrue($original->is($resolved));
        $this->assertSame('SynergyMill', $resolved->fresh()->name);
        $this->assertSame('31 Cessna Ct Ste B', $resolved->fresh()->address);
        $this->assertSame(1, Venue::query()->count());
        $this->assertCount(1, $logged);
        $this->assertSame('info', $logged[0]->level);
        $this->assertSame('Event import created a venue', $logged[0]->message);
        $this->assertSame('ChIJ8fulZrQxWIgR8PpSefWe8t0', $logged[0]->context['google_place_id']);
        $this->assertSame('I9BEC17763FC84469', $logged[0]->context['apple_maps_place_id']);
    }

    public function test_apple_place_id_reuses_a_venue_when_the_google_id_differs(): void
    {
        $original = Venue::factory()->create([
            'name' => 'SynergyMill',
            'slug' => 'synergymill',
            'apple_maps_place_id' => 'I9BEC17763FC84469',
            'google_place_id' => 'place-from-the-name',
        ]);

        $resolved = $this->venueData(
            '31 Cessna Ct',
            '31 Cessna Ct',
            googlePlaceId: 'place-from-the-street',
            appleMapsPlaceId: 'I9BEC17763FC84469',
        )->resolveVenue();

        $this->assertTrue($original->is($resolved));
        $this->assertSame(1, Venue::query()->count());
    }

    public function test_a_different_place_id_creates_and_logs_another_venue(): void
    {
        $logged = $this->captureVenueLogs();

        $this->venueData('SynergyMill', '31 Cessna Ct Ste B', googlePlaceId: 'place-name')->resolveVenue();
        $created = $this->venueData('31 Cessna Ct', '31 Cessna Ct', googlePlaceId: 'place-street')->resolveVenue();

        $this->assertSame('31 Cessna Ct', $created->name);
        $this->assertSame('place-street', $created->google_place_id);
        $this->assertNull($created->apple_maps_place_id);
        $this->assertSame(2, Venue::query()->count());
        $this->assertCount(2, $logged);

        $second = $logged[1];
        $this->assertSame('info', $second->level);
        $this->assertSame('Event import created a venue', $second->message);
        $this->assertSame('31 Cessna Ct', $second->context['name']);
        $this->assertSame('place-street', $second->context['google_place_id']);
        $this->assertSame('31 Cessna Ct', $second->context['address']);
    }

    public function test_a_blank_place_id_does_not_match_another_blank_place_id(): void
    {
        $first = $this->venueData('SynergyMill', googlePlaceId: '   ')->resolveVenue();
        $second = $this->venueData('OpenWorks', googlePlaceId: '')->resolveVenue();

        $this->assertFalse($first->is($second));
        $this->assertNull($first->fresh()->google_place_id);
        $this->assertNull($second->fresh()->google_place_id);
        $this->assertSame(2, Venue::query()->count());
    }

    public function test_name_match_wins_when_the_place_id_differs(): void
    {
        $original = $this->venueData('SynergyMill', googlePlaceId: 'place-name')->resolveVenue();

        $resolved = $this->venueData('SynergyMill', '31 Cessna Ct', googlePlaceId: 'place-street')->resolveVenue();

        $this->assertTrue($original->is($resolved));
        $this->assertSame('place-name', $resolved->fresh()->google_place_id);
        $this->assertSame(1, Venue::query()->count());
    }

    public function test_a_name_match_stores_a_place_id_the_venue_does_not_have_yet(): void
    {
        $original = Venue::factory()->create([
            'name' => 'SynergyMill',
            'slug' => 'synergymill',
            'google_place_id' => null,
            'apple_maps_place_id' => null,
        ]);

        $resolved = $this->venueData(
            'SynergyMill',
            googlePlaceId: 'ChIJ8fulZrQxWIgR8PpSefWe8t0',
            appleMapsPlaceId: 'I9BEC17763FC84469',
        )->resolveVenue();

        $this->assertTrue($original->is($resolved));
        $this->assertSame('ChIJ8fulZrQxWIgR8PpSefWe8t0', $resolved->fresh()->google_place_id);
        $this->assertSame('I9BEC17763FC84469', $resolved->fresh()->apple_maps_place_id);
        $this->assertSame(1, Venue::query()->count());
    }

    public function test_a_place_id_already_stored_on_another_venue_is_not_copied(): void
    {
        Venue::factory()->create([
            'name' => '31 Cessna Ct',
            'slug' => '31-cessna-ct',
            'google_place_id' => 'ChIJ8fulZrQxWIgR8PpSefWe8t0',
        ]);

        $named = Venue::factory()->create([
            'name' => 'SynergyMill',
            'slug' => 'synergymill',
            'google_place_id' => null,
        ]);

        $resolved = $this->venueData(
            'SynergyMill',
            googlePlaceId: 'ChIJ8fulZrQxWIgR8PpSefWe8t0',
        )->resolveVenue();

        $this->assertTrue($named->is($resolved));
        $this->assertNull($resolved->fresh()->google_place_id);
        $this->assertSame(2, Venue::query()->count());
    }

    public function test_a_blank_name_with_a_place_id_does_not_reuse_an_unnamed_venue(): void
    {
        $unnamed = Venue::factory()->create([
            'name' => '',
            'slug' => null,
            'address' => '101 N Main St',
            'google_place_id' => null,
            'apple_maps_place_id' => null,
        ]);

        $created = $this->venueData(
            '',
            '31 Cessna Ct',
            googlePlaceId: 'ChIJu2y13PguWIgR8fcwvNjYBBA',
            appleMapsPlaceId: 'I9BEC17763FC84469',
        )->resolveVenue();

        $this->assertFalse($unnamed->is($created));
        $this->assertNull($unnamed->fresh()->google_place_id);
        $this->assertNull($unnamed->fresh()->apple_maps_place_id);
        $this->assertSame('ChIJu2y13PguWIgR8fcwvNjYBBA', $created->google_place_id);
        $this->assertSame(2, Venue::query()->count());

        $again = $this->venueData(
            '',
            '31 Cessna Ct',
            googlePlaceId: 'ChIJu2y13PguWIgR8fcwvNjYBBA',
        )->resolveVenue();

        $this->assertTrue($created->is($again));
        $this->assertSame(2, Venue::query()->count());
    }

    public function test_a_blank_name_without_a_place_id_still_reuses_the_unnamed_venue(): void
    {
        $unnamed = Venue::factory()->create([
            'name' => '',
            'slug' => null,
            'address' => '101 N Main St',
        ]);

        $resolved = $this->venueData('')->resolveVenue();

        $this->assertTrue($unnamed->is($resolved));
        $this->assertSame(1, Venue::query()->count());
    }

    public function test_a_new_venue_is_posted_to_the_debugging_webhook(): void
    {
        config(['logging.channels.slack.url' => 'https://hooks.slack.com/services/test/new-venue']);
        Http::fake();

        $this->venueData('SynergyMill', googlePlaceId: 'place-name')->resolveVenue();

        Http::assertSent(fn ($request) => $request->url() === 'https://hooks.slack.com/services/test/new-venue'
                && $request['username'] === 'Venue Import'
                && str_contains($request['text'], 'Event import created a venue')
                && str_contains($request['text'], 'SynergyMill')
                && str_contains($request['text'], 'place-name'));
    }

    public function test_a_debugging_webhook_failure_still_returns_the_venue(): void
    {
        config(['logging.channels.slack.url' => 'https://hooks.slack.com/services/test/new-venue']);
        Http::fake(function () {
            throw new ConnectionException('timed out');
        });

        $venue = $this->venueData('SynergyMill')->resolveVenue();

        $this->assertSame('SynergyMill', $venue->name);
        $this->assertSame(1, Venue::query()->count());
    }

    public function test_unslugable_names_do_not_share_a_blank_slug(): void
    {
        $first = $this->venueData('!!!')->resolveVenue();
        $second = $this->venueData('???')->resolveVenue();
        $repeat = $this->venueData('!!!', 'Different address')->resolveVenue();

        $this->assertNull($first->slug);
        $this->assertNull($second->slug);
        $this->assertFalse($first->is($second));
        $this->assertTrue($first->is($repeat));
        $this->assertSame(2, Venue::query()->count());
    }

    public function test_soft_deleted_venue_is_not_reused(): void
    {
        $deleted = Venue::factory()->create([
            'name' => 'Fireforge Crafted Beer',
            'slug' => 'fireforge-crafted-beer',
        ]);
        $deleted->delete();

        $resolved = $this->venueData('Fireforge Crafted Beer')->resolveVenue();

        $this->assertFalse($deleted->is($resolved));
        $this->assertSame(1, Venue::query()->count());
        $this->assertSame(2, Venue::withTrashed()->count());
    }

    /**
     * @return ArrayObject<int, MessageLogged>
     */
    private function captureVenueLogs(): ArrayObject
    {
        $logged = new ArrayObject;

        Log::listen(function (MessageLogged $message) use ($logged) {
            if ($message->message === 'Event import created a venue') {
                $logged->append($message);
            }
        });

        return $logged;
    }

    private function venueData(
        string $name,
        string $address = '100 Main St',
        float $lat = 34.8,
        float $lon = -82.4,
        ?string $googlePlaceId = null,
        ?string $appleMapsPlaceId = null,
    ): VenueData {
        return VenueData::from([
            'id' => 'venue-1',
            'name' => $name,
            'address' => $address,
            'city' => 'Greenville',
            'state' => 'SC',
            'zip' => '29601',
            'country' => 'US',
            'lat' => $lat,
            'lon' => $lon,
            'googlePlaceId' => $googlePlaceId,
            'appleMapsPlaceId' => $appleMapsPlaceId,
        ]);
    }
}
