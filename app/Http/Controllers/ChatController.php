<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->withCount([
                'messagesReceived' => function ($query) {
                    $query->where('sender_id', '!=', Auth::id()) // මට ආපු
                        ->where('is_read', false);          // නොබලපු පණිවිඩ
                }
            ])
            ->get();

        // මෙහිදී messagesReceived_count ලෙස අගය ලැබෙනවා, අපි ඒක unread_count ලෙස පාවිච්චි කරමු
        $users->map(function ($user) {
            $user->unread_count = $user->messages_received_count;
            return $user;
        });

        return view('chat-app.pages.dashboard', compact('users'));
    }

    public function show($id)
    {
        $receiver = User::findOrFail($id);

        $messages = Message::where(function ($q) use ($id) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $id);
        })->orWhere(function ($q) use ($id) {
            $q->where('sender_id', $id)->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        Message::where('sender_id', $id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return view('chat-app.pages.chat-room', compact('receiver', 'messages'));
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required']);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $id,
            'message' => $request->message,
            'is_read' => false
        ]);

        broadcast(new MessageSent($message))->toOthers();

        // Meka thamayi JSON error eka fix karana kalla
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }

        return back();
    }
}
