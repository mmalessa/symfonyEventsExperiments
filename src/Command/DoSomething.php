<?php

declare(strict_types=1);

namespace App\Command;

use App\Event\SomethingHappened;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DoSomething extends Command
{
    protected static $defaultName = 'app:do:something';
    private $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Now something is happening.\n");

        $event = new SomethingHappened(new \DateTime(), "This is probably nothing important.");
        $this->eventDispatcher->dispatch($event);
    }
}
