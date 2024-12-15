<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\{RedirectResponse,Request};
use Illuminate\Support\Facades\{Auth,Redirect};
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $id = Auth::id();
        // Find the user to delete
        $user = User::findOrFail($id);

        // Check if the user is an admin
        if ($user->role == 'administrator') {
            return redirect()->route('profile.edit')->with('error', 'Cannot delete admin user.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();       

        try {
            $user->delete();
        } catch (\Throwable $th) {
            //\Log::error('User delete error: ' . $th->getMessage());
            return redirect()->route('profile.edit')->with('error', $th->getMessage());
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function create_token(Request $request){

        // Get the authenticated user
        $auth = Auth::user();

        if($auth){
            // Generate a Sanctum token for the authenticated user
            $token = $auth->createToken('API-Token')->plainTextToken;

            // Return the token in the response
            return response()->json(['token' => $token], 200);
        }

        // If authentication fails
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
