<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

use function App\Helpers\toast;
use App\Enums\ToastTypesEnum;

use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): Response
    {
        $search = request('search');
        $rowsPerPage = request('rowsPerPage', 10);

        $users = $this->userService->getFilteredUsers($search, $rowsPerPage);
       
        return Inertia::render('Users', [
            'users' => $users,
            'searchContext' => request('searchContext', ''),
            'searchTerm' => request('searchTerm', ''),
            "user_info" => Auth::user()
        ]);
    
    }

     public function delete(Request $request) : RedirectResponse
    {
        $users = $this->userService->deleteUser($request->id);
        
        if($users){
            toast(ToastTypesEnum::Success, "Successfully Deleted!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Deleted!");
        }
        
        return to_route('page.users');
    }

    public function update(Request $request)
    {
        $users = $this->userService->updateUser($request->all());
        if($users){
            toast(ToastTypesEnum::Success, "Successfully Update!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Update!");
        }
        
        return to_route('page.users');
    }

    public function store(Request $request)
    {
        $users = $this->userService->storeUser($request->all());
        if($users){
            toast(ToastTypesEnum::Success, "Successfully Save!");
        }else{
            toast(ToastTypesEnum::Error, "Unsuccessfully Save!");
        }
        
        return to_route('page.users');
    }
}
