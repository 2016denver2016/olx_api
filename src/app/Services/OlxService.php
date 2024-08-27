<?php

namespace App\Services;

use App\Models\Olx;
use App\Repositories\OlxRepository;
use App\Repositories\UserRepository;
use Goutte\Client as Parser;
use Illuminate\Http\Request;

final class OlxService extends AbstractService
{

    private UserRepository $userRepository;
    /**
     * Constructor of the class
     *
     * @param OlxRepository $olxRepository
     * @param UserRepository $userRepository
     */
    public function __construct(OlxRepository $olxRepository, UserRepository $userRepository)
    {
        $this->repository = $olxRepository;
        $this->userRepository = $userRepository;
    }

    public function create($request): Olx
    {
        $client = new Parser();
        $crawler = $client->request('GET', $request['olx_url']);
        $user = $this->userRepository->findOneById(auth()->id());
        return $this->repository->createSubscribe($user, $crawler, $request['olx_url']);
    }

}
