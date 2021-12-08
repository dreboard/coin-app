<?php

namespace App\Mail\Users;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyBannedUser extends Mailable
{
    use Queueable, SerializesModels;

    private string $length;
    private User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $length, User $user)
    {
        $this->length = $length;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('example@example.com', 'Example')
            ->view('email.user.banned')
            ->with([
                'user' => $this->user,
                'length' => $this->length,
            ]);
    }
}
