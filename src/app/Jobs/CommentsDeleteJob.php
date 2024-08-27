<?php

namespace App\Jobs;

use App\Models\CommentLike;
use App\Models\Flowk;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Comment;

class CommentsDeleteJob extends Job
{
//    use Batchable, InteractsWithQueue, Queueable, SerializesModels;
    public $commentId;
    public $count;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($commentId)
    {
        $this->commentId = $commentId;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->count = 0;
        $this->deleteComment($this->commentId, $this->count);


    }

    public function deleteComment($id, $count)
    {
        $comment = Comment::query()->where('id', $id)->first();
        $parentComments = Comment::where('parent_id', $comment->id)->get();
        if (!empty($parentComments)) {
            foreach ($parentComments as $parentComment) {
                $comments = Comment::where('parent_id', $parentComment->id)->get();
                if (!empty($comments)) {
                    CommentLike::query()->where('comment_id', $parentComment->id)->delete();
                    $this->count ++;
                    $this->deleteComment($parentComment->id, $count);
                }
            }
        }
        $flowk = Flowk::query()->where('id', $comment->flowk_id)->first();
        $flowk->count_comment--;
        $flowk->save();
        $this->count ++;
        echo'delete '.$flowk->id.' ';
        CommentLike::query()->where('comment_id', $comment->id)->delete();
        $comment->delete();
    }
}
