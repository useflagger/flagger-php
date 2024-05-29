# Flagger API SDK for PHP 

[![Build Status](https://github.com/useflagger/flagger-php/actions/workflows/tests.yml/badge.svg)](https://github.com/useflagger/flagger-php)

This library helps with integrating Flagger into PHP applications.

## Installation

This library can be installed via [Composer](https://getcomposer.org):

```bash
composer require useflagger/flagger-php
```

## Configuration

The only required configuration is the Environment Token. You can get your Environment Token via the [Project settings](https://app.useflagger.com/admin/projects) in your Flagger account.

Configuration values can be set when creating a new API client or via environment variables. The environment takes precedence over values provided during the initialization process.

**Configuration via environment variables**

```bash
FLAGGER_ENVIRONMENT_TOKEN=tok-sample
```

**Configuration during initialization**

```php
use \Flagger\Client;

$client = new Client::config(['environment_token' => 'tok-sample'])->connect();
```

## Context

When retrieving values for settings a context can be provided that can change the value based on unique attributes of the context.

```php
use \Flagger\Client;
use \Flagger\Settings\Request\Entities\Context\Attribute;
use \Flagger\Settings\Request\Entities\Context\Context;
use \Flagger\Settings\Request\Entities\Context\Value;

$context = new Context('user', 'John Doe', 'john-doe', [
    new Attribute('key', [
        new Value('value'),
    ]),
]);

$client = Client::config()
    ->withContext($context)
    ->connect();

$results = $client->all();
$result = $client->get('setting-key')->getValue();

```

## Usage

Before retrieving a setting or flag, create a new client. If you configured your environment token key via environment variables there's nothing to add. Otherwise, see the example above.

```php
use \Flagger\Client;

$client = new Client();
```

### Retrieving Settings

#### All Settings

```php
$results = $client->all();

foreach ($results as $results) {
    $key = $result->key;
    $name = $result->name;
    $type = $result->type;
    $value = $result->value
}
```

#### Single Setting

```php
$result = $client->setting('setting-key');

$key = $result->key;
$name = $result->name;
$type = $result->type;
$value = $result->value
```

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/useflagger/flagger-php. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](http://contributor-covenant.org) code of conduct.

## License

The library is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).

## Code of Conduct

Everyone interacting in the Flagger Software’s code bases, issue trackers, chat rooms and mailing lists is expected to follow the [code of conduct](https://github.com/useflagger/flagger-php/blob/master/CODE_OF_CONDUCT.md).

## What is Flagger?

[Flagger](https://useflagger.com/) allows you to control which features and settings are enabled in your application giving you better flexibility to deploy code and release features.

Flagger Software was started in 2023 as an alternative to highly complex feature flag tools. Learn more [about us](https://useflagger.com/).
