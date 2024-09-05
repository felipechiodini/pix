<?php

namespace LiveOficial\Pix;

class Billing
{
    private $identifier;
    private $status;
    private $value;
    private $cpf;
    private $copyPaste;

    public function __construct(string $identifier, string $status, string $copyPaste)
    {
        $this->identifier = $identifier;
        $this->status = $status;
        $this->copyPaste = $copyPaste;
    }

    public static function fromResponse(object $data)
    {
        //
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function isPaid()
    {
        return $this->status === 'CONCLUIDA';
    }
}
