<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user:create
                            {email : The new user\'s email}
                            {first_name : The new user\'s first name}
                            {last_name : The new user\'s last name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user who can access the admin dashboard.';

    /**
     * @return int
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        try {
            if (User::whereEmail($email)->exists()) {
                throw new Exception("User already exists for email '{$email}'.");
            }

            $user = User::create([
                'email' => $email,
                'first_name' => $this->argument('first_name'),
                'last_name' => $this->argument('last_name'),
                'password' => Str::password(),
            ]);

            // todo: attempt to notify new user via email

            $this->info("Successfully created user for email '{$user->email}'.");
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
