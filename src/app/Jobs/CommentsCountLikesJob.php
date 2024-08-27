<?php

namespace App\Jobs;

use App\Models\CommentLike;
use App\Models\Flowk;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Comment;

class CommentsCountLikesJob extends Job
{
//    use Batchable, InteractsWithQueue, Queueable, SerializesModels;
    public $lockerId;
    public $blockerId;

    public $isBlocked;




    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($blockerId, $lockerId, $isBlocked)
    {
        $this->blockerId = $blockerId;
        $this->lockerId = $lockerId;
        $this->isBlocked = $isBlocked;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $blockerCommentLikes = CommentLike::query()
            ->where(['user_id' => $this->blockerId, 'author_id' => $this->lockerId])
            ->get();
        if (!empty($blockerCommentLikes)) {
            foreach ($blockerCommentLikes as $blockerCommentLike) {
                $comment = Comment::query()
                    ->where('id', $blockerCommentLike->comment_id)
                    ->first();
                $this->isBlocked ? $comment->count_like-- :  $comment->count_like++;
                $comment->save();
            }
        }

        $blockerCommentLikes = CommentLike::query()
            ->where(['user_id' => $this->lockerId, 'author_id' => $this->blockerId])
            ->get();
        if (!empty($blockerCommentLikes)) {
            foreach ($blockerCommentLikes as $blockerCommentLike) {
                $comment = Comment::query()
                    ->where('id', $blockerCommentLike->comment_id)
                    ->first();
                $this->isBlocked ? $comment->count_like-- :  $comment->count_like++;
                $comment->save();
            }
        }
    }
}
