# Airlines

**Airlines** is a virtual team board manager, allowing every member to handle his affected tasks on a weekly basis.

## Use cases

- User creates a board
- User adds members to the board
- Members connect to the board
- Members fill up the board with tasks and their estimates, in order to get a total of 5 per week

## Installation

TODO

## Usage

TODO

## Logs

- Generated Doctrine entities and related CRUD interfaces with Symfony's console component
- Installed [JMSSerializerBundle](https://github.com/schmittjoh/JMSSerializerBundle)
- Set up a RESTful JSON API for task management through AJAX
- Set up a `Manager` class to refactor task operations
- Wrote unit tests for `Task` and `TaskManager` classes
- Created companion project [airlines-socket](https://github.com/neemzy/airlines-socket) to handle realtime UI updates
- Installed [TinyRedisClient](https://github.com/ptrofimov/tinyredisclient) and [socket.io-php-emitter](https://github.com/rase-/socket.io-php-emitter)
- Added bundle's own configuration file and made it accessible through the extension class
- Made Socket.IO `Emitter` available as a service
- Set up a `TaskListener` class to trigger socket emitting towards the configured host and port
- Set up a `ListenerResolver` class to keep using the `EventListener` as a service and gain control over its dependencies
- Set up a `TaskEmitter` class as a separate service to do the job, as using the `TaskManager` created a circular dependency (since the latter gets injected with Doctrine's `EntityManager`)
- Added avatar support for members by adding a `MemberListener` class handling image upload and deletion
- Installed [Twitter Bootstrap](http://getbootstrap.com) and [react-colorpicker](https://github.com/stayradiated/react-colorpicker)