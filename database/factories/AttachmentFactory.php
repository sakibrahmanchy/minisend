<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Mail;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->file('/'),
            'url' => $this->faker->imageUrl(),
            'size' => $this->faker->numberBetween(1900, 2016)." KB"
        ];
    }
}
