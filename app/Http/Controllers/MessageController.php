<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{

    public function index($userId)
    {
        
        $users = User::all();

        
        $selectedUser = User::find($userId);

        if (!$selectedUser) {
            return redirect()->route('home')->with('error', 'User not found');
        }

        
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('receiver_id', auth()->id())
                ->where('sender_id', $userId);
        })
        ->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $userId);
        })
        ->orderBy('created_at', 'asc') 
        ->get();

        return view('welcome', compact('users', 'selectedUser', 'messages')); 
    }

    public function sendMessage(Request $request)
    {
      
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        
        $message = new Message();
        $message->sender_id = auth()->id();
        $message->receiver_id = $request->user_id;
        $message->content = $request->message;

      
        $message->save();

        return redirect()->route('home', ['userId' => $request->user_id]);
    }

    public function fetchMessages($userId)
    {
        
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $userId);
        })
        ->orWhere(function ($query) use ($userId) {
            $query->where('receiver_id', auth()->id())
                ->where('sender_id', $userId);
        })
        ->where('read', false) 
        ->latest() 
        ->get();

        $messages->each(function ($message) {
            $message->read = true;
            $message->save();
        });

        return response()->json(['messages' => $messages]);
    }
}
