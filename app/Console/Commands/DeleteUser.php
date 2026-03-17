<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Throwable;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user:delete
                            {email : The email of the user to delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an existing user.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        try {
            $user = User::whereEmail($email)->firstOrFail();

            if ( ! $this->confirm("Are you sure you want to delete this user?")) {
                $this->error("User delete cancelled.");
                return self::FAILURE;
            }

            if ( ! $user->delete()) {
                throw new Exception("Unable to delete user with email '{$user->email}'.");
            }

            $this->info("User deleted successfully.");

            return self::SUCCESS;
        } catch (Throwable $throwable) {
            $this->error($throwable->getMessage());
            return self::FAILURE;
        }
    }
}
