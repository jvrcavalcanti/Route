<?php

namespace Accolon\Route\Responses;

use Accolon\Route\Enums\ContentType;
use Accolon\Route\Response;

class TextResponse extends Response
{
    public function handle($body, int $code = 0, array $headers = [])
    {
        $this->setTypeContent(ContentType::TEXT);
        return $this->send((string) $body, $code, $headers);
    }
}
