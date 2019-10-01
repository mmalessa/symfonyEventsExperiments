<?php

declare(strict_types=1);

namespace App\Event;

class SomethingHappened
{
    private $dateTime;
    private $description;

    public function __construct(\DateTime $dateTime, string $description)
    {
        $this->dateTime = $dateTime;
        $this->description = $description;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

}
