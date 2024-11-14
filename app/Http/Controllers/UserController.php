<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
      
        if (auth()->check()) {
         
            $users = User::where('id', '!=', auth()->id())->get();

        
            $selectedUser = User::find($request->userId) ?? $users->first();

            
            $messages = Message::where(function($query) use ($selectedUser) {
                                $query->where('receiver_id', auth()->id())
                                      ->where('sender_id', $selectedUser->id);
                            })
                            ->orWhere(function($query) use ($selectedUser) {
                                $query->where('sender_id', auth()->id())
                                      ->where('receiver_id', $selectedUser->id);
                            })
                            ->get();

            
            return view('welcome', compact('users', 'messages', 'selectedUser'));
        }

        
        return view('welcome', ['users' => [], 'messages' => [], 'selectedUser' => null]);
    }
}
