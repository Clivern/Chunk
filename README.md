<p align="center">
    <img alt="chunk Logo" src="https://raw.githubusercontent.com/clivern/chunk/master/assets/img/gopher.png?v=1.2.0" width="180" />
    <h3 align="center">Chunk</h3>
    <p align="center">Asynchronous Task Queue Based on Distributed Message Passing for PHP</p>
    <p align="center">
        <a href="https://travis-ci.com/Clivern/Chunk"><img src="https://travis-ci.com/Clivern/Chunk.svg?branch=master"></a>
        <a href="https://packagist.org/packages/clivern/chunk"><img src="https://img.shields.io/badge/Version-1.2.0-red.svg"></a>
        <a href="https://github.com/Clivern/Chunk/blob/master/LICENSE"><img src="https://img.shields.io/badge/LICENSE-MIT-orange.svg"></a>
    </p>
</p>


## Documentation

### Installation:

To install the package via `composer`, use the following:

```zsh
$ composer require clivern/chunk
```

This command requires you to have `composer` installed globally.

### Basic Usage:


First create event handlers. Chunk supports these events
- `EventInterface::ON_MESSAGE_RECEIVED_EVENT`
- `EventInterface::ON_MESSAGE_FAILED_EVENT`
- `EventInterface::ON_MESSAGE_HANDLED_EVENT`
- `EventInterface::ON_MESSAGE_SENT_EVENT`
- `EventInterface::ON_MESSAGE_SEND_FAILURE_EVENT`

```php
use Clivern\Chunk\Contract\MessageInterface;
use Clivern\Chunk\Contract\EventInterface;
use Clivern\Chunk\Core\EventHandler;

class MessageReceivedEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_RECEIVED_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MessageInterface $message, $exception = null)
    {
        var_dump(sprintf('Message Received Event: %s', (string) $message));
    }
}

class MessageFailedEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_FAILED_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MessageInterface $message, $exception = null)
    {
        var_dump(sprintf('Message Failed Event: %s', (string) $message));
    }
}

class MessageHandledEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_HANDLED_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MessageInterface $message, $exception = null)
    {
        var_dump(sprintf('Message Handled Event: %s', (string) $message));
    }
}

class MessageSentEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_SENT_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MessageInterface $message, $exception = null)
    {
        var_dump(sprintf('Message Sent Event: %s', (string) $message));
    }
}

class MessageSendFailureEvent implements EventInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return EventInterface::ON_MESSAGE_SEND_FAILURE_EVENT;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MessageInterface $message, $exception = null)
    {
        var_dump(sprintf('Message Send Failure Event: %s', (string) $message));
        var_dump(sprintf('Error raised: %s', $exception->getMessage()));
    }
}

$eventHandler = new EventHandler();
$eventHandler->addEvent(new MessageReceivedEvent())
             ->addEvent(new MessageFailedEvent())
             ->addEvent(new MessageHandledEvent())
             ->addEvent(new MessageSendFailureEvent())
             ->addEvent(new MessageSentEvent());
```

Then create async message handlers, Each handler has a unique key so chunk can map the message to the appropriate handler.

In the following code, we create a handler to process any message with type `serviceA.processOrder`.

```php
use Clivern\Chunk\Contract\MessageHandlerInterface;
use Clivern\Chunk\Contract\MessageInterface;
use Clivern\Chunk\Core\Mapper;

class ProcessOrderMessageHandler implements MessageHandlerInterface
{
    /**
     * Invoke Handler.
     */
    public function invoke(MessageInterface $message): MessageHandlerInterface
    {
        var_dump(sprintf('Process Message: %s', (string) $message));

        return $this;
    }

    /**
     * onSuccess Event.
     *
     * @return void
     */
    public function onSuccess()
    {
        var_dump('Operation Succeeded');
    }

    /**
     * onFailure Event.
     *
     * @return void
     */
    public function onFailure()
    {
        var_dump('Operation Failed');
    }

    /**
     * Handler Type.
     */
    public function getType(): string
    {
        return 'serviceA.processOrder';
    }
}

$mapper = new Mapper();
$mapper->addHandler(new ProcessOrderMessageHandler());
```

Then create an instance of the message broker.

```php
use Clivern\Chunk\Core\Broker\RabbitMQ;

$broker = new RabbitMQ('127.0.0.1', 5672, 'guest', 'guest');
```


Now you can run listener daemon

```php
use Clivern\Chunk\Core\Listener;

$listener = new Listener($broker, $eventHandler, $mapper);
$listener->connect();
$listener->listen();
$listener->disconnect();
```

And start sending a message from a different process

```php
use Clivern\Chunk\Core\Sender;
use Clivern\Chunk\Core\Message;

$sender = new Sender($broker, $eventHandler);

$sender->connect();

$message = new Message();
$message->setId(1)
        ->setUuid('f9714a92-2129-44e6-9ef4-8eebc2e33958') // or leave & chunk will generate a uuid
        ->setPayload('something')
        ->setHandlerType('serviceA.processOrder'); // same as the one defined in ProcessOrderMessageHandler class -> getType method

$sender->send($message);
$sender->disconnect();
```

For a complete working examples, please check [this folder](/examples).


## Versioning

For transparency into our release cycle and in striving to maintain backward compatibility, Chunk is maintained under the [Semantic Versioning guidelines](https://semver.org/) and release process is predictable and business-friendly.

See the [Releases section of our GitHub project](https://github.com/clivern/chunk/releases) for changelogs for each release version of Chunk. It contains summaries of the most noteworthy changes made in each release.


## Bug tracker

If you have any suggestions, bug reports, or annoyances please report them to our issue tracker at https://github.com/clivern/chunk/issues


## Security Issues

If you discover a security vulnerability within Chunk, please send an email to [hello@clivern.com](mailto:hello@clivern.com)


## Contributing

We are an open source, community-driven project so please feel free to join us. see the [contributing guidelines](CONTRIBUTING.md) for more details.


## License

© 2020, clivern. Released under [MIT License](https://opensource.org/licenses/mit-license.php).

**Chunk** is authored and maintained by [@clivern](http://github.com/clivern).
