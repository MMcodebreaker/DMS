<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getFilteredUsers(string $search = null, int $rowsPerPage = 10)
    {
        return User::query()
            ->orderBy('name', 'ASC')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate($rowsPerPage)
            ->withQueryString()
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at->format('m/d/Y'),
            ]);
    }
    
    function deleteUser($id)
    {
        return User::where('id',$id)->delete();
    }

    function updateUser($data)
    {
       
        $user =  User::find($data['id']);

        if (isset($data['name']) && strlen($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email']) && strlen($data['email'])) {
            $user->email = $data['email'];
        }

        if (isset($data['password']) && strlen($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if (isset($data['role']) && strlen($data['role'])) {
            $user->role = $data['role'];
        }

        if($user->save()){
            return true;
        }else{
            return false;
        }

    }

    function storeUser($data)
    {
       
        $user = new User();

        if (isset($data['name']) && strlen($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email']) && strlen($data['email'])) {
            $user->email = $data['email'];
        }

        if (isset($data['password']) && strlen($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if (isset($data['role']) && strlen($data['role'])) {
            $user->role = $data['role'];
        }

        if($user->save()){
            return true;
        }else{
            return false;
        }

    }
}