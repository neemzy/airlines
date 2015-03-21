# Airlines

[![Travis CI](https://travis-ci.org/neemzy/airlines.svg)](https://travis-ci.org/neemzy/airlines)
[![David DM](https://david-dm.org/neemzy/airlines/dev-status.svg)](https://david-dm.org/neemzy/airlines#info=devDependencies)

**Airlines** is a virtual team board manager, allowing every member to handle his tasks on a weekly basis.

## Usage

All commands shall be ran at the application's root unless otherwise specified.

### Installation

*Note : using NPM is optional, but not doing so will prevent you from working on the app's front-end.*

- Make sure you have [Composer](https://getcomposer.org/download/) and [Node.js / NPM](https://docs.npmjs.com/getting-started/installing-node) installed
- Install [gulp](http://gulpjs.com/) globally : `npm install -g gulp`
- Run `composer install` to install back-end dependencies
- Run `npm install` to install front-end dependencies

You can also install Airlines using [Docker](https://www.docker.com/). If you are interested in using this method, just
follow the [guide](doc/docker_installation.md).

### Development

- Run `cp open.json.dist open.json` and edit the latter to define the app's root URL
- Run `gulp` to recompile development assets, start the livereload server and have your browser opened at the URL defined above

*Note : You can also force the use of a specific browser by adding an `app` parameter to `open.json` (see [gulp-open](https://www.npmjs.com/package/gulp-open)'s documentation about that).*

If you are unfamiliar with running Symfony applications, you may want to [RTFM](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html).  
**TL;DR:** if your PHP is >= 5.4, run `php app/console server:start` to run the app on `localhost:8000`.  
Otherwise, upgrade it.

#### Unit tests

- Run PHP tests with `phpunit` (the `--testdox` option can be used for a more behavior-driven results display)
- Run JS tests with `jest`

### Deployment

Run `gulp --dist` to compile production-ready assets.

#### A word on assets

Compiled assets are currently versioned as the project isn't linked to any specific deployment process/tool. A better way to handle this would be to `.gitignore` these files and make the command above part of your deployment workflow. In the meantime, please make sure to run it before committing changes to assets in order to minimize the resulting diff.

## Side notes

- [Development log](doc/log.md)
- [React test cases](doc/testing.md)
