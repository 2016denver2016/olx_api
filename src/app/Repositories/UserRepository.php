<?php

namespace App\Repositories;

use App\Exceptions\Exception;
use App\Jobs\PodJob;
use App\Models\FlowkLike;
use App\Models\FlowkTags;
use App\Models\PodFollowing;
use App\Models\User;
use App\Models\Flowk;
use App\Models\File;
use App\Models\DeviceSession;
use App\Models\UserBlocker;
use App\Models\UserDeleted;
use App\Models\UserFollower;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Aloha\Twilio\TwilioInterface;
use Aloha\Twilio\Manager;
use Aloha\Twilio\Support\Laravel\Facade as Twilio;

final class UserRepository extends AbstractRepository
{


    public function __construct()
    {
        $this->model = User::class;
    }


    public function create(array $data): User
    {
        $user = User::create([
            'email'                   => $data['email'] ?? '',
            'email_verification_code' => $data['email_verification_code'] ?? null,
            'password'                => Hash::make($data['password']),
            'status'                  => User::STATUS_WAITING_APPROVAL,
        ]);

        return $user;
    }



    /**
     * Finds a user by the given email
     *
     * @param string $email
     *
     * @return null|User
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', '=', $email)->first();
    }

    public function findByNickName(string $nickname): ?User
    {
        return User::where('nickname', '=', $nickname)->first();
    }

    public function findByPhone(string $nickname): ?User
    {
        return User::where('phone', '=', $nickname)->first();
    }

    public function findByVerificationCode(string $code): ?User
    {
        return User::where(['email_verification_code'=> $code])->first();
    }

    public function findByPasswordRecoveryToken(string $accessToken): ?User
    {
        return User::where('password_recovery_token', '=', $accessToken)->first();
    }

    public function findByPhoneRecoveryCode(string $code): ?User
    {
        return User::where(['password_recovery_token' => $code])->first();
    }


}
