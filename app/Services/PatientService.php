<?php

namespace App\Services;
use App\Models\Patient;

class PatientService
{
    public function getFilteredPatients(string $search = null, int $rowsPerPage = 10)
    {
        return Patient::query()
            ->orderBy('lastname', 'ASC')
            ->when($search, function ($query, $search) {
                $query->where('firstname', 'like', "%{$search}%")
                    ->orWhere('middlename', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('patient_no', 'like', "%{$search}%");
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
                'patient_no' => $physician->patient_no,
                'gender' => $physician->gender,
                'created_at' => $physician->created_at->format('m/d/Y'),
            ]);
    }
    
    function deletePatient($id)
    {
        return Patient::where('id',$id)->delete();
    }

    function updatePatient($data)
    {
       
        $patient =  Patient::find($data['id']);

        if (isset($data['firstname']) && strlen($data['firstname'])) {
            $patient->firstname = $data['firstname'] ?? null;
        }

        if (isset($data['middlename']) && strlen($data['middlename'])) {
            $patient->middlename = $data['middlename'] ?? null;
        }

        if (isset($data['lastname']) && strlen($data['lastname'])) {
            $patient->lastname = $data['lastname'] ?? null;
        }

        if (isset($data['suffix']) && strlen($data['suffix'])) {
            $patient->suffix = $data['suffix'] ?? null;
        }

     
        if (isset($data['patient_no']) && strlen($data['patient_no'])) {
            $patient->patient_no = $data['patient_no'] ?? null;
        }

        if (isset($data['gender']) && strlen($data['gender'])) {
            $patient->patient_no = $data['gender'] ?? null;
        }

         
         $patient->full_name =  $data['firstname'].' '.$data['middlename'] .' '.$data['lastname'] .' '.$data['suffix'] ;
        

        if($patient->save()){
            return true;
        }else{
            return false;
        }

    }

    function storePatient($data)
    {
       
        $patient = new Patient();

         if (isset($data['firstname']) && strlen($data['firstname'])) {
            $patient->firstname = $data['firstname'] ?? null;
        }

        if (isset($data['middlename']) && strlen($data['middlename'])) {
            $patient->middlename = $data['middlename'] ?? null;
        }

        if (isset($data['lastname']) && strlen($data['lastname'])) {
            $patient->lastname = $data['lastname'] ?? null;
        }

        if (isset($data['suffix']) && strlen($data['suffix'])) {
            $patient->suffix = $data['suffix'] ?? null;
        }

     
        if (isset($data['patient_no']) && strlen($data['patient_no'])) {
            $patient->patient_no = $data['patient_no'] ?? null;
        }

        if (isset($data['gender']) && strlen($data['gender'])) {
            $patient->patient_no = $data['gender'] ?? null;
        }

         
        $patient->full_name =  $data['firstname'].' '.$data['middlename'] .' '.$data['lastname'] .' '.$data['suffix'] ;
        

        if($patient->save()){
            return true;
        }else{
            return false;
        }

    }
}