<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kost extends Model
{
    use HasFactory;

    public const ASK_ROOM_COST = 10;

    protected $fillable = [
        'title',
        'type',
        'address',
        'description',
        'price',
        'owner_id',
        'available_room',
    ];
}
