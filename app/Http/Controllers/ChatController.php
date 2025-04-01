<?php

namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ChatController extends Controller
{
    public function getChats()
    {
        $chats = Chat::with(['person_one','person_two'])->where(function($query) {
            $query->where('person_one', auth()->user()->id)->orWhere('person_two', auth()->user()->id)->get();
        })->orderBy('updated_at','desc')->get();
        return response()->json($chats, 200);
    }

    public function getChat($id)
    {
        $chat = Chat::with(['person_one','person_two','messages.user'])->find($id);
        return response()->json($chat, 200);
    }

    public function registerMessage(Request $request)
    {
        $message = ChatMessage::create([
            'chat_id' => $request->chat_id,
            'user_id' => auth()->user()->id,
            'message' => $request->message,
        ]);
        $chat = Chat::find($request->chat_id);
        $chat->update([
            'updated_at' => now()
        ]);
        if ($chat->person_one == auth()->user()->id) {
            $user = User::find($chat->person_two);
            Notification::sendNow($user, new GenericNotification($message->message));
        } else {
            $user = User::find($chat->person_one);
            Notification::sendNow($user, new GenericNotification($message->message));
        }
    }

    public function getUsers()
    {
        $users = User::where('id','<>',auth()->user()->id)->get();
        return response()->json($users, 200);
    }

    public function getUserChat($id)
    {
        $chat = Chat::with(['person_one','person_two','messages.user'])->whereHas('person_one', function($query) use ($id) {
            $query->where('id', $id)->orWhere('id', auth()->user()->id);
        })->whereHas('person_two', function($query) use ($id) {
            $query->where('id', $id)->orWhere('id', auth()->user()->id);
        })->first();
        if (!$chat) {
            $chat = Chat::create([
                'person_one' => auth()->user()->id,
                'person_two' => $id,
            ]);
            ChatMessage::create([
                'chat_id' => $chat->id,
                'user_id' => auth()->user()->id,
                'message' => 'Hello',
            ]);
        }
        return response()->json($chat, 200);
    }
}
