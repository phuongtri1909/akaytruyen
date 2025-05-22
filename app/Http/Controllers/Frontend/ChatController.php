<?php

namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Lấy tất cả tin nhắn mới nhất
    public function index()
    {
        $messages = Message::with('user')->latest()->take(50)->get()->reverse(); // Lấy 50 tin nhắn mới nhất
        return response()->json($messages);
    }

    // Gửi tin nhắn
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return response()->json(['message' => 'Sent', 'data' => $message]);
    }
}
