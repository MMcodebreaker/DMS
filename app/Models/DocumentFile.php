<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Searchable;

class DocumentFile extends Model
{
    use HasFactory, Searchable;

    protected $table = "document_files";
    protected $fillable = [
        'document_id',
        'url',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
