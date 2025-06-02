<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Inertia\Response;
use Inertia\Inertia;

use function App\Helpers\toast;
use App\Enums\ToastTypesEnum;

use App\Services\DocumentService;
use App\Services\SettingService;
use App\Services\PatientService;
use App\Services\PhysicianService;

use App\Models\Document;
use App\Models\DocumentFile;
use App\Models\DocumentTaggPhysician;

class DocumentController extends Controller
{
    protected DocumentService $documentService;
    protected SettingService $settingService;
    protected PatientService $patientService;
    protected PhysicianService $physicianService;

    public function __construct(DocumentService $documentService, SettingService $settingService, PatientService $patientService, PhysicianService $physicianService)
    {
        $this->documentService = $documentService;
        $this->settingService = $settingService;
        $this->patientService = $patientService;
        $this->physicianService = $physicianService;
    }

    public function index(): Response
    {
        $search = request('search');
        $search_physician = request('search_physician');
        $search_table = request('search_table');
        $rowsPerPage = request('rowsPerPage', 10);

        $patients = $this->patientService->getFilteredPatients($search, 10);
        $physicians = $this->physicianService->getFilteredPhysicians($search_physician, 10);
        $data = $this->documentService->getFilteredDocuments($search_table, $rowsPerPage );
      
        return Inertia::render('Document', [
            "user_info" => Auth::user(),
            "patients" => $patients,
            "physicians"  => $physicians,
            "data" => $data,
            'searchContext' => request('searchContext', ''),
            'searchTerm' => request('searchTerm', ''),
        ]);
    
    }

    public function storeDocumentV1(Request $request)
    {
        $setting_data = $this->settingService->getSettings();
        $settings = json_decode($setting_data);

         $request->validate([
            'patient' => 'required',
            'physician' => 'required',
            'document_no' => 'required|unique:document,document_no',
            'tagg' => 'required',
            'attachment' => 'required',
        ]);


        $file_url = array();  
        $patients= request('patient');
        $physicians = request('physician');
        $tagg = request('tagg');
        $document_no = request('document_no');
        $summary = request('summary');

        foreach($settings as $setting)
        {
            if ($setting->value === "pdf_store_on_premise_server" && $setting->status === 1) {
                if ($request->hasFile('attachment')) {
                    $files = $request->file('attachment');  

                    foreach ($files as $file) {
                        $extension = $file->getClientOriginalExtension();
                        $filename = time() . '_' . uniqid() . '.' . $extension;
                        $destinationPath = public_path('upload/attachment');

                        if ($file->move($destinationPath, $filename)) {
                            $file_url[] =   asset('upload/attachment/' . $filename); ;
                        } else {

                            toast(ToastTypesEnum::Error, "Failed to upload file: $filename");
                            return;
                        }
                    }
                   
                } else {
                    toast(ToastTypesEnum::Error, "No files uploaded.");
                }
            }

            if ($setting->value === "enable_ai" && $setting->status === 1) {

            }
        }

      

        try {
            DB::beginTransaction();

            $document = new Document();
            $document->patient_id = $patients['id'];
            $document->document_no = $document_no;
            $document->tag = $tagg;
            $document->summary = $summary;
            $document->user_id = Auth::id();

            if (!$document->save()) {
                throw new \Exception("Failed to save document.");
            }

            foreach ($file_url as $path) {
                $document_file = new DocumentFile();
                $document_file->document_id = $document->id;
                $document_file->url = $path;

                if (!$document_file->save()) {
                    throw new \Exception("Failed to save document file.");
                }
            }

            foreach ($physicians as $physician) {
                $document_tagg_physician = new DocumentTaggPhysician();
                $document_tagg_physician->document_id = $document->id;
                $document_tagg_physician->physician_id = $physician['id'];

                if (!$document_tagg_physician->save()) {
                    throw new \Exception("Failed to save document tagg physician.");
                }
            }

            DB::commit();
            toast(ToastTypesEnum::Success, "Document saved successfully!");
            return;

        } catch (\Exception $e) {
            DB::rollBack();
            toast(ToastTypesEnum::Error, "An error occurred: " . $e->getMessage());
            return;
        }
        
    }

}
