<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\Exception;
use App\Services\OlxService;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Olx\OlxRequest;
use Illuminate\Support\Facades\DB;
use Goutte\Client as Parser;

class OlxController extends BaseController
{
    private OlxService $olxService;

    /**
     * Constructor of the class
     *
     * @param OlxService $olxService
     */
    public function __construct(OlxService $olxService)
    {
        $this->olxService = $olxService;
    }

    /**
     * @api            {post} /olx/create Create Olx subscribe
     * @apiDescription Olx subscribe Create
     * @apiGroup       Olx
     * @apiPermission  user
     * @apiVersion     0.1.0
     *
     * @apiParam {string} olx_url
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 204 Created
     *
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 404 Unprocessable Entity
     *  {
     *      "message": "404 Unprocessable Content",
     *      "errors": {
     *          "price": [
     *              "The price field not found."
     *          ],
     *          "advert_id": [
     *              "The advert_id field not found."
     *          ],
     *      },
     *      "status_code": 404
     *  }
     */
    public function createSubscribe(OlxRequest $request): Response
    {
        try {
            DB::beginTransaction();
            $this->olxService->create($request->validated());
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            $this->response->errorBadRequest($e->getMessage());
        }
        return $this->noContentResponse();
    }

}
