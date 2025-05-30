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

use App\Services\PatientService;

class PatientController extends Controller
{
    protected PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function index(): Response
    {
        $search = request('search');
        $rowsPerPage = request('rowsPerPage', 10);

        $patients = $this->patientService->getFilteredPatients($search, $rowsPerPage);
       
        return Inertia::render('Patients', [
            'patients' => $patients,
            'searchContext' => request('searchContext', ''),
            'searchTerm' => request('searchTerm', ''),
            "user_info" => Auth::user()
        ]);
    
    }

     public function delete(Request $request) : RedirectResponse
    {
        $patient = $this->patientService->deletePatient($request->id);
        
        if($patient){
            toast(ToastTypesEnum::Success, "Successfully Deleted!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Deleted!");
        }
        
        return to_route('page.patients');
    }

    public function update(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'patient_no' => 'required',
        ]);

        $patients = $this->patientService->updatePatient($request->all());
        if($patients){
            toast(ToastTypesEnum::Success, "Successfully Update!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Update!");
        }
        
        return to_route('page.patients');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'patient_no' => 'required|unique:patient,patient_no',
        ]);

        $patient = $this->patientService->storePatient($request->all());
        if($patient){
            toast(ToastTypesEnum::Success, "Successfully Save!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Save!");
        }
        
        return to_route('page.patients');
    }
}
