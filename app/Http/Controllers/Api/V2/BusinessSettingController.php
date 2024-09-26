<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\BusinessSettingCollection;
use App\Models\BusinessSetting;

class BusinessSettingController extends Controller
{
    public function index()
    {
        return new BusinessSettingCollection(BusinessSetting::all());
    }
    
    public function colorCodes()
    {
        $data = array(
            [
            "type"  => "primary_first_color",
            "value" => BusinessSetting::where('type','base_color')->first()->value
            ],[
            "type"  => "primary_second_color",
            "value" => BusinessSetting::where('type','base_color')->first()->value
            ],[
            "type"  => "hover_primary_first_color",
            "value" => BusinessSetting::where('type','base_hov_color')->first()->value
            ],[
            "type"  => "hover_primary_second_color",
            "value" => BusinessSetting::where('type','base_hov_color')->first()->value
            ],
        );
        return response()->json(["data" => $data, "success" => true, "status"   => 200]);
    }
}
