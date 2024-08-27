<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\ResetPasswordEvent;
use App\Exceptions\Exception;
use App\Exceptions\UserNotActiveException;
use App\Exceptions\UserNotFoundException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendCodeRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\RefreshRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordByCodeRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Mails\RegisteredEventSender;
use App\Mails\ResetPasswordEventSender;

use App\Services\UserService;
use Dingo\Api\Http\Response;
use App\Http\Requests\Auth\SendCodeByPhoneRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Aloha\Twilio\Support\Laravel\Facade as Twilio;

class AuthController extends BaseController
{
    private UserService $userService;


    /**
     * Constructor of the class
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @api            {post} /auth/register Register User
     * @apiDescription Register User
     * @apiGroup       Auth
     * @apiPermission  none
     * @apiVersion     0.1.0
     *
     * @apiParam {String}   email       Email
     * @apiParam {String}   password    Password
     * @apiParam {String}   password_confirmation    Password Confirmation
     * @apiParamExample {json} Request-Example:
     *  {
     *      "email": "user@email.com",
     *      "password": "12345678",
     *      "password_confirmation": "12345678",
     *  }
     *
     * @apiSuccessExample {json} Success-Response:
     * HTTP/1.1 201 Created
     *  {
     *      "data": {
     *          "user_id": 75
     *      }
     *  }
     *
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 422 Unprocessable Entity
     *  {
     *      "message": "422 Unprocessable Content",
     *      "errors": {
     *          "email": [
     *              "The email field is required."
     *          ],
     *          "password": [
     *              "The password field is required."
     *          ],
     *          "password_confirmation": [
     *              "The password_confirmation field is required."
     *          ],
     *      },
     *      "status_code": 422
     *  }
     */
    public function register(RegisterRequest $request): Response
    {
        try {
            DB::beginTransaction();

            $user = $this->userService->register($request->validated());
            if (!empty($user->email)) {
                $sender = new RegisteredEventSender();
                $sender->to($user, null)
                    ->setUserRegisteredMessage()
                    ->send();
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->createdResponse(['user_id' => $user->id]);
    }

    /**
     * @api            {get} /auth/verify?email_verify_code=KfmF753oii2ebQdYO9UIxxgbA9MKI3Tu  Verify User Email By Code
     * @apiDescription Create JWT token
     * @apiGroup       Auth
     * @apiPermission  none
     * @apiVersion     0.1.0
     *
     * @apiParam {String}   email_verify_code    Email Verification Code
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 204 No Content
     *
     *  HTTP/1.1 404 Not Found
     *  {
     *      "message": "User is not found with provided code!",
     *      "status_code": 404
     *  }
     *
     */
    public function verify(VerifyRequest $request): Response
    {
        try {
            $this->userService->verifyByCode($request->get('email_verify_code'));
        } catch (UserNotFoundException|UserNotActiveException|\Exception $e) {
            $this->badRequestException($e->getMessage());
        }

        return $this->createdResponse(['status' => 'Email is verify']);
    }

    /**
     * @api            {post} /auth/login Create JWT token
     * @apiDescription Create JWT token
     * @apiGroup       Auth
     * @apiPermission  none
     * @apiVersion     0.1.0
     *
     * @apiParam {String}   email       mailbox
     * @apiParam {String}   password    password
     * @apiParamExample {json} Request-Example:
     *  {
     *      "email": "user@email.com",
     *      "password": "12345678",
     *  }
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "token":
     *          "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbHVtZW4tYXBpLWRlbW8uZGV1L2FwaS9hdXRob3JpemF0aW9ucyIsImlhdCI6MTQ4Mzk3NTY5MywiZXhwIjoxNDg5MTU5NjkzLCJuYmYiOjE0ODM5NzU2OTMsImp0aSI6ImViNzAwZDM1MGIxNzM5Y2E5ZjhhNDk4NGMzODcxMWZjIiwic3ViIjo1M30.hdny6T031vVmyWlmnd2aUr4IVM9rm2Wchxg5RX_SDpM",
     *          "expired_at": "2017-03-10 15:28:13",
     *          "refresh_expired_at": "2017-01-23 15:28:13"
     *      }
     *  }
     *
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 401 Unauthorized
     *  {
     *      "message": "Invalid credentials!",
     *      "status_code": 401
     *  }
     *
     *  HTTP/1.1 404 Not Found
     *  {
     *      "message": "User is not verified!",
     *      "status_code": 404
     *  }
     *
     *  HTTP/1.1 422 Unprocessable Entity
     *  {
     *      "message": "422 Unprocessable Content",
     *      "errors": {
     *          "email": [
     *              "The email field is required."
     *          ],
     *          "password": [
     *              "The password field is required."
     *          ],
     *      },
     *      "status_code": 422
     *  }
     */
    public function login(LoginRequest $request): Response
    {
        $token = null;
        try {
            $token = $this->userService->authenticateByArray($request);
        } catch (UserNotFoundException|UserNotActiveException|\Exception $e) {
            $this->badRequestException($e->getMessage());
        }

        return $this->successResponse($token->toArray());
    }


    /**
     * @api            {delete} /auth/logout Delete current token
     * @apiDescription Delete current token
     * @apiGroup       Auth
     * @apiPermission  jwt
     * @apiVersion     0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     */
    public function logout(): Response
    {
        auth()->logout();

        return $this->noContentResponse();
    }
}
