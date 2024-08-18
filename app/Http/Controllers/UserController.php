<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'permissions' => '{"platform.systems.attachment":"0","platform.systems.roles":"0","platform.systems.users":"0","platform.systems.category.manage":"0","platform.alerts.create":"0","platform.alerts.delete":"0","platform.alerts.edit":"0","platform.alerts.view":"1","platform.index":"1"}',
            'password' => Hash::make($validatedData['password']),
        ]);



        // Redirect or respond as needed
        return redirect()->route('login')->with('success', 'User registered successfully!');
    }
}
