<?php

namespace App\Services;

use App\Repositories\AbstractRepositoryInterface;
use App\Repositories\FileRepository;
use App\Repositories\FlowkRepository;
use App\Repositories\UserRepository;
use App\Repositories\CommentRepository;

class AbstractService
{
    protected AbstractRepositoryInterface $repository;

    /**
     * @return AbstractRepositoryInterface|FileRepository|FlowkRepository|UserRepository|CommentRepository
     */
    public function getRepository(): AbstractRepositoryInterface
    {
        return $this->repository;
    }
}
