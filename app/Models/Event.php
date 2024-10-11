<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $primaryKey = 'id';

    protected $fillable = [
        'uuid', 'title', 'date', 'time', 'guests'
    ];

    protected $casts = [
        'created_at' => 'datetime:d M, Y',
        'updated_at' => 'datetime:d M, Y'
    ];
}
