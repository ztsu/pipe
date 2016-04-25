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

$pipeline = new Pipeline;

$pipeline->add(function ($payload, $next) { return $next($payload . "first"); });
$pipeline->add(function ($payload) { return $payload . " second"; });

$pipeline->run(""); // "first second"
