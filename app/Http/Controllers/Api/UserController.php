<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Lista todos los usuarios (solo admin)
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Muestra un usuario específico
     */
    public function show($id)
    {
        $user = User::select('id', 'name', 'email', 'role', 'created_at', 'updated_at')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Actualiza el rol de un usuario
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:customer,vendor,admin',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado correctamente',
            'data' => $user,
        ]);
    }

    /**
     * Suspende un usuario
     */
    public function suspend($id)
    {
        $user = User::findOrFail($id);
        $user->is_suspended = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario suspendido correctamente',
        ]);
    }

    /**
     * Reactiva un usuario suspendido
     */
    public function unsuspend($id)
    {
        $user = User::findOrFail($id);
        $user->is_suspended = false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario reactivado correctamente',
        ]);
    }
}