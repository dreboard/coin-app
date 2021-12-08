<?php

namespace Tests\Unit\Admin;

use App\Events\BannedUser;
use App\Events\UnbanUser;
use App\Listeners\Banned\EnterBannedUser;
use App\Listeners\Banned\RestoreBannedUser;
use App\Listeners\Banned\SendBannedUserNotification;
use App\Listeners\Banned\SendRestoreUserNotification;
use App\Mail\Users\NotifyBannedUser;
use App\Mail\Users\RestoreBannedUser as RestoreMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminTasksTest extends TestCase
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
        $this->unverified_user = User::factory()->unverified()->create();
        $this->admin_user = User::factory()->isAdmin()->create();


    }


    public function test_users_can_authenticate_using_the_login_screen()
    {
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


    public function test_ban_event_fires()
    {
        Event::fake();
        $this->actingAs($this->admin_user)->post(route('admin.ban_user'), [
            'user_id' => $this->active_user->id,
            'length' => '7',
        ])->assertRedirect();

        Event::assertDispatched(BannedUser::class);
    }

    public function test_unban_event_fires()
    {

        Event::fake();
        $this->actingAs($this->admin_user)->get('/admin/unban_user/'.$this->active_user->id)
        ->assertRedirect();

        Event::assertDispatched(UnbanUser::class);
    }

    public function test_ban_event_mail_sent_to_user()
    {
        $this->withoutExceptionHandling();
        Notification::fake();
        Mail::fake();

        $event = new BannedUser($this->active_user, 7);
        $listener = new SendBannedUserNotification();
        $listener->handle($event);

        Mail::assertSent(NotifyBannedUser::class);
    }

    public function test_unban_event_mail_sent_to_user()
    {
        $this->withoutExceptionHandling();
        Notification::fake();
        Mail::fake();

        $event = new UnbanUser($this->active_user);
        $listener = new SendRestoreUserNotification();
        $listener->handle($event);

        Mail::assertSent(RestoreMail::class);
    }


    public function test_db_listeners_attached_banned_event()
    {
        Event::fake();
        Event::assertListening(
            BannedUser::class,
            EnterBannedUser::class
        );
    }


    public function test_mail_listeners_attached_banned_event()
    {
        Event::fake();
        Event::assertListening(
            BannedUser::class,
            SendBannedUserNotification::class
        );
    }


}
