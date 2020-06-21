<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\MapSubscribeValidatonRequest;
use App\Map;
use App\Rules\EventExistValidator;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('read', Map::class);

        $maps = Map::all();

        return response()->json($maps, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('read', Map::class);

        $event = Event::query()->where('id', $id)->first();
        if($event == null){
            return response()->json('Error', 404);
        }
        $map = Map::query()->where('event_id', $event->id)->first();

        return response()->json($map, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('write', Map::class);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'json' => ['required'],
            'event_id' => ['required', new EventExistValidator],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Map::create($request->all());

        return response()->json(['message' => 'Map created successfully']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Map $map
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Map $map)
    {
        $this->authorize('write', Map::class);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'json' => ['required'],
            'event_id' => ['required', new EventExistValidator],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $map = Map::findOrFail($map->id);
        $map->update($request->all());

        return response()->json(['message' => 'Event ' . $map->name . ' updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Map $map
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Map $map)
    {
        $this->authorize('write', Map::class);

        $map = Map::findOrFail($map->id);
        $map->delete();

        return response()->json(['message' => 'Map deleted successfully']);

    }

    /**
     * @return JsonResponse
     */
    public function isAllowedToRegister(){
        if(Auth::id() == null){
            return response()->json(false, 200);
        }

        $user = User::findOrFail(Auth::id());
        return response()->json($user->can('register', Map::class), 200);
    }

    /**
     * @param MapSubscribeValidatonRequest $request
     * @param Event $event
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function subscribe(MapSubscribeValidatonRequest $request, Event $event){

        $this->authorize('register', Map::class);

        $event = Event::findOrFail($event->id);
        $map = Map::query()->where('event_id', $event->id)->first();

        $map->json = $request->map_data;
        $map->update();

        return response()->json(['message'=> 'success'], 200);
    }
}
