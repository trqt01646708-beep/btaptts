<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        Log::info('Chat index accessed by user: ' . Auth::id());
        $users = User::where('id', '!=', Auth::id())->get();
        return view('chat', compact('users'));
    }

    public function fetchMessages($receiverId)
    {
        Log::info('Fetching messages between ' . Auth::id() . ' and ' . $receiverId);
        return Message::where(function($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiverId);
        })->orWhere(function($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', Auth::id());
        })
        ->with('sender', 'receiver')
        ->orderBy('created_at', 'asc')
        ->get();
    }

    public function sendMessage(Request $request)
    {
        Log::info('Sending message from ' . Auth::id() . ' to ' . $request->receiver_id);
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        Log::info('Message saved to DB. Broadcasting event...');
        
        broadcast(new MessageSent($message->load('sender')));

        return ['status' => 'Message Sent!', 'message' => $message];
    }
}
