<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
                $this->sendError("Unable to delete user with email '{$user->email}'.");

                return self::FAILURE;
            }

            $this->info("User deleted successfully.");

            return self::FAILURE;
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
