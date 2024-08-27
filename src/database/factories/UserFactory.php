<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $createdAt;
        static $password;

        $firstName = $this->faker->firstName;
        $lastName  = $this->faker->lastName;

        return [
            'email'      => $this->faker->email,
            'status'     => array_rand(User::getStatuses(), 1),
            'password'   => $password ?: $password = app('hash')->make(123456),
            'created_at' => $createdAt ?: $createdAt = Carbon::now(),
            'updated_at' => $createdAt ?: $createdAt = Carbon::now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
