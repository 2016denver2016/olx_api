<?php

namespace App\Listeners;

use App\Events\NotificationAfterNewVideoEvent;
use App\Models\Flowk;

use App\Models\Notifications;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationAfterNewVideoEventListener implements ShouldQueue
{
    public function handle(NotificationAfterNewVideoEvent $event): void
    {
        $video = $event->getVideo();
        $followers = $event->getFollowers();
        foreach ($followers as $value) {
            $notification = Notifications::create([
                'user_id'       => $value['user_id'],
                'autor_id'      => $video->user_id,
                'video_id'      => $video->id,
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
    }
}
