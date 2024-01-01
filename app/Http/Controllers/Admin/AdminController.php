<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{


    function dashboard(Request $request)
    {
        $query = Feedback::query();
        if ($request->category != "") {
            $query->where("category", $request->category);
        }
        $feedback = $query->with("users", "votes", "comment")->latest('id')->paginate(10);
        return view('admin.dashboard', compact("feedback"));
    }

    function login()
    {
        return view("admin.login");
    }

    function login_request(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required",
        ]);

        if (!Auth::guard('admin')->attempt(["email" => $request->email, "password" => $request->password])) {
            return redirect()->back()->with("error", "Login details incorrect");
        } else {
            return redirect()->route('admin.dashboard');
        }
    }

    function logout(Request $request)
    {

        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
