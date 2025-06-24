<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class AdminUserController extends Controller
{
    // List all users (paginated)
    public function index()
{
    $users = User::select('id', 'name', 'email', 'role', 'created_at')
                 ->orderBy('id', 'desc')
                 ->paginate(20);

    return UserResource::collection($users)->additional([
        'meta' => [
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
        ]
    ]);
}


    // View specific user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    // Create new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,manager,user'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => new UserResource($user)
        ]);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'role' => 'nullable|in:admin,manager,user'
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($user)
        ]);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    // Search user
    public function search(Request $request)
{
    $q = $request->query('q');

    $users = User::where('name', 'like', "%$q%")
                 ->orWhere('email', 'like', "%$q%")
                 ->orWhere('role', 'like', "%$q%")
                 ->select('id', 'name', 'email', 'role', 'created_at')
                 ->paginate(20);

    return UserResource::collection($users)->additional([
        'meta' => [
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
        ]
    ]);
}

}
