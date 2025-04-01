<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\GenericNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Notification;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Chat::create([
            'person_one' => 1,
            'person_two' => 2,
        ]);

        for ($i = 0; $i < 50; $i++) {
            $message = ChatMessage::create([
                'chat_id' => 1,
                'user_id' => rand(1,2),
                'message' => fake()->text(30)
            ]);
            $user = User::find($message->user_id);
            Notification::sendNow($user, new GenericNotification($message->message));
        }
    }
}
