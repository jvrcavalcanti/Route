<?php

namespace Accolon\Route\Responses;

use Accolon\Route\Enums\ContentType;
use Accolon\Route\Response;

class JsonResponse extends Response
{
    public function handle($body, int $code = 0, array $headers = [])
    {
        $this->setTypeContent(ContentType::JSON);
        return $this->send(is_string($body) ? $body : json_encode($body), $code, $headers);
    }
}
