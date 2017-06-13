# ztsu/pipe

Provides a simple implemenation of a pipeline pattern.

## Requirements

Supports PHP starting with version 5.4.

## Installation

```bash
composer require ztsu/pipe
```

## Usage

Here is a basic usage example:

```php

use Ztsu\Pipe\Pipeline;

$a = function ($payload, $next) {
    return $next($payload . "a");
};

$b = function ($payload, $next) {
    return $next($payload . "b");
};

$pipeline = new Pipeline;

$pipeline->add($a);
$pipeline->add($b);

echo $pipeline->run(""); // "ab"
```

Here `$a` and `$b` are callables with two arguments. First is for accumulating a payload from previous stages.
Second is for continuing next stages in a pipeline.

For break pipeline just return `$payload` instead of call `$next`:

```php
$pipeline = new Pipeline;

$break = function ($payload, $next) {
    return $payload;
};

$pipeline->add($a);
$pipeline->add($break);
$pipeline->add($b);

echo $pipeline(""); // "a"
```

A pipeline is callable too. And it's able to use as a stage.

For this just add it to another pipeline:

```php
$pipeline = new Pipeline;

$pipeline->add($a);
$pipeline->add($bc);

echo $pipeline(""); // "abc"
```

If use pipeline with a break as a stage it breaks entire pipeline.

## License

MIT.
