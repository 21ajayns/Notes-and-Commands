<?php

namespace Tests;

use App\Models\Organization\Organization;
use App\Models\User\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private ?Organization $organization = null;

    /**
     * The organization the current test works in, created on first use.
     */
    protected function organization(): Organization
    {
        return $this->organization ??= Organization::query()->create(['name' => 'Test Organization']);
    }

    protected function organizationId(): string
    {
        return $this->organization()->getId();
    }

    /**
     * Signs in a user who belongs to the test's organization.
     */
    protected function signIn(?Organization $organization = null): User
    {
        $user = User::factory()->create([
            'organization_id' => ($organization ?? $this->organization())->getId(),
        ]);

        Sanctum::actingAs($user);

        return $user;
    }
}
