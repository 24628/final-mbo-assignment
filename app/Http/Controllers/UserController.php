<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\UpdateUserNameRequest;
use App\Map;
use App\Permissions;
use App\RegistrationEvents;
use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function updatetoken()
    {
        $user = User::find(Auth::id());
        $s = $user->generateToken();

        return response()->json(['message' => $s], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => ['string']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::query()
            ->where('name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('email', 'LIKE', '%' . $request->search . '%')
            ->get(['name', 'email', 'id']);

        return response()->json(['message' => $user], 200);
    }

    /**
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function permissions()
    {
        $this->authorize('write', Role::class);

        $permissions = new Permissions;
        return response()->json(['message' => $permissions->getAllPermissions()], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function UpdateSelectableUserRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['integer'],
            'user_id' => ['integer']
        ]);

        if ($validator->fails()) {
            abort(403);
        }

        if($request->user_id != Auth::id()){
            abort(403);
        }

        $user = User::findOrFail($request->user_id);

        if (Role::query()
                ->where("selectable", true)
                ->where('id', $request->id)
                ->first() == null
        ) {
            if($user->role_id !== $request->user_id){
                abort(403);
            }
        }

        $user->role_id = $request->id;
        $user->update();

        return response()->json(['message' => "Role Edited"], 200);
    }

    /**
     * @return JsonResponse
     */
    public function isLoggedIn(){

        return response()->json(Auth::check(), 200);

    }

    /**
     * @param UpdateUserNameRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserNameRequest $request, User $user){

        if(Auth::id() !== $user->id){
            abort(403);
        }

        $user = User::findOrFail($user->id);
        $user->update(array('name' => $request->name));

        return response()->json(['message' => 'User updated successfully'], 200);
    }

    /**
     * @param Event $event
     * @return JsonResponse
     */
    public function isSubscribed(Event $event){

        $q = RegistrationEvents::query()
            ->where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        return response()->json($q, 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function print($id){

        $this->authorize('print', User::class);

        $q = RegistrationEvents::query()
            ->where('id', $id)
            ->with('event')
            ->with('event.settings')
            ->with('user')
            ->with('user.role')
            ->with('user.profile')
            ->first();

        return response()->json($q, 200);
    }

    /**
     * @return JsonResponse
     */
    public function viewMap(){

        $user = User::findOrFail(Auth::id());

        return response()->json($user->role->role_name, 200);
    }
}
