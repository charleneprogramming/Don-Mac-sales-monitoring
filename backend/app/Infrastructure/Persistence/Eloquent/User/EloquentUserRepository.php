<?php

namespace App\Infrastructure\Persistence\Eloquent\User;

use App\Domain\Users\User;
use App\Domain\Users\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

// use Exception;

class EloquentUserRepository implements UserRepository
{
    public function create(User $user): void
    {

        $data = new UserModel;
        $data->username = $user->getUsername();
        $data->password = $user->getPassword();
        $data->isAdmin = $user->getIsAdmin();
        $data->name = $user->getName();
        $data->contact_number = $user->getContactNumber();
        $data->save();
    }

    public function update(User $user): void
    {
        $data = UserModel::find($user->getID()) ?? new UserModel;
        $data->id = $user->getID();
        $data->username = $user->getUsername();
        $data->password = $user->getPassword();
        $data->name = $user->getName();
        $data->contact_number = $user->getContactNumber();
        $data->save();
    }

    public function delete(int $id): void
    {
        UserModel::where('id', $id)->delete();
    }

    public function findByUsername(string $username): ?User
    {
        $data = UserModel::where('username', $username)->first();
        if (! $data) {
            return null;
        }

        return new User(
            $data->id, 
            $data->username,
            $data->password,
            $data->isAdmin,
            $data->name,
            $data->contact_number);
    }

    public function findAll(): array
    {
        $users = UserModel::all();

        return $users->map(function ($user) {
            return new User(
                $user->id,
                $user->username,
                $user->password,
                $user->isAdmin,
                $user->name,
                $user->contact_number
            );
        })->toArray();
    }

    public function userLogin($credentials)
    {
        // dd($credentials);
        // if (Auth::attempt($credentials)) {
        //     return redirect('home')->with('message', 'Login Successful!');
        // }

        // return redirect('/')->with('message', 'Login Failed!');
        if (! Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials');
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'contact_number' => $user->contact_number,
                'isAdmin' => $user->isAdmin,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ];
    }
}
