<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\LogOutput;
use App\Models\User;
use Illuminate\Console\Command;
use Throwable;

class DeactivateUser extends Command
{
    use LogOutput;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user:deactivate {email : The email address of the account to deactivate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate a user.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        try {
            $user = User::whereEmail($email)->firstOrFail();

            if ( ! $user->active) {
                $this->error("User with the email '{$user->email}' is already deactivated.");
                return self::FAILURE;
            }

            $user->active = false;
            $user->save();

            $this->logInfo("User successfully deactivated.");

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            $this->logError($throwable->getMessage());
            return self::FAILURE;
        }
    }
}
