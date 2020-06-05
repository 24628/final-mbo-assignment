<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\JsonResponse;

class OverviewController extends Controller
{
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
            ->first();

        return response()->json($s, 200);
    }
}
