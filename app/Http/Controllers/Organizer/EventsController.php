<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Events;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function create()
    {
        return view('organizer.events.create');
    }

    public function createPost(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'event_rules' => 'required',
            'date_time' => 'required|date',
            'image' => 'required',
            'location' => 'required',
            'city_id' => 'required',
        ]);

        $event = new Events();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->event_rules = $request->event_rules;
        $event->location = $request->location;
        $event->date_time = $request->date_time;
        $event->city_id = $request->city_id;
        $event->user_id = Auth::user()->id;
        $event->status = 1;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('upload/events'), $imageName);

            if ($event->image && file_exists(public_path($event->image))) {
                unlink(public_path($event->image));
            }
            $event->image = 'upload/events/' . $imageName;
        }
        if ($request->hasFile('sitting_plan')) {
            $image = $request->file('sitting_plan');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('upload/events/sitting_plan'), $imageName);

            if ($event->sitting_plan && file_exists(public_path($event->sitting_plan))) {
                unlink(public_path($event->sitting_plan));
            }
            $event->sitting_plan = 'upload/events/sitting_plan/' . $imageName;
        }
        $event->save();

        if ($event) {
            foreach ($request->ticket_categories as $key => $category) {
                $ticketCategory = new TicketCategory();
                $ticketCategory->event_id = $event->id;
                $ticketCategory->name = $category['name'];
                $ticketCategory->price = $category['price'];
                $ticketCategory->ticket_quantity = $category['quantity'];
                $ticketCategory->save();
            }
        }

        return redirect()->route('organizer.index');
    }

    public function update($id)
    {
        $event = Events::findOrFail($id);
        return view('organizer.events.update', compact('event'));
    }

    public function updatePost(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'event_rules' => 'required',
            'date_time' => 'required|date',
            'location' => 'required',
            'city_id' => 'required',
        ]);

        $event = Events::findOrFail($id);
        $event->title = $request->title;
        $event->description = $request->description;
        $event->event_rules = $request->event_rules;
        $event->date_time = $request->date_time;
        $event->city_id = $request->city_id;
        $event->location = $request->location;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('upload/events'), $imageName);
            if ($event->image && file_exists(public_path($event->image))) {
                unlink(public_path($event->image));
            }
            $event->image = 'upload/events/' . $imageName;
        }
        $event->save();

        foreach ($request->ticket_categories as $categoryId => $category) {
            $ticketCategory = TicketCategory::findOrFail($categoryId);
            $ticketCategory->name = $category['name'];
            $ticketCategory->price = $category['price'];
            $ticketCategory->ticket_quantity = $category['quantity'];
            $ticketCategory->save();
        }

        return redirect()->route('organizer.index');
    }

    public function delete($id)
    {
        $event = Events::findOrFail($id);

        $event->getTicketCategories()->delete();

        $event->delete();

        return redirect()->route('organizer.index');
    }

    public function closeEvent($id)
    {
        $event = Events::findOrFail($id);
        $event->status = 0;
        $event->save();

        return redirect()->back();
    }
}
