<?php

namespace App\Request;

class SecretRequest
{
    private string $hash;

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): void
    {
        $this->hash = $hash;
    }
}