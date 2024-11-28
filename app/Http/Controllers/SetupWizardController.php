<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SetupWizardController extends Controller
{
    public function show()
    {
       
        // Check if an admin already exists to prevent access to the wizard
        if (User::where('role', 'administrator')->exists()) {
            return redirect('/'); // Redirect to home if admin exists
        }
        return view('install.setup-wizard'); // Show the setup form
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the admin user
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'administrator',
        ]);

        // Log in the admin user
        //Auth::login($admin);
        //return redirect(route('dashboard')); // Redirect to the dashboard after setup

        return redirect(route('login')); // Redirect to the dashboard after setup
    }
}
