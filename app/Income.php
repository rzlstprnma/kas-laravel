<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = [
        "user_id", "income_name", "nominal", "date"
    ];
}
