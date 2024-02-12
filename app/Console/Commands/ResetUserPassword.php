<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user:reset-password
                            {email : Email address of the user whose password should be reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a user\'s password to a strong random password. The user will have to change their password after.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        try {
            $user = User::whereEmail($email)->firstOrFail();

            // todo: attempt to notify user that their password has been reset

            if ( ! $this->confirm("Are you sure you want to reset this user's password?")) {
                $this->error("Password reset cancelled.");

                return self::FAILURE;
            }

            $user->password = Str::password();

            if ( ! $user->save()) {
                $this->sendError("Unable to reset user's password.");

                return self::FAILURE;
            }

            $this->info("User successfully reset.");

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            $this->sendError($throwable->getMessage());
            return self::FAILURE;
        }
    }

    private function sendError(string $message): void
    {
        $this->error($message);
        Log::error("{$message} " . __CLASS__ . "::" . __METHOD__);
    }
}
