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

### Development

Run `gulp` to compile development assets, start the livereload server and have your browser opened at the app's root.

*Note : The URL reached by the latter can be specified in the `open.json` file created by the build if it does not exist. You can also force the use of a specific browser with the `app` parameter (see [gulp-open](https://www.npmjs.com/package/gulp-open)'s documentation about that).*

If you are unfamiliar with running Symfony applications, you may want to [RTFM](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html).  
**TL;DR:** if your PHP is >= 5.4, run `php app/console server:start` to run the app on `localhost:8000`.  
Otherwise, upgrade it.

#### Unit tests

- Run PHP tests with `phpunit` (the `--testdox` option can be used for a more behavior-driven results display)
- Run JS tests with `jest`

### Deployment

Run `gulp --dist` to compile production-ready assets.

## Side notes

- [Development log](doc/log.md)
- [React test cases](doc/testing.md)
