<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Tymon\JWTAuth\Payload;

class Authorization
{
    /** @var string $token */
    protected string $token;


    /** @var Payload $payload */
    protected $payload;

    public function __construct(string $token = null)
    {
        $this->setToken($token);

//        $this->setSocketToken($socketToken);
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }


    /**
     * @throws Exception
     */
    public function getToken(): string
    {
        if (!$this->token) {
            throw new Exception('Please set token');
        }

        return $this->token;
    }


    /**
     * @return mixed
     * @throws Exception
     */
    public function getPayload(): Payload
    {
        if (!$this->payload) {
            $this->payload = \Auth::setToken($this->getToken())->getPayload();
        }

        return $this->payload;
    }

    /**
     * @throws Exception
     */
    public function getExpiredAt(): string
    {
        return Carbon::createFromTimestamp($this->getPayload()->get('exp'))->toDateTimeString();
    }

    /**
     * @throws Exception
     */
    public function getRefreshExpiredAt(): string
    {
        return Carbon::createFromTimestamp($this->getPayload()->get('iat'))->addMinutes(config('jwt.refresh_ttl'))
            ->toDateTimeString();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function user()
    {
        return \Auth::authenticate($this->getToken());
    }

    /**
     * @throws Exception
     */
    public function toArray(): array
    {
        return [
            'token'              => $this->getToken(),
            'expired_at'         => $this->getExpiredAt(),
            'refresh_expired_at' => $this->getRefreshExpiredAt(),
        ];
    }
}
