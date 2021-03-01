<?php

namespace Accolon\Route\Responses;

use Accolon\Route\Response;

class HtmlResponse extends Response
{
    public function handle($body, int $code = 0, array $headers = [])
    {
        $this->setTypeContent(static::HTML);
        return $this->send($body, $code, $headers);
    }
}