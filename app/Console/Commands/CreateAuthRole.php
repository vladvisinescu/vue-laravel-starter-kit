<?php

namespace App\Console\Commands;

use App\Models\Auth\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAuthRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snick:role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Role for this app.';

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

        // Display a table of available Roles
        $roles = Role::all(['id', 'name']);
        $this->table(['ID', 'Name'], $roles);

        // Ask user for new Role info
        $display_name = $this->ask('Give the new role a friendly name');
        $name = Str::slug($display_name);
        $description = $this->ask('(Optional) Describe this role');

        $role = new Role();
        $role->name = $name;
        $role->display_name = $display_name;
        $role->description = $description;
        $role->save();

        $this->info('Role "' . $display_name . '"" has been successfully created!');
    }
}
