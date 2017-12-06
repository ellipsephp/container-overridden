# Overridden container

This package provides a **[Psr-11 container](http://www.php-fig.org/psr/psr-11/)** decorator allowing to override container entries at runtime.

**Require** php >= 7.1

**Installation** `composer require ellipse/container-overridden`

**Run tests** `./vendor/bin/kahlan`

* [Decorating a container](#decorating-a-container)

## Decorating a container

This package provides a `Ellipse\Container\OverriddenContainer` class which can be used to decorate any Psr-11 container. It takes an associative array of alias => values pairs as second parameter. Once decorated, the container `->has()` method will return true when the given alias is a key of this array and the `->get()` method will return its associated value. When the alias is not a key of the array, the original container `->has()` and `->get()` methods are used.

It is especially useful when used with [ellipse/container-reflection](https://github.com/ellipsephp/container-reflection).

```php
<?php

namespace App;

class SomeClass
{
    public function __construct(SomeOtherClass $class)
    {
        //
    }
}
```

```php
<?php

namespace App;

use Psr\Http\Message\ServerRequestInterface;

class SomeOtherClass
{
    public function __construct(ServerRequestInterface $request)
    {
        //
    }
}
```

```php
<?php

use Psr\Http\Message\ServerRequestInterface;

use Some\Psr7ServerRequestFactory;
use Some\Psr11Container;

use Ellipse\Container\ReflectionContainer;
use Ellipse\Container\OverriddenContainer;

use App\SomeClass;

// Get a Psr-7 request from somewhere.
$request = Psr7ServerRequestFactory::fromGlobals();

// Get an instance of some Psr-11 container.
$container = new Psr11Container;

// Decorate the container.
$container = new ReflectionContainer(
    new OverriddenContainer($container, [
        ServerRequestInterface::class => $request,
    ])
);

// Returns an instance of SomeClass with the overridden Psr-7 instance injected in it's
// SomeOtherClass dependency.
$container->get(SomeClass::class);
```
