# Retrofit PHP Bundle
This Symfony2 bundle aims to provide an easy way to use the [tebru/retrofit-php](https://github.com/tebru/retrofit-php) library.  Please review the [documentation](https://github.com/tebru/retrofit-php) for functionality.

## Installation

```sh
composer require tebru/retrofit-bundle
```

As Retrofit does not include an http client, install Guzzle.

```sh
composer require guzzlehttp/guzzle
```

Add a line to your AppKernel.php

```php
new Tebru\RetrofitBundle\TebruRetrofitBundle()
```

## Usage

You will need to create providers for each API you want to consume.  They should be created with the builder and return a RestAdapter.  Here is an example:

```php
<?php

namespace AppBundle;

use Tebru\Retrofit\Adapter\Rest\RestAdapter;

class FooBarRestAdapterProvider
{
    public static function get($baseUrl)
    {
        // setup additional dependencies like an http client here and use
        // the setters to add them to the builder
        
        // build the rest adapter
        return RestAdapter::builder()
            ->setBaseUrl($baseUrl)
            ->build();
    }
}
```

Create annotated interfaces.  A simple example is shown below, more detailed examples are available [here](https://github.com/tebru/retrofit-php).

```php
<?php

namespace AppBundle;

use Tebru\Retrofit\Annotation as Rest;

interface Foo
{
    /**
     * @Rest\GET("/get/{id}")
     */
    public function getFoo($id);
}
```

Everything else can be configured in your service definition.  A yaml example is shown.

```yaml
parameters:
    foo.class: AppBundle\Foo
    bar.class: AppBundle\Bar
    
services:
    # create a rest adapter
    foobar_rest_adapter:
        class: %tebru_retrofit.rest_adapter.class%
        factory: [AppBundle\FooBarRestAdapterProvider, get]
        
    # use the rest adapter to create clients
    # clients must be tagged with 'tebru_retrofit.register'
    foo_service:
        class: %foo.class%
        factory: [@foobar_rest_adapter, create]
        arguments: [%foo.class%]
        tags:
            - { name: tebru_retrofit.register }
            
    bar_service:
        class: %bar.class%
        factory: [@foobar_rest_adapter, create]
        arguments: [%bar.class%]
        tags:
            - { name: tebru_retrofit.register }

    # inject your client services anywhere
    baz:
        class: AppBundle\Baz
        arguments: [@foo_service, @bar_service]
```

### Mocking
Because Retrofit uses interfaces, it's easy to create mock implementations if you do not want to hit a real API.

```yaml
services:
    foo_service:
        class: %foo.class%
        factory: [@foobar_rest_adapter, create]
        arguments: ['AppBundle\MockFoo']
        tags:
            - { name: tebru_retrofit.register }
```

```yaml
services:
    foo_service:
        class: %foo.class%
        factory: [@foobar_rest_adapter, create]
        arguments: [@mock_foo]
        tags:
            - { name: tebru_retrofit.register }
```

Take note of the `arguments` key in the above example.  You may pass in a concrete class as a string or service to use that instead of the generated class.  This is especially useful during development.
