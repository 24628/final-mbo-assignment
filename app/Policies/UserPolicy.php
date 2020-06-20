<?php

namespace App\Policies;

use App\Permissions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability) {
        if(in_array(Permissions::__ADMIN__, json_decode($user->role->permissions, 1))){
            return true;
        }
    }

    /**
     * Determine whether the user can read Profile.
     *
     * @param User $user
     * @return mixed
     */
    public function read(User $user) {
        return in_array(Permissions::__WRITE_USER__, json_decode($user->role->permissions, 1));
    }

    /**
     * Determine whether the user can write profile.
     *
     * @param User $user
     * @return mixed
     */
    public function write(User $user) {
        return in_array(Permissions::__READ_USER__, json_decode($user->role->permissions, 1));
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function notify(User $user) {
        return in_array(Permissions::__NOTIFY_USER__, json_decode($user->role->permissions, 1));
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function AdminUser(User $user) {
        return in_array(Permissions::__ADMIN_UPDATE_USER__, json_decode($user->role->permissions, 1));
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function print(User $user) {
        return in_array(Permissions::__PRINT_BADGE__, json_decode($user->role->permissions, 1));
    }
}
