<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Http\Resources\Json\JsonResource;

class EventController extends Controller
{
    use CanLoadRelationships;
    private array $relations = ['user','attendees', 'attendees.user'];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = $this->loadRelationships(Event::query(), $this->relations);
        return EventResource::collection($query->paginate());
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $event = Event::create([
            ...$request->validated(),
           'user_id'=>1
        ]);

        return new EventResource($this->loadRelationships($event, $this->relations));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['user', 'attendees']);
        return new EventResource($this->loadRelationships($event, $this->relations));
    }


    public function update(UpdateEventRequest $request, Event $event)
    {
        $event->update($request->validated());
        $event->load(['user', 'attendees']);
        return new EventResource($this->loadRelationships($event, $this->relations));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message'=>'Event deleted successfully']);
    }
}
