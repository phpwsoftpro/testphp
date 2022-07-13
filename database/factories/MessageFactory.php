<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userSender = User::all()->random();
        $userRecived = User::whereNotIn('id',[$userSender->id])->get()->random();
        return [
            'message' => $this->faker->text,
            'sender_id' => $userSender->id,
            'received_id'   => $userRecived->id,
            'received_date' => $this->faker->dateTimeBetween('-5 years')
        ];
    }
}
