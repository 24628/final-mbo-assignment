<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ReflectionClass;

class Permissions extends Model {

    public function getAllPermissions() {
        try {
            $reflectionClass = new ReflectionClass($this);
            return $reflectionClass->getConstants();
        } catch (\ReflectionException $e) {
            abort(500, "Internal Server Error");
        }
    }

    public const __ADMIN__ = 'Admin';
    public const __NOTIFY_USER__ = 'notify_users';
    public const __ADMIN_UPDATE_USER__ = 'notify_users';

    public const __READ_ROLE__ = 'read_role';
    public const __WRITE_ROLE__ = 'write_role';

    public const __READ_CV__ = 'read_cv';
    public const __WRITE_CV__ = 'write_cv';

    public const __READ_PROFILE__ = 'read_profile';
    public const __WRITE_PROFILE__ = 'write_profile';

    public const __READ_EVENT__ = 'read_event';
    public const __WRITE_EVENT__ = 'write_event';

    public const __READ_ITEM__ = 'read_item';
    public const __WRITE_ITEM__ = 'write_item';

    public const __READ_EVENT_SETTINGS__ = 'read_event_settings';
    public const __WRITE_EVENT_SETTINGS__ = 'write_event_settings';

    public const __READ_MAP__ = 'read_map';
    public const __WRITE_MAP__ = 'write_map';

    public const __READ_PROGRAMS__ = 'read_programs';
    public const __WRITE_PROGRAMS__ = 'write_programs';

    public const __READ_BLOCK__ = 'read_block';
    public const __WRITE_BLOCK__ = 'write_block';

    public const __READ_USER__ = 'read_user';
    public const __WRITE_USER__ = 'write_user';

    public const __READ_CONGRESS__ = 'read_congress';
    public const __WRITE_CONGRESS__ = 'write_congress';

    public const __READ_PROGRAM_ITEMS__ = 'read_program_items';
    public const __WRITE_PROGRAM_ITEMS__ = 'write_program_items';

    public const __REGISTER_MAP_LOCATION__ = 'register_map_location';
    public const __PRINT_BADGE__ = 'print_badge';

}
