<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\SecretRepository')]
class Secret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 32, unique: true)]
    private ?string $hash;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private ?string $secretText;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $expiresAt;

    #[ORM\Column(type: 'integer')]
    private ?int $remainingViews;

    public function setHash(?string $hash): void
    {
        $this->hash = $hash;
    }

    public function getSecretText(): ?string
    {
        return $this->secretText;
    }

    public function setSecretText(?string $secretText): void
    {
        $this->secretText = $secretText;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeInterface $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getRemainingViews(): ?int
    {
        return $this->remainingViews;
    }

    public function setRemainingViews(?int $remainingViews): void
    {
        $this->remainingViews = $remainingViews;
    }
}

