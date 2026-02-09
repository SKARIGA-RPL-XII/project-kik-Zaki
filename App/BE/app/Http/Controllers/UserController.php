<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            "email" => "sometimes|required|email|unique:users,email," . $id,
            "password" => "required|sometimes",
            "no_tlp" => "required|sometimes|integer|unique:users,no_tlp," . $id,
            "addres" => "required|sometimes|string",
            "gender" => "required|sometimes|in:LK,PR",
            "profile_image" => "required|sometime|file|mimes:png,jpg,webp,jpeg|max:2040",
            "username" => "required|sometimes|string",
        ]);

        $user = User::findOrFail($id);

        if (!$user) {
            return Controller::ERROR('error', 'user not found', 404);
        }

        if ($request->password) {
            $user->update([
                "password" => Hash::make($request->password)
            ]);
        }

        if ($request->hasFile('image_profile')) {
            Storage::delete($request->profile_image);
            $path = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->update([
            "email" => $request->email,
            "password" => $request->password,
            "no_tlp" => $request->no_tlp,
            "addres" => $request->addres,
            "gender" => $request->gender,
            "profile_image" => $path,
            "username" => $request->username,
        ]);

        return Controller::OKE('success' , 'success update' , $user , 200);
    }
}
