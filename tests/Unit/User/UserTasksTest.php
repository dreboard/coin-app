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

    //protected $baseUrl = 'http://localhost';


    private $unverified_user;
    /**
     * @var
     */
    private $banned_user;
    /**
     * @var
     */
    private $active_user;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->active_user = User::factory()->create();
        $this->unverified_user = User::factory()->unverified()->create();
    }


    public function test_users_can_authenticate_using_the_login_screen()
    {
        $this->withoutExceptionHandling();
        $this->withMiddleware();
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
    public function test_users_cannot_view_dashboard_when_unverified()
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->unverified_user)->get(route('user.dashboard'));
        $response->assertRedirect('verify-email');
    }

    /**
     * @test
     *
     * not authenticated and requested a protected route, you'll receive a redirection response to login with status 302
     */
    public function test_user_current_password_required_on_change_password()
    {
        $this->withExceptionHandling();
        $this->withOutMiddleware();

        $response = $this->actingAs($this->active_user)->post('user/user_save_password', [
            'current_password' => '', //$this->active_user->password,
            'password' => 'password',
            'confirm_password' => 'password'
        ]);
        $response->assertSessionHasErrors([
            'current_password' => 'The current password field is required.'
        ]);
    }

    public function test_user_new_password_required_on_change_password()
    {
        $this->withExceptionHandling();
        $this->withoutMiddleware();

        $response = $this->actingAs($this->active_user)->post('user/user_save_password', [
            'current_password' => 'password', //$this->active_user->password,
            'password' => '',
            'confirm_password' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'The password field is required.'
        ]);
    }

    public function test_user_confirm_password_required_on_change_password()
    {
        $this->withExceptionHandling();
        $this->withoutMiddleware();

        $response = $this->actingAs($this->active_user)->post('user/user_save_password', [
            'current_password' => 'password', //$this->active_user->password,
            'password' => 'password',
            'confirm_password' => '',
        ]);

        $response->assertSessionHasErrors([
            'confirm_password' => 'The confirm password field is required.'
        ]);
    }

    public function test_user_can_change_visibility()
    {
        $this->withoutExceptionHandling();
        $this->withOutMiddleware();
        $response = $this->actingAs($this->active_user)->post('user/user_change_visibility', [
            'profile_visibility' => 0,
            'id' => $this->active_user->id
        ]);
        $this->assertEquals('200', $response->getStatusCode());
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
