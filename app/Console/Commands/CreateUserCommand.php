<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = text(label: 'Name', required: true);
        $email = text(label: 'Email', required: true);
        $password = password(label: 'Password', required: true);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->info('User created successfully');
    }
}
