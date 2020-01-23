<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snick:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a User with the Role of Admin';

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
     * @return mixed
     */
    public function handle()
    {
        // Ask user for new Permission info
        $name = $this->ask('Give the admin a catchy name');
        $email = $this->ask('...now type the login email');
        $password = $this->secret('...and a good, strong, easy-to-forget password');

        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        if ($this->confirm('Make him a SuperAdmin?')) {
            $admin->attachRole('super-administrator');
        } else {
            $admin->attachRole('administrator');
        }

        $token = $admin->createToken('auth_login')->accessToken;

        $this->info('Admin ID: ' . $admin->id);
        $this->info('Admin Name: ' . $admin->name);
        $this->info('Admin Email: ' . $admin->email);
        $this->info('Admin Token:');
        $this->line($token);

    }
}
