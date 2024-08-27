<?php

namespace App\Repositories;

use App\Exceptions\NotFoundException;
use App\Mails\ChangePriceSender;
use App\Models\Olx;
use App\Repositories\UserRepository;
use Goutte\Client as Parser;

final class OlxRepository extends AbstractRepository
{

    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->model = Olx::class;
        $this->userRepository = $userRepository;
    }

    public function createSubscribe($user, $crawler, $url): Olx
    {
        $price = preg_replace("/[^0-9]/", '', $crawler->filter('.css-90xrc0')->text());
        $advertId = preg_replace("/[^0-9]/", '', $crawler->filter('.css-12hdxwj')->text());
        if (!$price) {
            throw new NotFoundException("The advert has no price");
        }
        if (!$advertId) {
            throw new NotFoundException("advert ID not found ");
        }
        $olx = Olx::create([
            'url'                   => $url,
            'user_id'               => auth()->id(),
            'advert_id'             => (int)$advertId,
            'status'                => Olx::STATUS_ACTIVE,
            'price'                 => (int)$price,
            'email'                 => $user->email
        ]);
        return $olx;
    }

    public function verifyPrice()
    {
        $adverts = $this->model::select()->where('status', $this->model::STATUS_ACTIVE)->distinct('advert_id')->get();
        if (!empty($adverts)) {
            foreach ($adverts as $advert) {
                $client = new Parser();
                $crawler = $client->request('GET', $advert->url);
                $price = preg_replace("/[^0-9]/", '', $crawler->filter('.css-90xrc0')->text());
                if ($price != $advert->price) {
                    $this->model::where('advert_id', $advert->advert_id)->update(['price' => $price]);
                    $this->sendMessages($advert->advert_id);
                }
            }
        }
    }

    public function sendMessages($advertId)
    {
        $usersAdvert = $this->model::select()->with('user')->where('advert_id', $advertId)->get();
        if (!empty($usersAdvert)) {
            foreach ($usersAdvert as $userAdvert) {
                $sender = new ChangePriceSender();
                $sender->to($this->userRepository->findOneById($userAdvert->user_id), $userAdvert)
                    ->setUserAdvertChangePriceMessage()
                    ->send();
            }
        }
    }


}
