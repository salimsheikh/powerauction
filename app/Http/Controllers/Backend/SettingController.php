<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index(){
        $terms_condition = Setting::where('option_name', 'terms_condition')->value('option_value');
        $privacy_policy = Setting::where('option_name', 'privacy_policy')->value('option_value');
        return view('backend.settings.index', compact('terms_condition','privacy_policy'));        
    }

    public function update(Request $request)
    {   
        $rules = [
            'settings.terms_condition' => 'required|string',
            'settings.privacy_policy' => 'required|string',
        ];

        $message = [
            'settings.terms_condition.required' => 'Terms and Conditions field is required.',
            'settings.privacy_policy.required' => 'Privacy Policy field is required.',
            'settings.terms_condition.string' => 'Terms and Conditions must be a valid string.',
            'settings.privacy_policy.string' => 'Privacy Policy must be a valid string.',
        ];
        // Validate the request to ensure "settings" array exists
        $validated = $request->validate($rules, $message);        

        // Loop through the settings array
        foreach ($validated['settings'] as $key => $value) {

            \Log::info($key);
            // Update or Create the setting for each key-value pair
            Setting::updateOrCreate(
                ['option_name' => $key],    // Search by option_name
                ['option_value' => $value] // Update or insert option_value
            );
        }

        // return response()->json(['message' => 'Settings updated successfully!'], 200);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
