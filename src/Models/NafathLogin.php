<?php

namespace MohamadZatar\Nafath\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NafathLogin extends Model
{
    use HasFactory;

    protected $fillable = [
        'trans_id',
        'random',
        'status',
    ];

    // Define any additional model methods or relationships if needed
}
