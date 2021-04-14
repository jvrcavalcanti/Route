<?php

namespace Accolon\Route\Responses;

use Accolon\Route\Response;
use Accolon\Route\Enums\ContentType;

class JsonResponse extends Response
{
    public function handle($body, int $code = 0, array $headers = [])
    {
        $this->setTypeContent(ContentType::JSON);
        return $this->send(json_encode($body), $code, $headers);
    }
}
