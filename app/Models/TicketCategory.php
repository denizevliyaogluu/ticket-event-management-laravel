<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'ticket_quantity',
        'price',
    ];

    public function remainingQuantity()
    {
        $purchasedQuantity = $this->orderItems()->sum('quantity');
        return $this->ticket_quantity - $purchasedQuantity;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'ticket_category_id');
    }
}
