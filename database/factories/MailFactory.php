<?php

namespace Database\Factories;

use App\Models\Mail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'from' => $this->faker->email,
            'to' => $this->faker->email,
            'subject' => $this->faker->sentence ,
            'content' => $this->faker->paragraph,
            'created_by' => User::all()->random()->id,
            'status' => rand(0, 2)."",
        ];
    }
}
