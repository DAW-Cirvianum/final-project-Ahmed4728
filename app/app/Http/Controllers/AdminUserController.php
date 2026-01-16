<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();

        return redirect('/admin/users');
    }
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        return redirect('/admin/users');
    }
}
