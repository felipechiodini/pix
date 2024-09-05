<?php

namespace LiveOficial\Pix;

class PixService
{
    private $api;

    public function __construct(ApiContract $api)
    {
        $this->api = $api;
    }

    public function builder()
    {
        return new Builder($this->api);
    }
}
