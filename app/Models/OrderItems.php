<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'ticket_category_id', 'quantity', 'seat_no'
    ];

    protected $casts = [
        'seat_no' => 'json'
    ];

    public function getOrder()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function getTicketCategory()
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }
}
