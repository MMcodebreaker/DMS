<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Inertia\Inertia;

use function App\Helpers\toast;
use App\Enums\ToastTypesEnum;

use App\Services\DocumentService;
use App\Services\SettingService;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected DocumentService $documentService;
    protected SettingService $settingService;

    public function __construct(DocumentService $documentService, SettingService $settingService)
    {
        $this->documentService = $documentService;
        $this->settingService = $settingService;
    }

    public function index(): Response
    {
        return Inertia::render('Document', [
            "user_info" => Auth::user()
        ]);
    
    }

    public function storeDocumentV1(Request $request)
    {
        $setting_data = $this->settingService->getSettings();
        $settings = json_decode($setting_data);  

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
                            toast(ToastTypesEnum::Success, "Files uploaded successfully : $filename ");
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

        
    }

}
