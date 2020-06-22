<?php

namespace App\Http\Controllers;

use App\EventSettings;
use App\Http\Requests\EventSettingsStoreValidationRequest;
use App\Http\Requests\EventSettingsUpdateValidationRequest;
use App\Rules\ColorValidator;
use App\Rules\EventExistValidator;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Event;
use Illuminate\Support\Facades\Validator;

class EventSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('read', EventSettings::class);

        $settings = Event::with('settings')->get();

        return response()->json($settings, 200);
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
        $this->authorize('read', EventSettings::class);

        $settings = EventSettings::findOrFail($id);

        return response()->json($settings, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EventSettingsStoreValidationRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(EventSettingsStoreValidationRequest $request)
    {
        $this->authorize('write', EventSettings::class);

        if (EventSettings::where('event_id', $request->event_id)->first() != null) {
            return response()->json(['message' => 'Event Settings already exist for this Event'], 403);
        }

        EventSettings::create($request->all());

        return response()->json(['message' => 'Event Settings created successfully'], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param EventSettingsUpdateValidationRequest $request
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(EventSettingsUpdateValidationRequest $request, $id)
    {
        $this->authorize('write', EventSettings::class);

        $event = EventSettings::findOrFail($id);
        $event->update($request->all());

        return response()->json(['message' => 'Event Settings updated successfully'], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Event $event)
    {
        $this->authorize('write', EventSettings::class);

        $settings = EventSettings::query()->where('event_id', $event->id)->first();
        $settings->delete();

        return response()->json(['message' => 'Event Settings deleted successfully'], 200);
    }
}
