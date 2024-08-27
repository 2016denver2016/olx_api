<?php

use App\Models\User;
use Illuminate\Http\Response;

class AuthApiTest extends TestCase
{
    public function testSuccessLogin()
    {
        $this->post(route('/login'), User::factory()->create()->toArray());
        $this->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testFailureLogin()
    {

    }

}
