<?php

namespace LiveOficial\Pix;

use Illuminate\Http\Client\PendingRequest;

interface ReceiverContract
{
    public function request(): PendingRequest;
}
