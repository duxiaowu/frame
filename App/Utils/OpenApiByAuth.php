<?php
/**
 * openapi工具类
 */
namespace App\Utils;

class OpenApiByAuth
{
    use OpenApiTrait;
    protected static $auth = "Basic OGUyMWFhREU1RDc3OjBiNTA1YTQ2NDE0ZGFlZWMxZjU5YzVhYjBjOGUyMDkx";

    public function __construct()
    {
        $this->header = array("Authorization:" . self::$auth);
    }
}

