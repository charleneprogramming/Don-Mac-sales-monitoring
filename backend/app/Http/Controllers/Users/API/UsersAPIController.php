<?php

namespace App\Http\Controllers\Users\API;

use App\Application\User\RegisterUser;
use App\Domain\Users\User;
use App\Http\Controllers\Controller;
use App\Infrastructure\Persistence\Eloquent\User\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersAPIController extends Controller
{
    private RegisterUser $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    public function findAll()
    {
        $users = UserModel::all(['id', 'username',  'name', 'contact_number', 'created_at', 'updated_at']);

        return response()->json([
            'users' => $users,
            'message' => 'Users retrieved successfully',
        ]);
    }

    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:6',
                'name' => 'required|string|max:255',
                'contact_number' => 'nullable|string|max:20',
                'user_type' => 'required|string',
            ]);

            $isAdmin = $validatedData['user_type'] == '1' ? true : false;

            $this->registerUser->create(
                $validatedData['username'],
                Hash::make($validatedData['password']),
                $isAdmin,
                $validatedData['name'],
                $validatedData['contact_number']
            );

            return response()->json([
                'message' => 'User created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string|unique:users,username,'.$id,
                'password' => 'required|string|min:6',
            ]);

            $existingUser = UserModel::find($id);
            if (! $existingUser) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user = new User(
                (int) $id,
                $validatedData['username'],
                Hash::make($validatedData['password'])
            );

            $this->registerUser->update($user);

            return response()->json([
                'message' => 'User updated successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $existingUser = UserModel::find($id);
            if (! $existingUser) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $this->registerUser->delete($id);

            return response()->json([
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function findByUsername($username)
    {
        try {
            $user = UserModel::where('username', $username)->first();

            if (! $user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'contact_number' => $user->contact_number,
                ],
                'message' => 'User found successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error finding user',
            ], 500);
        }
    }
}
