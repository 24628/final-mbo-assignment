<?php

namespace App\Policies;

use App\Permissions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MapPolicy
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
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function read(User $user) {
        return in_array(Permissions::__READ_MAP__, json_decode($user->role->permissions, 1));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function write(User $user) {
        return in_array(Permissions::__WRITE_MAP__, json_decode($user->role->permissions, 1));
    }

    /**
     * Determine whether the user can register on map location
     *
     * @param User $user
     * @return mixed
     */
    public function register(User $user) {
        return in_array(Permissions::__REGISTER_MAP_LOCATION__, json_decode($user->role->permissions, 1));
    }
}
