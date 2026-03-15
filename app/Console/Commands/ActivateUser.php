<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Throwable;

class ActivateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user:activate {email : The email address of the account to activate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate an user.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        try {
            $user = User::whereEmail($email)->firstOrFail();

            if ($user->active) {
                $this->error("User with the email '{$user->email}' is already activated.");
                return self::FAILURE;
            }

            $user->active = true;
            $user->save();

            $this->info("User successfully activated.");

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            $this->error($throwable->getMessage());
            return self::FAILURE;
        }
    }
}
