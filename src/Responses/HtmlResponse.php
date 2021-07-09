<?php

namespace Accolon\Route\Responses;

use Accolon\Route\Enums\ContentType;

class HtmlResponse extends Response
{
    public function handle($body, int $code = 0, array $headers = [])
    {
        $this->setTypeContent(ContentType::HTML);
        return $this->send($body, $code, $headers);
    }
}