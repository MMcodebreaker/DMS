<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class Setting extends Model
{
    use Searchable;

    protected $table = "setting";
    protected $fillable = [
        'name',
        'value',
        'status',
    ];
}
