<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;


class Physician extends Model
{
    use Searchable;

    protected $table = "physician";
    protected $fillable = [
        'full_name',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'prc_no',
        'specialization',
    ];

    public function document_tag()
    {
        return $this->hasMany(DocumentTaggPhysician::class);
    }
}
