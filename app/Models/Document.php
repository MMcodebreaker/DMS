<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Searchable;

class Document extends Model
{
    use HasFactory, Searchable;
     protected $table = "document";
    protected $fillable = [
        'patient_id',
        'tag',
        'document_no',
        'user_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taggedPhysicians()
    {
        return $this->hasMany(DocumentTaggPhysician::class);
    }

    public function files()
    {
        return $this->hasMany(DocumentFile::class);
    }
}
