<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LumenCors
{

    protected $settings = array(
        'origin'       => '*',    // Wide Open!
        'allowMethods' => '*'
    );

    public function __construct()
    {
        $this->settings['origin']        = env('CORS_ORIGIN', '*');
        $this->settings['allowMethods']  = env('CORS_ALLOW_METHODS', 'GET,HEAD,PUT,POST,DELETE,PATCH,OPTIONS');
        $this->settings['maxAge']        = env('CORS_MAX_AGE', '');
        $this->settings['allowHeaders']  = env('CORS_ALLOW_HEADERS', '');
        $this->settings['exposeHeaders'] = env('CORS_EXPOSE_HEADERS', '');
    }


    /**
     * @param Request $req
     * @param Response $rsp
     */
    protected function setOrigin($req, $rsp): void
    {
        $origin = $this->settings['origin'];
        if (is_callable($origin)) {
            $origin = call_user_func($origin,
                $req->header("Origin")
            );
        }
        $rsp->header('Access-Control-Allow-Origin', $origin);
    }

    /**
     * @param Request $req
     * @param Response $rsp
     */
    protected function setExposeHeaders($req, $rsp): void
    {
        if (isset($this->settings['exposeHeaders'])) {
            $exposeHeaders = $this->settings['exposeHeaders'];
            if (is_array($exposeHeaders)) {
                $exposeHeaders = implode(", ", $exposeHeaders);
            }

            $rsp->header('Access-Control-Expose-Headers', $exposeHeaders);
        }
    }

    /**
     * @param Request $req
     * @param Response $rsp
     */
    protected function setMaxAge($req, $rsp): void
    {
        if (isset($this->settings['maxAge'])) {
            $rsp->header('Access-Control-Max-Age', $this->settings['maxAge']);
        }
    }

    /**
     * @param Request $req
     * @param Response $rsp
     */
    protected function setAllowCredentials($req, $rsp): void
    {
        if (isset($this->settings['allowCredentials']) && $this->settings['allowCredentials'] === True) {
            $rsp->header('Access-Control-Allow-Credentials', 'true');
        }
    }

    /**
     * @param Request $req
     * @param Response $rsp
     */
    protected function setAllowMethods($req, $rsp): void
    {
        if (isset($this->settings['allowMethods'])) {
            $allowMethods = $this->settings['allowMethods'];
            if (is_array($allowMethods)) {
                $allowMethods = implode(", ", $allowMethods);
            }

            $rsp->header('Access-Control-Allow-Methods', $allowMethods);
        }
    }

    /**
     * @param Request $req
     * @param Response $rsp
     */
    protected function setAllowHeaders($req, $rsp): void
    {
        if (isset($this->settings['allowHeaders'])) {
            $allowHeaders = $this->settings['allowHeaders'];
            if (is_array($allowHeaders)) {
                $allowHeaders = implode(", ", $allowHeaders);
            }
        }
        else {  // Otherwise, use request headers
            $allowHeaders = $req->header("Access-Control-Request-Headers");
        }

        if (isset($allowHeaders)) {
            $rsp->header('Access-Control-Allow-Headers', $allowHeaders);
        }
    }

    /**
     * @param Request $req
     * @param Response $rsp
     */
    protected function setCorsHeaders($req, $rsp): void
    {
        if ($req->isMethod('OPTIONS')) {
            $this->setOrigin($req, $rsp);
            $this->setMaxAge($req, $rsp);
            $this->setAllowCredentials($req, $rsp);
            $this->setAllowMethods($req, $rsp);
            $this->setAllowHeaders($req, $rsp);
        }
        else {
            $this->setOrigin($req, $rsp);
            $this->setExposeHeaders($req, $rsp);
            $this->setAllowCredentials($req, $rsp);
            $this->setAllowMethods($req, $rsp);
            $this->setAllowHeaders($req, $rsp);
        }
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return Response|mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')) {
            $response = new Response("", 200);
        }
        else {
            $response = $next($request);
        }

        $this->setCorsHeaders($request, $response);

        return $response;
    }

}
