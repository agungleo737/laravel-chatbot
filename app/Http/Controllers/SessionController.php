<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatSession;

class SessionController extends Controller
{
    //daftar sesi
    public function index()
    {
          $sessions = ChatSession::orderBy('created_at', 'desc')->get();
          return response()->json($sessions);
    }
    //buat sesi
    public function store(Request $request)
    {
          $session = ChatSession::create([
               'title' => 'New Chat'
          ]);
          return response()->json($session,201);
    }
    //tampil sesi
    public function show($id)
    {
        $session = ChatSession::with('messages')->findOrFail($id);
        return response()->json($session);
    }

    //hapus sesi
    public function destroy($id)
    {
        $session = ChatSession::findOrFail($id);
        $session->delete();
        return response()->json(['message' => 'Sesi dihapus']);
    }
}
