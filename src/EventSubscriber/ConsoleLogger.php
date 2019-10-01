<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\SomethingHappened;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConsoleLogger implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            SomethingHappened::class => [
                ['writeOnConsole', 1],
                ['sendSms', 10],
            ]
        ];
    }

    public function writeOnConsole(SomethingHappened $event)
    {
        echo "EventSubscriber: ConsoleLogger\n";
        echo sprintf("  %s %s\n", $event->getDateTime()->format('Y-m-d H:i:s'), $event->getDescription());
        echo PHP_EOL;
    }

    public function sendSms(SomethingHappened $event)
    {
        echo "EventSubscriber: SendSms\n";
        echo sprintf("  %s %s\n", $event->getDateTime()->format('Y-m-d H:i:s'), $event->getDescription());
        echo PHP_EOL;
    }
}
