<?php

namespace App\Http\Controllers;

use App\Event;
use App\RegistrationEvents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class OverviewController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function event()
    {
        $s = Event::query()
            ->with('settings')
            ->has('settings')
            ->get();

        return response()->json($s, 200);
    }

    /**
     * @param Event $event
     * @return JsonResponse
     */
    public function index(Event $event)
    {
        $s = Event::query()
            ->where('id', $event->id)
            ->with('settings')
            ->with('program')
            ->with('program.programItems')
            ->with('congress')
            ->with('congress.block')
            ->with('congress.block.items')
            ->with('map')
            ->first();

        return response()->json($s, 200);
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

        if($q != null) {
            return response()->json($q, 200);
        }

        return response()->json(false, 200);
    }
}
