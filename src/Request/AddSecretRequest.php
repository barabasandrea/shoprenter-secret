<?php

namespace App\Request;

class AddSecretRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    public ?string $secret;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    public ?int $expireAfterViews;

    /**
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     * @Assert\GreaterThan(value=0)
     */
    public ?int $expireAfter;

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(?string $secret): void
    {
        $this->secret = $secret;
    }

    public function getExpireAfterViews(): ?int
    {
        return $this->expireAfterViews;
    }

    public function setExpireAfterViews(?int $expireAfterViews): void
    {
        $this->expireAfterViews = $expireAfterViews;
    }

    public function getExpireAfter(): ?int
    {
        return $this->expireAfter;
    }

    public function setExpireAfter(?int $expireAfter): void
    {
        $this->expireAfter = $expireAfter;
    }
}