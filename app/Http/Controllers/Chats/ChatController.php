<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    public function sendMessage(Request $request){

        $request->validate([
            'recipient'=>'required',
            'body'=>'required'
        ]);
        $recipient = $request->recipient;
        $user = auth()->user();

        //chek if there is an existing chat
        $chat = $user->getChatWithUser($recipient);

        if(!$chat){
            //create new chat
            $chat = new Chat();
            $chat->save();

            //create participants
            $chat->participants()->sync([$user->id,$recipient]);
        }

        //add message to chat
        $message = Message::create([
            'user_id'=>$user->id,
            'chat_id'=>$chat->id,
            'body' => $request->body,
        ]);

        return new MessageResource($message);

    }

    public function getUsersChats(){
        $chats = auth()->user()->chats()->get();
        return ChatResource::collection($chats->loadMissing('messages','participants'));
    }

    public function getChatMessages($chatId){
        $messages = Message::where('chat_id',$chatId)->withTrashed()->get();
        return MessageResource::collection($messages);
    }

    public function markAsRead($chatId){
        $chat = Chat::where('id',$chatId)->first();
        $chat->markAsReadForUser(auth()->id());
        return response()->json(['message'=>'successful'],200);
    }

    public function destroyMessage($id){
        $message = Message::find($id);
        $this->authorize('delete',$message);
        $message->delete();
    }
}
