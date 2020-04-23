<?php

namespace App\Message;

class AbstractMessage
{
    public $uuid;

    public $createdAt;

    public $isEventSourcingEnabled;

    public function getPayload(): array
    {
        return json_decode(json_encode($this), true);
    }
}