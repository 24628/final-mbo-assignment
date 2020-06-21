<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\EventValidationRequest;
use App\Http\Requests\SubscribeValidationRequest;
use App\RegistrationEvents;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('read', Event::class);

        return response()->json(Event::all(), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Event $event
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Event $event)
    {
        $this->authorize('read', Event::class);

        return response()->json($event, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EventValidationRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(EventValidationRequest $request)
    {
        $this->authorize('write', Event::class);

        Event::create($request->all());

        return response()->json(['message' => 'Event created successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EventValidationRequest $request
     * @param Event $event
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(EventValidationRequest $request, Event $event)
    {
        $this->authorize('write', Event::class);

        $event = Event::findOrFail($event->id);
        $event->update($request->all());

        return response()->json(['message' => 'Event ' . $event->name . ' updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Event $event
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Event $event)
    {
        $this->authorize('write', Event::class);

        $event = Event::findOrFail($event->id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }

    /**
     * @param SubscribeValidationRequest $request
     * @param Event $event
     * @return JsonResponse
     */
    public function subscribe(SubscribeValidationRequest $request, Event $event)
    {
        $q = RegistrationEvents::query()
            ->where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();
        if ($q != null) {
            $q->user_id = Auth::id();
            $q->event_id = $event->id;
            $q->item_ids = $request->item_ids;
            $q->update();
            return response()->json(['message' => 'successfully updated subscribed to the event'], 200);
        }

        if(RegistrationEvents::query()->where('event_id',$event->id)->count() >= $event->settings->max_registrations){
            return response()->json(['message' => 'Event is already full'], 401);
        }

        $subscribe = new RegistrationEvents;
        $subscribe->user_id = Auth::id();
        $subscribe->event_id = $event->id;
        $subscribe->item_ids = $request->item_ids;
        $subscribe->save();

        return response()->json(['message' => 'successfully subscribed to the event'], 200);
    }

    /**
     * @param Event $event
     * @return JsonResponse
     * @throws Exception
     */
    public function unsubscribe(Event $event)
    {
        $subscription = RegistrationEvents::query()
            ->where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($subscription == null) {
            return response()->json(['message' => 'User is not subscribed to this event'], 403);
        }

        $subscription->delete();

        return response()->json(['message' => 'successfully subscribed to the event'], 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function tickets($id){

        $tickets = RegistrationEvents::query()
            ->where('event_id', $id)
            ->get()
            ->count();

        return response()->json($tickets, 200);
    }
}
