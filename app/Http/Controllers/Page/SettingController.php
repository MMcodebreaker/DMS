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

use App\Services\SettingService;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(): Response
    {
        $search = request('search');
        $rowsPerPage = request('rowsPerPage', 10);

        $settings = $this->settingService->getFilteredSettings($search, $rowsPerPage);
       
        return Inertia::render('Settings', [
            'settings' => $settings,
            'searchContext' => request('searchContext', ''),
            'searchTerm' => request('searchTerm', ''),
            "user_info" => Auth::user()
        ]);
    
    }

     public function delete(Request $request) : RedirectResponse
    {
        $settings = $this->settingService->deleteSetting($request->id);
        
        if($settings){
            toast(ToastTypesEnum::Success, "Successfully Deleted!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Deleted!");
        }
        
        return to_route('page.settings');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $settings = $this->settingService->updateSetting($request->all());
        if($settings){
            toast(ToastTypesEnum::Success, "Successfully Update!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Update!");
        }
        
        return to_route('page.settings');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $patient = $this->settingService->storeSetting($request->all());
        if($patient){
            toast(ToastTypesEnum::Success, "Successfully Save!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Save!");
        }
        
        return to_route('page.settings');
    }
}
