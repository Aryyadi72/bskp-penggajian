<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SomeController extends Controller
{
    public function someProtectedMethod(Request $request)
    {
        // Mendapatkan pengguna dari request
        $user = $request->attributes->get('user');

        // Sekarang Anda bisa menggunakan data pengguna untuk logika aplikasi
        return response()->json(['message' => 'Welcome, ' . $user->name]);
    }
}
