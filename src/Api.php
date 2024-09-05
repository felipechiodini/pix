<?php

namespace LiveOficial\Pix;

class Api implements ApiContract
{
    private $receiver;

    public function __construct(ReceiverContract $receiver)
    {
        $this->receiver = $receiver;
    }

    public function createBilling(Builder $builder)
    {
        $response = $this->receiver->request()
            ->post('cob', $builder->jsonSerialize())
            ->object();

        return new Billing(
            $response->txid,
            $response->status,
            $response->pixCopiaECola
        );
    }
}
