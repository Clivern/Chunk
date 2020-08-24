

### Direct Exchange

```zsh
$ ./examples/terminal/chunk \
    role=listener \
    server=127.0.0.1 \
    queue_name=serviceA_events_orders \
    exchange_name=serviceA_events \
    exchange_type=direct \
    routing_key=serviceA_events_orders

$ ./examples/terminal/chunk \
    role=sender \
    server=127.0.0.1 \
    queue_name=serviceA_events_orders \
    exchange_name=serviceA_events \
    exchange_type=direct \
    routing_key=serviceA_events_orders \
    message=something
```

### Topic Exchange

Subscriber: listens to all messages sent to `serviceB.events.*`

```zsh
$ ./examples/terminal/chunk \
    role=listener \
    server=127.0.0.1 \
    queue_name=serviceB_events_orders \
    exchange_name=serviceB_events \
    exchange_type=topic \
    routing_key='serviceB.events.*'

$ ./examples/terminal/chunk \
    role=sender \
    server=127.0.0.1 \
    queue_name=serviceB_events_orders \
    exchange_name=serviceB_events \
    exchange_type=topic \
    routing_key=serviceB.events.newOrders \
    message=newOrder

$ ./examples/terminal/chunk \
    role=sender \
    server=127.0.0.1 \
    queue_name=serviceB_events_orders \
    exchange_name=serviceB_events \
    exchange_type=topic \
    routing_key=serviceB.events.cancelOrders \
    message=cancelOrder
```

```zsh
$ ./examples/terminal/chunk \
    role=listener \
    server=127.0.0.1 \
    queue_name=serviceB_events_orders \
    exchange_name=serviceB_events \
    exchange_type=topic \
    routing_key='*.newOrders'

$ ./examples/terminal/chunk \
    role=sender \
    server=127.0.0.1 \
    queue_name=serviceB_events_orders \
    exchange_name=serviceB_events \
    exchange_type=topic \
    routing_key=serviceC.events.newOrders \
    message=newOrder

$ ./examples/terminal/chunk \
    role=sender \
    server=127.0.0.1 \
    queue_name=serviceB_events_orders \
    exchange_name=serviceB_events \
    exchange_type=topic \
    routing_key=serviceD.events.newOrders \
    message=newOrder

$ ./examples/terminal/chunk \
    role=sender \
    server=127.0.0.1 \
    queue_name=serviceB_events_orders \
    exchange_name=serviceB_events \
    exchange_type=topic \
    routing_key='serviceE.events.cancelOrders' \
    message=cancelOrder
```
