<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;

class Patient extends Model
{
    use Searchable;

    protected $table = "patient";
     protected $fillable = [
        'full_name',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'patient_no',
        'gender',
    ];

    public function document()
    {
        return $this->hasMany(Document::class);
    }
}
