<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Searchable;

class DocumentTaggPhysician extends Model
{
    use HasFactory,Searchable;
     protected $table = "document_tagg_physician";
    protected $fillable = [
        'document_id',
        'physician_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function physician()
    {
        return $this->belongsTo(Physician::class);
    }
}
