<?php

namespace App\Response;


use App\Resource\View\SecretItem;

class SecretResponse
{
    private SecretItem $item;

    public function setItem(SecretItem $item): void
    {
        $this->item = $item;
    }

    public function getItem(): SecretItem
    {
        return $this->item;
    }

}
