<?php

namespace App\Message;

class AbstractMessage
{
    public $uuid;

    public $isEventSourcingEnabled;

    public function getPayload(): array
    {
        return json_decode(json_encode($this), true);
    }
}