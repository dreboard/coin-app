<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserTasksTest extends TestCase
{

    use RefreshDatabase;

    private \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $unverified_user;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->active_user = User::factory()->create();
        $this->banned_user = User::factory()->suspended()->create();
        $this->unverified_user = User::factory()->unverified()->create();
    }


    public function test_users_can_authenticate_using_the_login_screen()
    {

        $response = $this->post('/login', [
            'email' => $this->active_user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }


    /** @test */
    public function test_users_can_view_dashboard_when_active()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->active_user)->get(route('user.user_profile'));
        $response->assertViewIs("users.settings.profile");
    }

    /**
     * @test
     *
     * not authenticated and requested a protected route, you'll receive a redirection response to login with status 302
     */
    public function test_users_cannot_view_dashboard_when_banned()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->banned_user)->get(route('user.dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * @test
     *
     * not authenticated and requested a protected route, you'll receive a redirection response to login with status 302
     */
    public function test_users_cannot_view_dashboard_when_unverified()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->unverified_user)->get(route('user.dashboard'));
        $response->assertRedirect('verify-email');
    }

    /**
     * @test
     *
     * basic users can not access admin dashboard
     */
    public function test_users_cannot_view_admin_dashboard()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->active_user)->get(route('admin.dashboard'));
        $response->assertRedirect('user/dashboard');
    }




}
