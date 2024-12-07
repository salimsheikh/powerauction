<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index(){
        $data['terms_condition'] = Setting::where('option_name', 'terms_condition')->value('option_value');
        $data['privacy_policy'] = Setting::where('option_name', 'privacy_policy')->value('option_value');
        $data['auction_expire_minutes'] = Setting::where('option_name', 'auction_expire_minutes')->value('option_value');

        $data['auction_expire_minutes'] = $data['auction_expire_minutes'] == "" ? 2 : $data['auction_expire_minutes'];

        
        
        return view('admin.settings', $data);        
    }

    public function update(Request $request)
    {   
        $rules = [
            'settings.terms_condition' => 'required|string',
            'settings.privacy_policy' => 'required|string',
            'settings.auction_expire_minutes' => 'required|integer',
        ];

        $message = [
            'settings.terms_condition.required' => 'Terms and Conditions field is required.',
            'settings.privacy_policy.required' => 'Privacy Policy field is required.',
            'settings.terms_condition.string' => 'Terms and Conditions must be a valid string.',
            'settings.privacy_policy.string' => 'Privacy Policy must be a valid string.',

            'settings.auction_expire_minutes.required' => 'Auction expire minutes is required fields.',
            'settings.auction_expire_minutes.integer' => 'Acution expire minutes is intiger value.',
        ];
        // Validate the request to ensure "settings" array exists
        $validated = $request->validate($rules, $message);        

        // Loop through the settings array
        foreach ($validated['settings'] as $key => $value) {

            // Update or Create the setting for each key-value pair
            Setting::updateOrCreate(
                ['option_name' => $key],    // Search by option_name
                ['option_value' => $value] // Update or insert option_value
            );
        }        

        // Setting::updateOrCreate(['option_name' => $key], ['option_value' => $value]);
        Cache::forget("setting_auction_expire_minutes");

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
