<?php

namespace App\Console\Commands;

use App\Role;
use App\Permissions;
use Illuminate\Console\Command;

class GenerateBasicRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate the 4 basic roles';

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
        $role = new Role;
        $role->id = 1;
        $role->role_name = 'Admin';
        $role->color = '#a0a867';
        $role->selectable = false;
        $role->permissions = json_encode([
            Permissions::__ADMIN__
        ]);
        $role->save();

        $role = new Role;
        $role->id = 2;
        $role->role_name = 'Bezoeker';
        $role->color = '#a0a867';
        $role->selectable = true;
        $role->permissions = json_encode([
            Permissions::__READ_USER__,
            Permissions::__WRITE_USER__,
            Permissions::__WRITE_PROFILE__,
            Permissions::__READ_PROFILE__,
            Permissions::__READ_MAP__

        ]);
        $role->save();

        $role = new Role;
        $role->id = 3;
        $role->role_name = 'Werkzoekende';
        $role->color = '#a0a867';
        $role->selectable = true;
        $role->permissions = json_encode([
            Permissions::__READ_USER__,
            Permissions::__WRITE_USER__,
            Permissions::__WRITE_PROFILE__,
            Permissions::__READ_PROFILE__,
            Permissions::__READ_MAP__
        ]);
        $role->save();

        $role = new Role;
        $role->id = 4;
        $role->role_name = 'Standhouder';
        $role->color = '#a0a867';
        $role->selectable = true;
        $role->permissions = json_encode([
            Permissions::__READ_USER__,
            Permissions::__WRITE_USER__,
            Permissions::__WRITE_PROFILE__,
            Permissions::__READ_PROFILE__,
            Permissions::__READ_MAP__
        ]);
        $role->save();
    }
}
