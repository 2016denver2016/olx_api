<?php

namespace App\Repositories;

use App\Models\DeviceSession;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

final class DeviceSessionRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->model = DeviceSession::class;
    }

    public function create(array $data): DeviceSession
    {
        /** @var DeviceSession $deviceSession */
        $deviceSession = DeviceSession::create([
            'user_id'     => auth()->id(),
            'device_id'   => $data['device_id'] ?? '',
            'push_id'     => $data['push_id'] ?? '',
            'device_type' => $data['device_type'] ?? DeviceSession::DEVICE_TYPE_OTHER,
            'valid_until' => $data['valid_until'] ?? Carbon::now()->addMilliseconds(DeviceSession::DEFAULT_TTL),
            'auth_token'  => Str::random(64),
        ]);

        if (env('REDIS_HOST')) {
            Redis::hSet(':device_sessions', $deviceSession->id, $deviceSession->toJson());
        }

        return $deviceSession;
    }

    public function update(int $id, array $data): DeviceSession
    {
        /** @var DeviceSession $deviceSession */
        $deviceSession = $this->findOneById($id);

        $deviceSession->valid_until = (!empty($data['valid_until']) && $data['valid_until'] instanceof Carbon)
            ? $data['valid_until']
            : Carbon::now()->addMilliseconds(DeviceSession::DEFAULT_TTL);

        if (!empty($data['push_id']) && $deviceSession->push_id !== $data['push_id']) {
            $deviceSession->push_id = $data['push_id'];
        }

        $deviceSession->save();

        if (env('REDIS_HOST')) {
            Redis::hSet(':device_sessions', $deviceSession->id, $deviceSession->toJson());
        }

        return $deviceSession;
    }

    public function findByUserId(int $userId): ?DeviceSession
    {
        return DeviceSession::where('user_id', $userId)
            ->first();
    }

    public function findByDeviceId(int $deviceId): ?DeviceSession
    {
        return DeviceSession::where('device_id', $deviceId)
            ->first();
    }

    public function findValidByDeviceId(string $deviceId, $userId): ?DeviceSession
    {
        return DeviceSession::where('device_id', $deviceId)
            ->where('updated_at', '<', Carbon::now())
            ->where('user_id', $userId)
            ->first();
    }

    public function findByValidUntil(Carbon $validUntil): ?Collection
    {
        return DeviceSession::where('valid_until', '>', $validUntil)->get();
    }

    public function findExpired(Carbon $validUntil): ?Collection
    {
        return DeviceSession::where('valid_until', '<=', $validUntil)->get();
    }

    public function deleteExpired(Carbon $validUntil): void
    {
        DeviceSession::where('valid_until', '<=', $validUntil)->delete();
    }
}
