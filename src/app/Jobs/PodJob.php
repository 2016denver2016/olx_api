<?php

namespace App\Jobs;

use App\Models\PodFollowing;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class PodJob extends Job
{
//    use Batchable, InteractsWithQueue, Queueable, SerializesModels;
    public $followers;
    public $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($followers, $userId)
    {
        $this->followers = $followers;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        PodFollowing::query()->where('user_id', $this->userId)->delete();
        foreach ($this->followers as $value) {
            $pod = PodFollowing::create([
                'user_id'       => $this->userId,
                'follower_id'      => $value['id'],

            ]);
            $pod->save();
            echo "qwe112\n";
        }

    }
}
