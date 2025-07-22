<?php

namespace Tests\Unit\Models;

use App\Enums\OrganizationStatus;
use App\Models\Org;
use Tests\DatabaseTestCase;

class OrgTest extends DatabaseTestCase
{
    public function test_scope_active_pulls_active_organization()
    {
        $org = Org::factory()->create([
            'status' => OrganizationStatus::Active
        ]);

        $result = Org::query()->active()->first();

        $this->assertEquals($org->id, $result->id);
    }

    public function test_scope_active_does_not_pull_inactive_organization()
    {
        $org = Org::factory()->create([
            'status' => OrganizationStatus::InActive
        ]);

        $result = Org::query()->active()->first();

        $this->assertNull($result);
    }
}
