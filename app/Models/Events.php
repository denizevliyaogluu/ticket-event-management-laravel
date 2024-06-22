<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'city_id', 'date_time', 'location', 'user_id', 'sitting_plan'];

    public function getOrganizers()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTicketCategories()
    {
        return $this->hasMany(TicketCategory::class, 'event_id');
    }
    public function getCity()
    {
        return $this->hasOne('App\Models\Cities', 'id', 'city_id')->withDefault([
            'id' => NULL,
            'name' => 'Belirtilmemiş',
        ]);
    }
}
