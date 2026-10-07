<?php

namespace Tests\Unit\Models;

use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VenueTest extends TestCase
{
    use RefreshDatabase;

    public static function missingFieldsProvider(): array
    {
        return [
            'missing city' => [
                ['name' => 'Venue', 'address' => '123 Main St', 'city' => null, 'state' => 'SC', 'zipcode' => '29601'],
                'Venue - 123 Main St SC 29601',
            ],
            'missing state' => [
                ['name' => 'Venue', 'address' => '123 Main St', 'city' => 'Greenville', 'state' => null, 'zipcode' => '29601'],
                'Venue - 123 Main St Greenville 29601',
            ],
            'missing zipcode' => [
                ['name' => 'Venue', 'address' => '123 Main St', 'city' => 'Greenville', 'state' => 'SC', 'zipcode' => null],
                'Venue - 123 Main St Greenville, SC',
            ],
            'missing city and state' => [
                ['name' => 'Venue', 'address' => '123 Main St', 'city' => null, 'state' => null, 'zipcode' => '29601'],
                'Venue - 123 Main St 29601',
            ],
            'missing city state and zipcode' => [
                ['name' => 'Venue', 'address' => '123 Main St', 'city' => null, 'state' => null, 'zipcode' => null],
                'Venue - 123 Main St',
            ],
            'missing address' => [
                ['name' => 'Venue', 'address' => null, 'city' => 'Greenville', 'state' => 'SC', 'zipcode' => '29601'],
                'Venue -  Greenville, SC 29601',
            ],
        ];
    }
    #[Test]
    public function full_address_with_all_fields(): void
    {
        $venue = new Venue([
            'name' => 'Test Venue',
            'address' => '123 Main St',
            'city' => 'Greenville',
            'state' => 'SC',
            'zipcode' => '29601',
        ]);

        $this->assertEquals('Test Venue - 123 Main St Greenville, SC 29601', $venue->fullAddress());
    }

    #[Test]
    #[DataProvider('missingFieldsProvider')]
    public function full_address_handles_missing_fields(array $attributes, string $expected): void
    {
        $venue = new Venue($attributes);

        $this->assertEquals($expected, $venue->fullAddress());
    }

    #[Test]
    public function normalize_slug_uses_the_name_when_the_slug_is_blank(): void
    {
        $this->assertSame('open-works', Venue::normalizeSlug(null, 'Open Works'));
        $this->assertSame('open-works', Venue::normalizeSlug('   ', 'Open Works'));
    }

    #[Test]
    public function normalize_slug_keeps_a_custom_slug(): void
    {
        $this->assertSame('openworks', Venue::normalizeSlug('openworks', 'Open Works'));
    }

    #[Test]
    public function normalize_slug_canonicalizes_a_provided_slug(): void
    {
        $this->assertSame('open-works', Venue::normalizeSlug('Open Works', 'Something Else'));
    }

    #[Test]
    public function normalize_slug_returns_null_when_neither_value_can_be_slugified(): void
    {
        $this->assertNull(Venue::normalizeSlug(null, '!!!'));
        $this->assertNull(Venue::normalizeSlug('---', '!!!'));
    }

    #[Test]
    public function saving_does_not_rewrite_a_slug(): void
    {
        $custom = Venue::factory()->create([
            'name' => 'Open Works',
            'slug' => 'openworks',
        ]);
        $blank = Venue::factory()->create([
            'name' => 'Open Works',
            'slug' => null,
        ]);

        $custom->update(['name' => 'Open Works Downtown']);

        $this->assertSame('openworks', $custom->fresh()->slug);
        $this->assertNull($blank->fresh()->slug);
    }
}
