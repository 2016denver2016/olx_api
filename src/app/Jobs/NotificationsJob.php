<?php

namespace App\Jobs;

use App\Models\Notifications;
use App\Repositories\NotificationsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class NotificationsJob extends Job
{
//    use Batchable, InteractsWithQueue, Queueable, SerializesModels;
    public $followers;
    public $video;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($followers, $video)
    {
        $this->followers = $followers;
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        throw new \Exception('bla');
        foreach ($this->followers as $value) {
            $notification = Notifications::create([
                'user_id'       => $value['user_id'],
                'autor_id'      => $this->video->user_id,
                'video_id'      => $this->video->id,
                'type'          => Notifications::TYPE_ORIGINAL_VIDEO_FROM_FOLLOW,
                'status'        => Notifications::STATUS_ACTIVE,
                'created_at'    => time(),
                'updated_at'    => time(),
            ]);
            $notification->created_at = strtotime($notification->created_at);
            $notification->updated_at = strtotime($notification->updated_at);
//            var_dump($notification); die;
            $notification->save();
        }

        echo "qwe112\n";
//        NotificationsRepository::saveNotifications($this->video, $this->followers);
    }
}
