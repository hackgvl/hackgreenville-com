<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\LogOutput;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Throwable;

class CreateUser extends Command
{
    use LogOutput;

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
                $this->error("User already exists for email '{$email}'.");
                return self::FAILURE;
            }

            $user = User::create([
                'email' => $email,
                'first_name' => $this->argument('first_name'),
                'last_name' => $this->argument('last_name'),
                'password' => Str::password(),
            ]);

            UserCreated::dispatch($user);

            $this->logInfo("Successfully created user for email '{$user->email}'.");
            return self::SUCCESS;
        } catch (Throwable $throwable) {
            $this->logError($throwable->getMessage());
            return self::FAILURE;
        }
    }
}
