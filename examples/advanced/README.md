## Exchange Types


Install chunk via composer

```php
$ composer install
```


### Default or Nameless Exchange:

In this case messages are routed to the queue with the name specified by `routing_key`, if it exists.

```php
$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    queue_name=serviceA_events_orders \
    routing_key=serviceA_events_orders

$ ./chunk \
    role=sender \
    server=127.0.0.1 \
    queue_name=serviceA_events_orders \
    routing_key=serviceA_events_orders \
    message=something
```


### Direct Exchange

A direct exchange delivers messages to queues based on a message routing key. The routing key is a message attribute added to the message header by the producer. Think of the routing key as an "address" that the exchange is using to decide how to route the message. A message goes to the queue(s) with the binding key that exactly matches the routing key of the message.

```php
# Three Listeners
$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=info,debug \
    exchange_type=direct

$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=error,fatal \
    exchange_type=direct

$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=warn \
    exchange_type=direct

# Senders
$ ./chunk \
    role=sender \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=warn \
    exchange_type=direct

$ ./chunk \
    role=sender \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=error \
    exchange_type=direct

$ ./chunk \
    role=sender \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=info \
    exchange_type=direct
```


### Fanout Exchange

A fanout exchange copies and routes a received message to all queues that are bound to it regardless of routing keys or pattern matching as with direct and topic exchanges. The keys provided will simply be ignored.

Fanout exchanges can be useful when the same message needs to be sent to one or more queues with consumers who may process the same message in different ways.

```php
# Three Listeners
$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    exchange_type=fanout

$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    exchange_type=fanout

$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    exchange_type=fanout

# Sender
$ ./chunk \
    role=sender \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    exchange_type=fanout
```


### Topic Exchange

Topic exchanges route messages to queues based on wildcard matches between the routing key and the routing pattern, which is specified by the queue binding. Messages are routed to one or many queues based on a matching between a message routing key and this pattern.


```php
# Three Listeners
$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=# \
    exchange_type=topic

$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=log.accounts.* \
    exchange_type=topic

$ ./chunk \
    role=listener \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=log.payment.* \
    exchange_type=topic

# Senders
$ ./chunk \
    role=sender \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=log.accounts.warn \
    exchange_type=topic

$ ./chunk \
    role=sender \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=log.payment.error \
    exchange_type=topic

$ ./chunk \
    role=sender \
    server=127.0.0.1 \
    exchange_name=serviceA_events_logs \
    queue_exclusive=true \
    routing_key=log.payment.info \
    exchange_type=topic
```


### Headers Exchange

A headers exchange routes messages based on arguments containing headers and optional values. Headers exchanges are very similar to topic exchanges, but route messages based on header values instead of routing keys. A message matches if the value of the header equals the value specified upon binding.

```php
```