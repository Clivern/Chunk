## Exchanges Types

### Default or Nameless Exchange:

In this case messages are routed to the queue with the name specified by routing_key, if it exists.

```zsh
$ ./examples/terminal/chunk \
    role=listener \
    server=127.0.0.1 \
    queue_name=serviceA_events_orders \
    routing_key=serviceA_events_orders

$ ./examples/terminal/chunk \
    role=sender \
    server=127.0.0.1 \
    queue_name=serviceA_events_orders \
    routing_key=serviceA_events_orders \
    message=something
```


### Direct Exchange


### Fanout Exchange


### Topic Exchange


### Headers Exchange

