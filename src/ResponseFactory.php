<?php

namespace Accolon\Route;

use Accolon\Route\Responses\JsonResponse;
use Accolon\Route\Responses\TextResponse;

class ResponseFactory
{
    private Response $response;

    public function text($body, int $code = 0, array $headers = [])
    {
        $this->response = new TextResponse();
        return $this->run($body, $code, $headers);
    }

    public function json($body, int $code = 0, array $headers = [])
    {
        $this->response = new JsonResponse();
        return $this->run($body, $code, $headers);
    }

    public function run($body = null, int $code = 0, array $headers = [])
    {
        return $this->response->handle($body, $code, $headers);
    }
}
