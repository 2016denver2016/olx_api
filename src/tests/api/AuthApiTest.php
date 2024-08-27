<?php

use App\Models\User;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class AuthApiTest extends TestCase
{
    public function testSuccessRegister()
    {
        $baseUrl = env('APP_URL') . '/v1/auth/register';

        $response = $this->json('POST', $baseUrl . '/', [
            'email' => 'shishkalovd@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);
        return $this->assertResponseStatus(201);
    }


    public function testSuccessVerifyEmail()
    {
        $baseUrl = env('APP_URL') . '/v1/auth/verify';
        $user = User::where('email', 'shishkalovd@gmail.com')->first();

        $this->json('GET', $baseUrl . '?email_verify_code='. $user->email_verification_code);
        return $this->assertResponseStatus(201);
    }

    public function testSuccessLogin()
    {
        $baseUrl = env('APP_URL') . '/v1/auth/login';
        $this->json('POST', $baseUrl . '/', [
            'email' => 'shishkalovd@gmail.com',
            'password' => '12345678',
        ]);
        return $this->assertResponseStatus(200);
    }

    public function testFailureLogin()
    {
        $baseUrl = env('APP_URL') . '/v1/auth/login';

        $response = $this->json('POST', $baseUrl . '/', [
            'email' => 'shishkalovd12@gmail.com',
            'password' => '12345678'
        ]);
        return $this->assertResponseStatus(400);
    }

    public function testFailureRegister()
    {
        $baseUrl = env('APP_URL') . '/v1/auth/register';

        $response = $this->json('POST', $baseUrl . '/', [
            'email' => 'shishkalovd@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);
        return $this->assertResponseStatus(422);
    }

}
