<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());

    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $request->user()->id,
        ]);

        $request->user()->update($request->only('name', 'email'));

        return response()->json(['message' => 'Profile updated', 'user' => $request->user()]);
    }
}
