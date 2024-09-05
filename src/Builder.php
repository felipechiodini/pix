<?php

namespace LiveOficial\Pix;

use JsonSerializable;

class Builder implements JsonSerializable
{
    private $api;
    private $cpf;
    private $name;
    private $value;
    private $key;
    private $payer;
    private $information = [];
    private $lifetime = 1800;

    public function __construct(ApiContract $api)
    {
        $this->api = $api;
    }

    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setLifetime(int $value): self
    {
        $this->lifetime = $value;
        return $this;
    }

    public function setPaymentRequires(string $payer): self
    {
        $this->payer = $payer;
        return $this;
    }

    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setCpf($cpf): self
    {
        $this->cpf = $cpf;
        return $this;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    public function addInformation(string $name, string $value): self
    {
        array_push($this->information, [
            'nome' => $name,
            'valor' => $value
        ]);

        return $this;
    }

    public function create(): Billing
    {
        return $this->api->createBilling($this);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'calendario' => [
                'expiracao' => $this->lifetime
            ],
            'devedor' => [
                'cpf' => $this->cpf,
                'nome' => $this->name,
            ],
            'valor' => [
                'original' => $this->value
            ],
            'chave' => $this->key,
            'solicitacaoPagador' => $this->payer,
            'infoAdicionais' => $this->information
        ];

        return $data;
    }
}
