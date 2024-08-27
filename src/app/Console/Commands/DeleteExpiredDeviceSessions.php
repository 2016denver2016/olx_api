<?php

namespace App\Console\Commands;

use App\Models\DeviceSession;
use App\Repositories\DeviceSessionRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredDeviceSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device-sessions:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @param DeviceSessionRepository $deviceSessionRepository
     *
     * @return void
     */
    public function handle(DeviceSessionRepository $deviceSessionRepository): void
    {
        $deviceSessionRepository->deleteExpired(Carbon::now()->addMilliseconds(DeviceSession::DEFAULT_TTL));
    }
}
