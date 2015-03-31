# omnipay-bundle

[![Latest Version](https://img.shields.io/github/release/colinodell/omnipay-bundle.svg?style=flat-square)](https://github.com/colinodell/omnipay-bundle/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/colinodell/omnipay-bundle/master.svg?style=flat-square)](https://travis-ci.org/colinodell/omnipay-bundle)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/colinodell/omnipay-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/colinodell/omnipay-bundle/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/colinodell/omnipay-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/colinodell/omnipay-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/colinodell/omnipay-bundle.svg?style=flat-square)](https://packagist.org/packages/colinodell/omnipay-bundle)

Simple bundle for implementing [Omnipay](http://omnipay.thephpleague.com/) in your Symfony application.

## Install

Via Composer

``` bash
$ composer require colinodell/omnipay-bundle
```

## Usage

This bundle provides a new service called `Omnipay`.  It contains a single method `get()`, which returns a fully-configured gateway for you to use:

``` php
$stripe = $this->get('omnipay')->get('Stripe');

$paypal = $this->get('omnipay')->get('PayPal_Express');
```

You can then use these gateways like usual.

**Note:** Gateways are "cached" - calling `get('Some_Gateway')` multiple times will always return the same object.

## Configuration

Gateways can be configured in your `app/config/config.yml` file

``` yml
omnipay:
    methods:
        # Your config goes here
```

For example, to configure the [Stripe](https://github.com/thephpleague/omnipay-stripe) and [PayPal Express](https://github.com/thephpleague/omnipay-paypal) gateways:

``` yml
omnipay:
    methods:
        Stripe:
            apiKey: sk_test_BQokikJOvBiI2HlWgH4olfQ2

        PayPal_Express:
            username:     test-facilitator_api1.example.com
            password:     3MPI3VB4NVQ3XSVF
            signature:    6fB0XmM3ODhbVdfev2hUXL2x7QWxXlb1dERTKhtWaABmpiCK1wtfcWd.
            testMode:     false
            solutionType: Sole
            landingPage:  Login
```

**NOTE:** You should probably consider using parameters instead of storing credentials directly in your `config.yml` like that.

The method names should be whatever you'd typically pass into `Omnipay::create()`.  The configuration settings vary per gateway - see
[Configuring Gateways](http://omnipay.thephpleague.com/gateways/configuring/) in the Omnipay documentation for more details.

## Registering Custom Gateways

Custom gateways can be registered via the container by tagging them with `omnipay.gateway`:

```yml
# services.yml
services:
    my.test.gateway:
        class: Path\To\MyTestGateway
        tags:
            - { name: omnipay.gateway, alias: MyTest }

# config.yml
omnipay:
    methods:
        # Reference the gateway alias here
        MyTest:
            apiKey: abcd1234!@#
```

You can then obtain the fully-configured gateway by its alias:

```php
$this->get('omnipay')->get('MyTest');
```

## Testing

``` bash
$ phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email colinodell@gmail.com instead of using the issue tracker.

## Credits

- [Colin O'Dell](https://github.com/colinodell)
- [All Contributors](https://github.com/colinodell/omnipay-bundle/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
