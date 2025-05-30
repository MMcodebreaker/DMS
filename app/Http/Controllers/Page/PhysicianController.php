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

use App\Services\PhysicianService;

class PhysicianController extends Controller
{
    protected PhysicianService $physicianService;

    public function __construct(PhysicianService $physicianService)
    {
        $this->physicianService = $physicianService;
    }

    public function index(): Response
    {
        $search = request('search');
        $rowsPerPage = request('rowsPerPage', 10);

        $physicians = $this->physicianService->getFilteredPhysicians($search, $rowsPerPage);
       
        return Inertia::render('Physicians', [
            'physicians' => $physicians,
            'searchContext' => request('searchContext', ''),
            'searchTerm' => request('searchTerm', ''),
            "user_info" => Auth::user()
        ]);
    
    }

     public function delete(Request $request) : RedirectResponse
    {
        $physicians = $this->physicianService->deletePhysician($request->id);
        
        if($physicians){
            toast(ToastTypesEnum::Success, "Successfully Deleted!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Deleted!");
        }
        
        return to_route('page.physicians');
    }

    public function update(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'prc_no' => 'required',
        ]);

        $physicians = $this->physicianService->updatePhysician($request->all());
        if($physicians){
            toast(ToastTypesEnum::Success, "Successfully Update!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Update!");
        }
        
        return to_route('page.physicians');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'prc_no' => 'required|unique:physician,prc_no',
        ]);

        $physicians = $this->physicianService->storePhysician($request->all());
        if($physicians){
            toast(ToastTypesEnum::Success, "Successfully Save!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Save!");
        }
        
        return to_route('page.physicians');
    }

}
