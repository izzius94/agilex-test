# Project

## Description
This is a skeleton for Laravel project with Docker.

## Getting Started
### Dependencies
- Download and install [Docker Desktop](https://www.docker.com/products/docker-desktop)
- Install [Make](https://www.gnu.org/software/make/), command for Mac: `brew install make`,

### Installing and executing
There are 2 command to help you install, start and update the project

#### Installation
```bash
make install
```
With this command the whole project will be installed on you machine and after the initial setup the project will be
up and running.

**Before running this command you should check the `.env.example` file in the root of the project to check if all the
port assigned here are free on your machine. If you need to change some value copy the file in file named `.env` to
customize it according to your needs.**


#### Starting the project
```bash
make start
```

## Debugging and testing
### Xdebug
Xdebug is an extension for PHP, and provides a range of features to improve the PHP development experience, it provides
the following features:
- step debugging, a way to through your code in your IDE or editor while the script is executing
- profiling, analyse the performance of the PHP application and find bottleneck, with the help of visualization tools
- coverage, to show which parts of your code base are executed when running unit test with PHPUnit

Useful links:
- [XDebug](https://xdebug.org/)
- [Configure XDebug with PHPStorm](https://www.jetbrains.com/help/phpstorm/configuring-xdebug.html)
- [Configure XDebug with VS Code](https://dev.to/jackmiras/xdebug-in-vscode-with-docker-379l)

### Clockwork
Clockwork is a development tool for PHP available right in your browser. Clockwork gives you an insight into your
application runtime - including request data, performance metrics, log entries, database queries, cache queries, redis
commands, dispatched events, queued jobs, rendered views and more - for HTTP requests, commands, queue jobs and tests.

To see the insight click [here](http://127.0.0.1:8000/__clockwork)

**You may change the port according to you configuration**

[Link](https://underground.works/clockwork/#documentation)
### MailDev
MailDev is a simple way to test your project's generated email during development, with an easy to use web interface that
runs on your machine built on top of Node.js.

It is accessible [here](http://127.0.0.1:8025)

**You may change the port according to you configuration**

[Link](https://github.com/maildev/maildev)
### PHPUnit
PHPUnit is a programmer-oriented testing framework for PHP. It is an instance of the xUnit architecture for unit testing
frameworks.

[Link](https://docs.phpunit.de/en/10.2/)
### Code analysis

#### PHPStan
```bash
make phpstan
```
PHPStan scans your whole codebase and looks for both obvious & tricky bugs. Even in those rarely executed if statements
that certainly aren't covered by tests.

[Link](https://phpstan.org/user-guide/getting-started)

#### Laravel pint
```bash
make pint
```

Laravel Pint is an opinionated PHP code style fixer for minimalists.
Pint is built on top of PHP-CS-Fixer and makes it simple to ensure that your code style
stays clean and consistent.

[Link](https://laravel.com/docs/11.x/pint)

#### Code Coverage
```bash
make coverage
```
Code coverage is the way to see how mush of your code is covered by the tests exporting it in a folder called `coverage`
with HTML files to investigate the results.
