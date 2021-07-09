<?php

namespace Accolon\Route\Enums;

abstract class Status
{
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NON_AUTHORITATIVE = 203;
    const NOT_CONTENT = 204;
    const RESET_CONTENT = 205;
    const PARTIAL_CONTENT = 206;
    const MULTI_STATUS = 207;
    const MULTIPLE_CHOICES = 300;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const PAYMENT_REQUIRED = 402;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const NOT_ACCEPTABLE = 406;
    const REQUEST_TIMEOUT = 408;
    const CONFICT = 409;
    const ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const BAD_GATEWAY = 502;
    const SERVICE_UNAVAILABLE = 503;
    const GATEWAY_TIMEOUT = 504;

    public static function statusToMessage(int $status): string
    {
        return [
            200 => "OK",
            201 => "Created",
            202 => "Accepted",
            203 => "Non-Authoritative Information",
            204 => "Not content",
            205 => "Reset Content",
            206 => "Partial Content",
            207 => "Multi-Status",
            208 => "Already Reported",
            226 => "IM Used",
            300 => "Multiple Choices",
            400 => "Bad Request",
            401 => "Unauthorized",
            402 => "Payment Required",
            403 => "Forbidden",
            404 => "Not Found",
            405 => "Method Not Allowed",
            406 => "Not Acceptable",
            407 => "Proxy Authentication Required",
            408 => "Request Timeout",
            409 => "Confict",
            500 => "Internal Server Error",
            501 => "Not Implemented",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Timeout"
        ][$status];
    }

    public static function messageToStatus(string $message): int
    {
        return [
            "OK" => 200,
            "Created" => 201,
            "Accepted" => 202,
            "Non-Authoritative Information" => 203,
            "Not content" => 204,
            "Reset Content" => 205,
            "Partial Content" => 206,
            "Multi-Status" => 207,
            "Already Reported" => 208,
            "IM Used" => 226,
            "Multiple Choices" => 300,
            "Bad Request" => 400,
            "Unauthorized" => 401,
            "Payment Required" => 402,
            "Forbidden" => 403,
            "Not Found" => 404,
            "Method Not Allowed" => 405,
            "Not Acceptable" => 406,
            "Proxy Authentication Required" => 407,
            "Request Timeout" => 408,
            "Confict" => 409,
            "Internal Server Error" => 500,
            "Not Implemented" => 501,
            "Bad Gateway" => 502,
            "Service Unavailable" => 503,
            "Gateway Timeout" => 504
        ][$message];
    }
}
