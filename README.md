# Airlines

![Travis CI](https://travis-ci.org/neemzy/airlines.svg)

**Airlines** is a virtual team board manager, allowing every member to handle his tasks on a weekly basis.

## Usage

All commands shall be ran at the application's root unless otherwise specified.

### Installation

*Note : using NPM is optional, but not doing so will prevent you from working on the app's front-end.*

- Make sure you have [Composer](https://getcomposer.org/download/) and [Node.js / NPM](https://docs.npmjs.com/getting-started/installing-node) installed
- Install [gulp](http://gulpjs.com/) globally : `npm install -g gulp`
- Run `composer install` to install back-end dependencies
- Run `npm install` to install front-end dependencies

### Development

- Run `cp open.json.dist open.json` and edit the latter to define the app's root URL
- Run `gulp` to recompile development assets, start the livereload server and have your browser opened at the URL defined above

*Note : You can also force the use of a specific browser by adding an `app` parameter to `open.json` (see [gulp-open](https://www.npmjs.com/package/gulp-open)'s documentation about that).*

If you are unfamiliar with running Symfony applications, you may want to [RTFM](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html).  
**TL;DR:** if your PHP is >= 5.4, run `php app/console server:start` to run the app on `localhost:8000`.  
Otherwise, upgrade it.

#### Unit tests

- Run PHP tests with `phpunit` (the `--testdox` option can be used for a more behavior-driven results display)
- Run JS tests with `npm test`

The latter will require you to run `npm install -g jasmine` if not already done.

### Deployment

Run `gulp --dist` to compile production-ready assets.

#### A word on assets

Compiled assets are currently versioned as the project isn't linked to any specific deployment process/tool. A better way to handle this would be to `.gitignore` these files and make the command above part of your deployment workflow. In the meantime, please make sure to run it before committing changes to assets in order to minimize the resulting diff.

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
- Installed [Twitter Bootstrap](https://github.com/twbs/bootstrap) and [react-colorpicker](https://github.com/stayradiated/react-colorpicker)
- Set up an `AbstractJsonController` and a `JsonMemberController` to be able to list members for a given board
- Installed [reqwest](https://github.com/ded/reqwest) and [promise](https://github.com/then/promise)
- Set up a `MemberManager` class to generate root API URLs for members in order to fetch their tasks
- Wrote React components
- Installed [jasmine](https://github.com/jasmine/jasmine) and configured `npm test` script to run it in the right directory
- Set up a `DateHelper` JS class to deal with date formats
- Installed [react-dnd](https://github.com/gaearon/react-dnd)
