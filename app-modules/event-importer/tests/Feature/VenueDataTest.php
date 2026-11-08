<?php

namespace HackGreenville\EventImporter\Tests\Feature;

use App\Models\Venue;
use HackGreenville\EventImporter\Data\VenueData;
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

    public function test_blank_name_does_not_reuse_another_unnamed_venue(): void
    {
        $unnamed = Venue::factory()->create([
            'name' => '',
            'slug' => null,
            'address' => '101 N Main St',
            'city' => 'Greenville',
            'state' => 'SC',
            'zipcode' => '29601',
            'lat' => '34.8516838',
            'lng' => '-82.3998468',
        ]);

        $resolved = $this->venueData('', '31 Cessna Ct suite b', 34.8484931, -82.3431733)->resolveVenue();

        $this->assertFalse($unnamed->is($resolved));
        $this->assertSame('', $unnamed->fresh()->name);
        $this->assertSame('101 N Main St', $unnamed->fresh()->address);
        $this->assertSame('31 Cessna Ct suite b', $resolved->name);
        $this->assertSame('31-cessna-ct-suite-b', $resolved->slug);
        $this->assertSame('31 Cessna Ct suite b', $resolved->address);
        $this->assertSame('Greenville', $resolved->city);
        $this->assertSame('SC', $resolved->state);
        $this->assertSame('29601', $resolved->zipcode);
        $this->assertEquals(34.8484931, $resolved->lat);
        $this->assertEquals(-82.3431733, $resolved->lng);
        $this->assertSame(2, Venue::query()->count());

        $again = $this->venueData('   ', '31 Cessna Ct suite b', 1.0, 2.0)->resolveVenue();

        $this->assertTrue($resolved->is($again));
        $this->assertEquals(34.8484931, $again->fresh()->lat);
        $this->assertSame(2, Venue::query()->count());
    }

    public function test_blank_name_does_not_match_a_named_venue_at_the_same_street(): void
    {
        $named = Venue::factory()->create([
            'name' => 'OpenWorks',
            'slug' => 'openworks',
            'address' => '101 N Main St',
        ]);

        $resolved = $this->venueData('', '101 N Main St')->resolveVenue();

        $this->assertFalse($named->is($resolved));
        $this->assertSame('101 N Main St', $resolved->name);
        $this->assertSame('101-n-main-st', $resolved->slug);
        $this->assertSame(2, Venue::query()->count());
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

    private function venueData(
        string $name,
        string $address = '100 Main St',
        float $lat = 34.8,
        float $lon = -82.4,
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
        ]);
    }
}
