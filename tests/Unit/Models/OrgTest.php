<?php

namespace Tests\Unit;

use App\Models\Org;
use App\Enums\OrganizationStatus;
use Carbon\Carbon;
use Tests\DatabaseTestCase;

class OrgTest extends DatabaseTestCase {
  public function setUp(): void {
    parent::setUp();
  }

  public function test_scopeActive_pulls_active_organization() {
    $org = Org::factory()->create([
      'status' => OrganizationStatus::Active
    ]);

    $result = Org::query()->active()->first();

    $this->assertEquals($org->id, $result->id);
  }

  public function test_scopeActive_does_not_pull_inactive_organization() {
    $org = Org::factory()->create([
      'status' => OrganizationStatus::InActive
    ]);

    $result = Org::query()->active()->first();

    $this->assertNull($result);
  }
}
