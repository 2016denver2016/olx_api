<?php

namespace App\Console\Commands;

use App\Models\Olx;
use App\Repositories\OlxRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class VerifyAdvertPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify-advert:price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @param OlxRepository $olxRepository
     *
     * @return void
     */
    public function handle(OlxRepository $olxRepository): void
    {
        $olxRepository->verifyPrice();
    }
}
