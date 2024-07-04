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


    /**
     * @SerializedName("secret_url")
     */
    public string $secretUrl;


    public function __construct(
        string $secretText,
        \DateTimeInterface $createdAt,
        \DateTimeInterface $expiresAt,
        int $remainingViews,
        string $secretUrl
    )
    {
        $this->secretText = $secretText;
        $this->createdAt = $createdAt;
        $this->expiresAt = $expiresAt;
        $this->remainingViews = $remainingViews;
        $this->secretUrl = $secretUrl;
    }


}