<?php

namespace Framework\Response;

use Framework\Http\Response;

class HtmlResponse extends Response
{
    public static function html(string $html, int $statusCode = 200): Response
    {
        return new Response($statusCode, ['Content-Type' => 'text/html'], $html);
    }
}