<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Mail\LoginDetailsMail;
use Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if the email already exists
        $existingUser = User::where('email', $row['email'])->first();

        // If the user with the same email exists, return null to skip creating a new user
        if ($existingUser) {
            return null;
        }

        // Generate a random password
        $password = Str::random(10);

        // Create the user
        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'is_active' => '1',
            'password' => $password,
        ]);

        $role = Role::where('name',$row['role'])->first();

        if($role)
        {
            $user->assignRole($role->name);
            $user->syncPermissions($role->permissions()->pluck('name')->toArray());
        }

        // Send email to the user with the random password
         Mail::to($user->email)->send(new LoginDetailsMail($user, $password));

         sleep(2);


        return $user;
    }
}
