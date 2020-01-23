<?php

namespace App\Console\Commands;

use App\Models\Auth\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAuthPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snick:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Permission for this app.';

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

        // Display a table of available Permissions
        $roles = Permission::all(['id', 'name']);
        $this->table(['ID', 'Name'], $roles);

        // Ask user for new Permission info
        $display_name = $this->ask('Give the new permission a friendly name');
        $name = Str::slug($display_name);
        $description = $this->ask('(Optional) Describe this permission');

        $permission = new Permission();
        $permission->name = $name;
        $permission->display_name = $display_name;
        $permission->description = $description;
        $permission->save();

        $this->info('Permission "' . $display_name . '"" has been successfully created!');
    }
}
