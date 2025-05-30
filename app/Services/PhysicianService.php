<?php

namespace App\Services;

use App\Models\Physician;

class PhysicianService
{
    public function getFilteredPhysicians(string $search = null, int $rowsPerPage = 10)
    {
        return Physician::query()
            ->orderBy('lastname', 'ASC')
            ->when($search, function ($query, $search) {
                $query->where('firstname', 'like', "%{$search}%")
                    ->orWhere('middlename', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('prc_no', 'like', "%{$search}%");
            })
            ->paginate($rowsPerPage)
            ->withQueryString()
            ->through(fn ($physician) => [
                'id' => $physician->id,
                'full_name' => $physician->full_name,
                'firstname' => $physician->firstname,
                'middlename' => $physician->middlename,
                'lastname' => $physician->lastname,
                'suffix' => $physician->suffix,
                'specialization' => $physician->specialization,
                'prc_no' => $physician->prc_no,
                'created_at' => $physician->created_at->format('m/d/Y'),
            ]);
    }
    
    function deletePhysician($id)
    {
        return Physician::where('id',$id)->delete();
    }

    function updatePhysician($data)
    {
       
        $physician =  Physician::find($data['id']);

        if (isset($data['firstname']) && strlen($data['firstname'])) {
            $physician->firstname = $data['firstname'] ?? null;
        }

        if (isset($data['middlename']) && strlen($data['middlename'])) {
            $physician->middlename = $data['middlename'] ?? null;
        }

        if (isset($data['lastname']) && strlen($data['lastname'])) {
            $physician->lastname = $data['lastname'] ?? null;
        }

        if (isset($data['suffix']) && strlen($data['suffix'])) {
            $physician->suffix = $data['suffix'] ?? null;
        }

        if (isset($data['specialization']) && strlen($data['specialization'])) {
            $physician->specialization = $data['specialization'] ?? null;
        }

        if (isset($data['prc_no']) && strlen($data['prc_no'])) {
            $physician->prc_no = $data['prc_no'] ?? null;
        }

         
         $physician->full_name =  $data['firstname'].' '.$data['middlename'] .' '.$data['lastname'] .' '.$data['suffix'] ;
        

        if($physician->save()){
            return true;
        }else{
            return false;
        }

    }

    function storePhysician($data)
    {
       
        $physician = new Physician();

        if (isset($data['firstname']) && strlen($data['firstname'])) {
            $physician->firstname = $data['firstname'] ?? null;
        }

        if (isset($data['middlename']) && strlen($data['middlename'])) {
            $physician->middlename = $data['middlename'] ?? null;
        }

        if (isset($data['lastname']) && strlen($data['lastname'])) {
            $physician->lastname = $data['lastname'] ?? null;
        }

        if (isset($data['suffix']) && strlen($data['suffix'])) {
            $physician->suffix = $data['suffix'] ?? null;
        }

        if (isset($data['specialization']) && strlen($data['specialization'])) {
            $physician->specialization = $data['specialization'] ?? null;
        }

        if (isset($data['prc_no']) && strlen($data['prc_no'])) {
            $physician->prc_no = $data['prc_no'] ?? null;
        }

         
         $physician->full_name =  $data['firstname'].' '.$data['middlename'] .' '.$data['lastname'] .' '.$data['suffix'] ;
        

        if($physician->save()){
            return true;
        }else{
            return false;
        }

    }
}