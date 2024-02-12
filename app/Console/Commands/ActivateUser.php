<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
                throw new Exception("User with the email '{$user->email}' is already activated.");
            }

            $user->active = true;
            $user->save();

            $this->info("User successfully activated.");

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
