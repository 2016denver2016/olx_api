<?php

namespace App\Services;

use App\Models\DeviceSession;
use App\Repositories\DeviceSessionRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

final class DeviceSessionService extends AbstractService
{
    /**
     * Constructor of the class
     *
     * @param DeviceSessionRepository $deviceSessionRepository
     */
    public function __construct(DeviceSessionRepository $deviceSessionRepository)
    {
        $this->repository = $deviceSessionRepository;
    }

    public function processDeviceSession(string $userAgent, ?string $deviceId = null, ?int $ttl = null): DeviceSession
    {
        $deviceSession = $this->repository->create([
            'user_id'     => auth()->id(),

            'device_type' => DeviceSession::getDeviceTypeByUserAgent($userAgent),
            'auth_token'  => Str::random(64),
            'valid_until' => Carbon::now()->addMilliseconds($ttl ?? DeviceSession::DEFAULT_TTL),
        ]);
        return $deviceSession;
    }
}
