<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Flowk;
use Illuminate\Queue\SerializesModels;

class NotificationAfterNewVideoEvent
{
    use SerializesModels;

    public array $followers;
    public Flowk $video;

    public function __construct(array $followers, Flowk $video)
    {
//        var_dump($followers); die;
        $this->followers = $followers;
        $this->video = $video;
    }

    public function getFollowers(): array
    {
        return $this->followers;
    }

    public function getVideo(): Flowk
    {
        return $this->video;
    }

}
