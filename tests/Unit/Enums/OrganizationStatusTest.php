<?php

namespace Tests\Unit\Enums;

use App\Enums\OrganizationStatus;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrganizationStatusTest extends TestCase
{
    #[Test]
    public function inactive_status_value_and_admin_label_are_spelled_inactive(): void
    {
        $status = OrganizationStatus::Inactive;

        $this->assertSame('inactive', $status->value);
        $this->assertSame('Inactive', $status->getLabel());
    }
}
