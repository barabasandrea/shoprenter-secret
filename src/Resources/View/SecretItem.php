<?php

namespace App\Resources\View;

class SecretItem
{
    /**
     * @SerializedName("secret_text")
     */
    public string $secretText;

    /**
     * @SerializedName("created_at")
     */
    public \DateTimeInterface $createdAt;

    /**
     * @SerializedName("expires_at")
     */
    public \DateTimeInterface $expiresAt;

    /**
     * @SerializedName("remaining_views")
     */
    public int $remainingViews;

    public function __construct(string $secretText, \DateTimeInterface $createdAt, \DateTimeInterface $expiresAt, int $remainingViews)
    {
        $this->secretText = $secretText;
        $this->createdAt = $createdAt;
        $this->expiresAt = $expiresAt;
        $this->remainingViews = $remainingViews;
    }
}