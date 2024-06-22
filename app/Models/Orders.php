<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total_amount', 'payment_status'
    ];

    public function getItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
}
