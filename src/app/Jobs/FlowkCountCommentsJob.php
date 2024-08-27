<?php

namespace App\Jobs;

use App\Models\CommentLike;
use App\Models\Flowk;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Comment;

class FlowkCountCommentsJob extends Job
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
        $blockComment = [];
        $blockerUserFlowks = Flowk::query()->where('user_id', $this->blockerId)->get();
        if (!empty($blockerUserFlowks)) {
            foreach ($blockerUserFlowks as $blockerUserFlowk) {
                $flowkComments = Comment::query()
                    ->where(['flowk_id' => $blockerUserFlowk->id, 'user_id' => $this->lockerId])
                    ->get();
                if (!empty($flowkComments->toArray())) {
                    foreach ($flowkComments as $flowkComment) {
                        $this->countComment($flowkComment->id, $this->lockerId, $this->isBlocked, $blockComment);
                    }
                }
            }
        }
        $blockComment = [];
        $blockerUserFlowks = Flowk::query()->where('user_id', $this->lockerId)->get();
//        dump($blockerUserFlowks); die;
        if (!empty($blockerUserFlowks)) {
            foreach ($blockerUserFlowks as $blockerUserFlowk) {
                $flowkComments = Comment::query()
                    ->where(['flowk_id' => $blockerUserFlowk->id, 'user_id' => $this->blockerId])
                    ->get();
                if (!empty($flowkComments->toArray())) {
                    foreach ($flowkComments as $flowkComment) {
                        $this->countComment($flowkComment->id, $this->blockerId, $this->isBlocked, $blockComment);
                    }
                }
            }
        }
    }

    public function countComment($id, $userId, $isBlocked, $blockComment)
    {
        $comment = Comment::query()->where('id', $id)->first();
        $parentComments = Comment::where('parent_id', $comment->id)->get();
        if (!empty($parentComments)) {
            foreach ($parentComments as $parentComment) {
                $comments = Comment::where('parent_id', $parentComment->id)->get();
                if (!empty($comments)) {
                    $blockComment[] = $parentComment->id;
                    $this->countComment($parentComment->id, $userId, $isBlocked, $blockComment);
                }
            }
        }
        $flowk = Flowk::query()->where('id', $comment->flowk_id)->first();
        print_r($blockComment);
        if (!in_array($comment->id, $blockComment)) {
            $isBlocked ? $flowk->count_comment-- : $flowk->count_comment++;
        }
        $flowk->save();
        echo'delete '.$comment->id.' ';
    }
}
