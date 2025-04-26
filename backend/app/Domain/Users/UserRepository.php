<?php

namespace App\Domain\Users;

interface UserRepository
{
    public function create(User $user): void;

    public function update(User $user): void;

    public function findByUsername(string $username): ?User;

    public function findAll(): array;

    public function delete(int $id): void;

    public function userLogin($credentials);
}

// sayop na diha dapat sa eloquent ni

// namespace App\Domain\Entities;

// use Illuminate\Support\Facades\DB;
// use Exception;

// class UserRepository
// {
//     public function create(User $user): void
//     {
//         try {
//             DB::table('users')->insert([
//                 'username' => $user->getUsername(),
//                 'password' => bcrypt($user->getPassword()), // Hash the password for security
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ]);
//         } catch (Exception $e) {
//             throw new Exception('Failed to create user: ' . $e->getMessage());
//         }
//     }

//     public function findByUsername(string $username): ?User
//     {
//         try {
//             $user = DB::table('users')
//                 ->where('username', $username)
//                 ->first();

//             if (!$user) {
//                 return null;
//             }

//             return new User(
//                 $user->username,
//                 $user->password
//             );
//         } catch (Exception $e) {
//             throw new Exception('Failed to find user: ' . $e->getMessage());
//         }
//     }

//     public function findAll(): array
//     {
//         try {
//             $users = DB::table('users')->get();

//             return $users->map(function ($user) {
//                 return new User(
//                     $user->username,
//                     $user->password
//                 );
//             })->toArray();
//         } catch (Exception $e) {
//             throw new Exception('Failed to fetch users: ' . $e->getMessage());
//         }
//     }

//     public function update(string $username, string $newPassword): void
//     {
//         try {
//             DB::table('users')
//                 ->where('username', $username)
//                 ->update([
//                     'password' => bcrypt($newPassword),
//                     'updated_at' => now()
//                 ]);
//         } catch (Exception $e) {
//             throw new Exception('Failed to update user: ' . $e->getMessage());
//         }
//     }

//     public function delete(string $username): void
//     {
//         try {
//             DB::table('users')
//                 ->where('username', $username)
//                 ->delete();
//         } catch (Exception $e) {
//             throw new Exception('Failed to delete user: ' . $e->getMessage());
//         }
//     }
// }
