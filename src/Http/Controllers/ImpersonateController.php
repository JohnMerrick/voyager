<?php

namespace TCG\Voyager\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Models\User;

class ImpersonateController extends Controller
{

    public function impersonate(Request $request)
    {
        // Validate the request
        // $request->validate([
        //     'user_id' => 'required|exists:users,id',
        // ]);

        // Get the user to impersonate
        $user = User::findOrFail($request->query('user_id'));

        session()->put('impersonate', $user->id);

        return redirect('/home');
    }

    public function destroy()
    {
        // Remove the impersonation session
        session()->forget('impersonate');

        // Optionally, you can redirect to a specific route after stopping impersonation
        return redirect('/admin')->with('message', 'Impersonation stopped.');
    }
}
