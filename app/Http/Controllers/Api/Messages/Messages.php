<?php

namespace App\Http\Controllers\Api\Messages;


use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Models\Messages as Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Messages extends Controller
{
    public function get()
    {
        $id = Auth::id();
        $user = User::find($id);
        $friends = $user->friends();
        return response()->json(['friends' => $friends]);

    }

    public function getMessagesFor($id)
    {
//        $id = auth()->id();
        // mark all messages with the selected contact as read
        Message::where('from', $id)->where('to', auth()->id())->update(['read' => true]);

        // get all messages between the authenticated user and the selected user
        $messages = Message::where(function($q) use ($id) {
            $q->where('from', auth()->id());
            $q->where('to', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('from', $id);
            $q->where('to', auth()->id());
        })
            ->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'from' => auth()->id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);

        broadcast(new NewMessage($message));

        return response()->json($message);
    }
}
