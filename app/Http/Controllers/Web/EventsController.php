<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\OrderItems;
use App\Models\Orders;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{
    public function index()
    {
        $events = Events::where('status', 1)->get();
        return view('web.events.index', compact('events'));
    }

    public function show($id)
    {
        $event = Events::findOrFail($id);
        return view('web.events.show', compact('event'));
    }

    public function cart()
    {
        $cart = session('cart', []);

        $totalAmount = 0;
        foreach ($cart as $ticketInfo) {
            $totalAmount += $ticketInfo['quantity'] * $ticketInfo['price'];
        }

        return view('web.events.cart', compact('cart', 'totalAmount'));
    }

    public function buyTicket(Request $request, $eventId, $categoryId)
    {
        $category = TicketCategory::findOrFail($categoryId);
        $event = Events::findOrFail($eventId);

        $ticketInfo = [
            'event_id' => $eventId,
            'event_name' => $event->title,
            'category_id' => $categoryId,
            'category_name' => $category->name,
            'quantity' => $request->input('quantity'),
            'price' => $category->price,
            'seat_no' => null,
            'image' => $event->image,
        ];

        $cart = session()->get('cart', []);

        $cart[] = $ticketInfo;

        $request->session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Add Cart');
    }

    public function updateCart(Request $request)
    {
        $index = $request->input('index');
        $action = $request->input('action');
        $cart = session('cart', []);

        if ($action == 'increase') {
            $cart[$index]['quantity']++;
        } elseif ($action == 'decrease' && $cart[$index]['quantity'] > 1) {
            $cart[$index]['quantity']--;
        }

        session(['cart' => $cart]);

        return redirect()->route('web.events.cart');
    }

    public function confirmPurchase(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $categories = [];

        $totalAmount = 0;
        foreach ($cart as $ticketInfo) {
            $totalAmount += $ticketInfo['quantity'] * $ticketInfo['price'];
            if (!isset($categories[$ticketInfo['category_id']])) {
                $categories[$ticketInfo['category_id']] = 0;
            }
            $categories[$ticketInfo['category_id']] += $ticketInfo['quantity'];
        }

        foreach ($categories as $categoryId => $quantity) {
            $category = TicketCategory::findOrFail($categoryId);
            $remainingQuantity = $category->remainingQuantity();
            if ($quantity > $remainingQuantity) {
                return redirect()->route('web.events.cart')->with('error', 'You are trying to purchase more tickets than available for category: ' . $category->name);
            }
        }

        $order = new Orders();
        $order->user_id = Auth::id();
        $order->total_amount = $totalAmount;
        $order->payment_status = 'pending';
        $order->save();

        foreach ($cart as $ticketInfo) {
            $orderItem = new OrderItems();
            $orderItem->order_id = $order->id;
            $orderItem->ticket_category_id = $ticketInfo['category_id'];
            $orderItem->quantity = $ticketInfo['quantity'];
            $orderItem->seat_no = $ticketInfo['seat_no'];
            $orderItem->save();
        }

        $request->session()->forget('cart');

        return redirect()->route('web.events.cart')->with('success', 'Purchase confirmed!');
    }
}
