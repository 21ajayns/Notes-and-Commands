<?php
declare(strict_types=1);

namespace Tests\Feature\Http\Auth;

use App\Models\Organization\Organization;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestsAreSentToTheLoginPage(): void
    {
        $this->get('/')->assertRedirect('/login');
        $this->get('/login')->assertOk()->assertSee('Log in');
        $this->get('/register')->assertOk()->assertSee('Create your account');
    }

    public function testSignUpCreatesAnOrganizationNamedAfterThePersonAndLogsIn(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ajay',
            'email' => 'Ajay@Example.com ',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ]);

        $response->assertRedirect('/');

        $user = User::query()->where('email', 'ajay@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('secret-password', $user->getAttribute('password')));

        $organization = Organization::query()->findOrFail($user->getOrganizationId());
        $this->assertSame('Ajay', $organization->getAttribute('name'));
        $this->assertDatabaseCount('organizations', 1);
    }

    public function testEachSignUpGetsItsOwnOrganization(): void
    {
        foreach (['first@example.com', 'second@example.com'] as $email) {
            $this->post('/register', [
                'name' => 'Person',
                'email' => $email,
                'password' => 'secret-password',
                'password_confirmation' => 'secret-password',
            ]);
            $this->post('/logout');
        }

        $this->assertDatabaseCount('organizations', 2);
        $this->assertSame(2, User::query()->distinct()->count('organization_id'));
    }

    public function testSignUpRejectsAnEmailThatIsAlreadyUsed(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->from('/register')->post('/register', [
            'name' => 'Someone',
            'email' => 'taken@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function testSignUpRejectsMismatchedOrShortPasswords(): void
    {
        $this->from('/register')->post('/register', [
            'name' => 'Someone',
            'email' => 'someone@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'different-password',
        ])->assertSessionHasErrors(['password']);

        $this->from('/register')->post('/register', [
            'name' => 'Someone',
            'email' => 'someone@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors(['password']);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('organizations', 0);
    }

    public function testLoginWithTheRightDetailsSignsIn(): void
    {
        $user = User::factory()->create(['email' => 'me@example.com', 'password' => 'secret-password']);

        $this->post('/login', ['email' => 'ME@example.com', 'password' => 'secret-password'])
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function testLoginWithAWrongPasswordFails(): void
    {
        User::factory()->create(['email' => 'me@example.com', 'password' => 'secret-password']);

        $this->from('/login')->post('/login', ['email' => 'me@example.com', 'password' => 'wrong-password'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    public function testLogoutSignsOutAndReturnsToLogin(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/logout')->assertRedirect('/login');

        $this->assertGuest();
    }

    public function testSignedInPeopleSkipTheLoginAndSignUpPages(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/login')->assertRedirect('/');
        $this->get('/register')->assertRedirect('/');
        $this->get('/')->assertOk();
    }

    public function testTheBrowserSessionCanCallTheApi(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'web')
            ->withHeader('Referer', 'http://localhost/')
            ->getJson('/api/tasks')
            ->assertOk()
            ->assertExactJson([]);
    }
}
