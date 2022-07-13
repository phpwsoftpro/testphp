<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateMessageData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generated:data {user} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'php artisan generate:data 3 30: 3 number user, 30 number message';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $user = $this->argument('user');
            $message = $this->argument('message');
            if ($user) {
                User::truncate();
                User::factory()->count($user)->create();
            }

            if ($message) {
                Message::truncate();
                Message::factory()->count($message)->create();
            }

            printf('Generated: %d Users and %d user messages', $user, $message);
        } catch (\Exception $exception) {
            printf('Error: %s', $exception->getMessage());
        }

        return 0;
    }
}
