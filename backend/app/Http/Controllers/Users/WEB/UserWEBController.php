<?php

namespace App\Http\Controllers\Users\WEB;

use App\Application\User\RegisterUser;
use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\User\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserWEBController extends Controller
{
    private RegisterUser $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    public function index()
    {
        $users = UserModel::all();

        return view('Pages.CreateUser.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'user_type' => 'required|string',
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20', 
        ]);

        if ($request->user_type == '1') {
            $isAdmin = true;
        } else {
            $isAdmin = false;
        }

        try {
            $this->registerUser->create(
                $validated['username'],
                Hash::make($validated['password']),
                $isAdmin,
                $validated['name'],
                $validated['contact_number'],
            );

            return redirect()->back()->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating user: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->registerUser->delete($id);

            return redirect()->back()->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting user: '.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,'.$id,
            'password' => 'nullable|string|min:6',
        ]);

        try {
            $user = UserModel::findOrFail($id);
            $user->username = $validated['username'];
            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            return redirect()->back()->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating user: '.$e->getMessage())
                ->withInput();
        }
    }
}