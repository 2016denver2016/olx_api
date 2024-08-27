<?php

namespace App\Http\Controllers;

use App\Exceptions\ResponseException;
use App\Transformers\ToArrayTransformer;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use Helpers;

    /**
     * @apiDefine user Role User
     * User access
     */

    /**
     * @apiDefine moderator Role Moderator
     * Moderator access
     */

    /**
     * @apiDefine admin Role Admin
     * Admin access
     */

    /**
     * @apiDefine any Role Admin|Moderator|User
     * Any User access
     */

    /**
     * @param Collection|Paginator|Model|array $data
     * @param string|null                      $location
     *
     * @return Response
     */
    protected function createdResponse($data = [], ?string $location = null): Response
    {
        if (empty($data)) {
            return $this->response->created($location, null);
        }

        return $this->createResponse($data)
            ->setStatusCode(201)
            ->header('Location', $location);
    }

    /**
     * @param Collection|Paginator|Model|array $data
     *
     * @return Response
     */
    protected function successResponse($data = []): Response
    {
        return $this->createResponse($data);
    }

    protected function successResponseWithoutData(?string $location = null): IlluminateResponse
    {
        $response = response('', $location ? 302 : 200);

        if ($location !== null) {
            $response->header('Location', $location);
        }

        return $response;
    }

    protected function noContentResponse(): Response
    {
        return $this->response->noContent();
    }

    ////////////////////////////////////////////////////////////////////////////////

    protected function forbiddenException(string $message = 'Forbidden'): void
    {
        $this->response->errorForbidden($message);
    }

    protected function unauthorizedException(string $message = 'Unauthorized'): void
    {
        $this->response->errorUnauthorized($message);
    }

    public function notFoundException(string $message = 'Not found'): void
    {
        $this->response->errorNotFound($message);
    }

    public function badRequestException(string $message = 'Bad Request'): void
    {
        $this->response->errorBadRequest($message);
    }

    protected function exceptionToString(\Exception $exception): string
    {
        $file    = $exception->getFile();
        $line    = $exception->getLine();
        $message = $exception->getMessage();

        return "{$file}:{$line} - {$message}";
    }

    ////////////////////////////////////////////////////////////////////////////////

    /**
     * @param Collection|Paginator|Model|array $data
     *
     * @return Response
     * @throws ResponseException
     */
    private function createResponse($data): Response
    {
        if ($data instanceof Collection) {
            return $this->response->collection($data, ToArrayTransformer::class);
        }

        if ($data instanceof Paginator) {
            return $this->response->paginator($data, ToArrayTransformer::class);
        }

        if (is_array($data)) {
            return $this->response->array(['data' => $data]);
        }

        if ($data instanceof Model) {
            return $this->response->array(['data' => $data->toArray()]);
        }

        throw new ResponseException('Unknown response.' . gettype($data));
    }
}
