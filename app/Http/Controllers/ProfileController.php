<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileValidationRequest;
use App\Profile;
use App\RegistrationEvents;
use App\User;
use App\Rules\Base64Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use stdClass;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('read', Profile::class);

        $profiles = Profile::with('user')->get();

        return response()->json($profiles, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Profile $profile
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Profile $profile)
    {
        $this->authorize('read', Profile::class);

        $profile = Profile::findOrFail($profile->id)->with('user')->get();

        return response()->json($profile, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ProfileValidationRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(ProfileValidationRequest $request)
    {
        $this->authorize('write', Profile::class);

        if (Profile::where('user_id', '=', Auth::id())->count() > 0) {
            return response()->json(['message' => 'Profile already exist on this user']);
        }

        $s = new stdClass();
        $s->user_id = Auth::id();

        foreach($request->all() as $k => $v) {
            $s->$k = $v;
        }

        Profile::create((array)$s);

        return response()->json(['message' => 'profile created successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProfileValidationRequest $request
     * @param Profile $profile
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(ProfileValidationRequest $request, Profile $profile)
    {
        $this->authorize('write', Profile::class);

        $profile = Profile::findOrFail($profile->id);
        $profile->update($request->all());

        return response()->json(['message' => 'Profile updated successfully '], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Profile $profile
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Profile $profile)
    {
        $this->authorize('write', Profile::class);

        $profile = Profile::findOrFail($profile->id);
        $profile->delete();

        return response()->json(['message' => 'Profile deleted successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Profile $profile
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function showcv(Profile $profile)
    {
        $this->authorize('read', Profile::class);
        $this->authorize('readCV', Profile::class);

        $cv = Profile::findOrFail($profile->id)->pluck('cv');

        return response()->json($cv, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storecv(Request $request)
    {
        $this->authorize('write', Profile::class);
        $this->authorize('writeCV', Profile::class);

        $validator = Validator::make($request->all(), [
            'cv' => [new Base64Validator, 'required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (Profile::where('user_id', '=', Auth::id())->count() > 0) {
            $profile = Profile::query()->where('user_id', Auth::id())->first();
            $profile->user_id = Auth::id();
            $profile->cv = $request->cv;
            $profile->update();

            return response()->json(['message' => 'CV updated successfully'], 200);
        }

        $profile = new Profile;
        $profile->user_id = Auth::id();
        $profile->cv = $request->cv;
        $profile->save();

        return response()->json(['message' => $request->cv], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Profile $profile
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function updatecv(Request $request, Profile $profile)
    {
        $this->authorize('write', Profile::class);
        $this->authorize('writeCV', Profile::class);

        $validator = Validator::make($request->all(), [
            'cv' => [new Base64Validator, 'required'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $profile = Profile::findOrFail($profile->id);
        $profile->user_id = Auth::id();
        $profile->cv = $request->cv;
        $profile->update();

        return response()->json(['message' => 'CV updated successfully'], 200);
    }

    /**
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function check()
    {
        $this->authorize('read', Profile::class);

        $user = User::query()
            ->where('id', Auth::id())
            ->with('profile')
            ->with('role:id,role_name')
            ->first();
        return response()->json($user, 200);

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function event($id){

        $rE = RegistrationEvents::query()->where('user_id', $id)->with('event')->get();

        return response()->json($rE, 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function visitProfile($id){

        $user = User::query()
            ->where('id', $id)
            ->with('profile')
            ->with('role:id,role_name')
            ->first();

        return response()->json($user, 200);
    }
}
