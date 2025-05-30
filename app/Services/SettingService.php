<?php

namespace App\Services;
use App\Models\Setting;

class SettingService
{
    public function getFilteredSettings(string $search = null, int $rowsPerPage = 10)
    {
        return Setting::query()
            ->orderBy('name', 'ASC')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate($rowsPerPage)
            ->withQueryString()
            ->through(fn ($physician) => [
                'id' => $physician->id,
                'name' => $physician->name,
                'value' => $physician->value,
                'status' => $physician->status,
                'created_at' => $physician->created_at->format('m/d/Y'),
            ]);
    }
    
    function deleteSetting($id)
    {
        return Setting::where('id',$id)->delete();
    }

    function updateSetting($data)
    {
       
        $setting =  Setting::find($data['id']);

        if (isset($data['name']) && strlen($data['name'])) {
            $setting->name = $data['name'] ?? null;
        }

        if (isset($data['value']) && strlen($data['value'])) {
            $setting->value = $data['value'] ?? null;
        }

        if (isset($data['status']) && strlen($data['status'])) {
            $setting->status = $data['status'] ?? null;
        }

        if($setting->save()){
            return true;
        }else{
            return false;
        }

    }

    function storeSetting($data)
    {
       
        $setting = new Setting();

         if (isset($data['name']) && strlen($data['name'])) {
            $setting->name = $data['name'] ?? null;
        }

        if (isset($data['value']) && strlen($data['value'])) {
            $setting->value = $data['value'] ?? null;
        }

        if (isset($data['status']) && strlen($data['status'])) {
            $setting->status = $data['status'] ?? null;
        }

        if($setting->save()){
            return true;
        }else{
            return false;
        }

    }

    function getSettings()
    {
        return Setting::all(); 
    }
}