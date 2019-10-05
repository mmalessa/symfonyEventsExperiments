# Symfony Events
A simple example to help you understand event subscribers in Symfony 4.3  
https://github.com/mmalessa/testSymfonyEvents  

# Install
```shell script
git clone git@github.com:mmalessa/symfonyEventsExperiments.git
cd testSymfonyEvents
composer install
```

# Usage
Run command
```shell script
./bin/console app:do:something
```

# How it works
## Event Dispatcher
(https://symfony.com/doc/current/components/event_dispatcher.html)  
The EventDispatcher component provides tools that allow your application components to communicate with each other 
by dispatching events and listening to them.   
The dispatcher is the central object of the event dispatcher system. In general, a single dispatcher is created, 
which maintains a registry of listeners.
When an event is dispatched via the dispatcher, it notifies all listeners registered with that event.

## Registering Event Listeners and Subscribers in the Service Container
Registering service definitions and tagging them with the **kernel.event_listener** and **kernel.event_subscriber**
tags is done in the symfony services configuration (yaml / xml). 
```yaml
services:
    App\EventSubscriber\ConsoleLogger:
        arguments:
            $firstArgument: 'service or something'
            $secondArgument: 'something else'
        tags: ['kernel.event_subscriber']
```

## Creating and dispatching an event
The "event" is information about an event that has occurred in the system.  
The Event object itself often contains data about the event being dispatched.  
The Event is immutable. Its structure is based on the Value Object pattern.  
So we have an event: 'SomethingHappened' (src/Event/SomethingHappened.php)  
```php
class SomethingHappened
{
    [...]
    public function __construct(\DateTime $dateTime, string $description)
    {
        $this->dateTime = $dateTime;
        $this->description = $description;
    }
    [...]
```
We dispatch it in command.  (src/Command/DoSomething.php)
```php
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
```

## Event Subscriber
Event Subscriber is part of the symfony event dispatcher.  
It has a list of events - the getSubscribedEvents() method. 
It is in fact, only that method, flagged by the EventSubscriberInterface that requires it, 
that defines that a class is an event subscriber.

It is called during startup (when the container is being compiled) which then builds 
the listeners from the events that are subscribed to. 
```php
class ConsoleLogger implements EventSubscriberInterface
{
    public static function __construct($firstArgument, $secondArgument)
    {
        [...]
    }

    public static function getSubscribedEvents()
    {
        return [
            SomethingHappened::class => [
                ['writeOnConsole', 1], // method name, priority
                ['sendSms', 10],
            ]
        ];
    }

    public function writeOnConsole(SomethingHappened $event)
    {
    [...]
```
