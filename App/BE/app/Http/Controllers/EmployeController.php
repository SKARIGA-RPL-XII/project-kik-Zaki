<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->query('search');
        $page = max(1, (int) $request->query('page', 1));
        $size = (int) $request->query('size', 10);

        $data = Employe::when($search, function ($query) use ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%");
            });
        })
            ->latest()
            ->skip(($page - 1) * $size)
            ->take($size)
            ->get();



        return Controller::OKE('success', 'success get data', ["employes" => $data, "metadata" => [
            "page" => $page,
            "size" => $size,
            "total" => $data->count()
        ]], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "addres" => "required|string",
            "no_tlp" => "required",
            "profile_image" => "required|file|max:2040|mimes:png,jpg,jpeg,webp",
            "identity_card" =>  "required|file|max:2040|mimes:png,jpg,jpeg,webp",
            "gender" => "required|in:LK,PR",
            "email" => "required",
            "password" => "required|min:6|confirmed",
            "username" => "required|min:3|string",
        ]);

        $PATH_PROFILE_IMAGE = $request->file('profile_image')->store('profile_images', 'public');
        $PATH_IDENTITY_CARD = $request->file('identity_card')->store('identity_card', 'public');

        $user = User::create([
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "username" => $request->username,
            "role_id" => 2
        ]);

        Employe::create([
            "addres" => $request->addres,
            "no_tlp" => (int) $request->no_tlp,
            "profile_image" => $PATH_PROFILE_IMAGE,
            "identity_card" =>  $PATH_IDENTITY_CARD,
            "gender" => $request->gender,
            "user_id" => $user->id
        ]);

        return Controller::OKE('success', 'success create data', [], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employe $employe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employe $employe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employe $employe)
    {
        $request->validate([
            "addres" => "sometimes|string",
            "no_tlp" => "sometimes",
            "profile_image" => "sometimes|file|max:2040|mimes:png,jpg,jpeg,webp",
            "identity_card" => "sometimes|file|max:2040|mimes:png,jpg,jpeg,webp",
            "gender" => "sometimes|in:LK,PR",
        ]);

        $data = $request->only(['addres', 'no_tlp', 'gender']);

        if ($request->hasFile('profile_image')) {
            Storage::disk('public')->delete($employe->profile_image);
            $data['profile_image'] = $request->file('profile_image')
                ->store('profile_images', 'public');
        }

        if ($request->hasFile('identity_card')) {
            Storage::disk('public')->delete($employe->identity_card);
            $data['identity_card'] = $request->file('identity_card')
                ->store('identity_card', 'public');
        }

        $employe->update($data);

        return Controller::OKE('success', 'success updated data', $employe, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employe $employe)
    {
        $employe->delete();
        return Controller::OKE('success', 'succes delete', [], 200);
    }
}
