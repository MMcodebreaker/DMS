<?php

namespace App\Services;
use App\Models\Patient;
use App\Models\Physician;
use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\DocumentTaggPhysician;

class DocumentService
{
    public function getFilteredDocuments(string $search = null,  int $rowsPerPage = 10)
    {
        return Document::with(['patient', 'taggedPhysicians.physician', 'files'])
                 ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        // Search in Document fields
                        $q->where('tag', 'like', "%{$search}%")
                        ->orWhere('document_no', 'like', "%{$search}%")
                        
                        // Search in related Patient fields
                        ->orWhereHas('patient', function ($subQuery) use ($search) {
                            $subQuery->where('firstname', 'like', "%{$search}%")
                                ->orWhere('middlename', 'like', "%{$search}%")
                                ->orWhere('lastname', 'like', "%{$search}%")
                                ->orWhere('full_name', 'like', "%{$search}%")
                                ->orWhere('patient_no', 'like', "%{$search}%");
                        });
                    });
                })
                ->paginate($rowsPerPage)
                ->withQueryString()
                ->through(fn ($document) => [
                    'id' => $document->id,
                    'document_no' => $document->document_no,
                    'tag' => $document->tag,
                    'created_at' => $document->created_at->format('m/d/Y'),
                    'patient' => [
                        'id' => $document->patient->id,
                        'full_name' => $document->patient->full_name,
                        'firstname' => $document->patient->firstname,
                        'middlename' => $document->patient->middlename,
                        'lastname' => $document->patient->lastname,
                        'suffix' => $document->patient->suffix,
                        'patient_no' => $document->patient->patient_no,
                        'gender' => $document->patient->gender,
                    ],
                    'tagged_physicians' => $document->taggedPhysicians,
                    'files' => $document->files,
                ]);

    }
}