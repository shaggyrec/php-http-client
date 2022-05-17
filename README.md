# Api client

[![Build Status](https://travis-ci.org/shaggyrec/php-http-client.svg?branch=master)](https://travis-ci.org/shaggyrec/php-http-client)

Api client provide a way to make http request (to rest api, for example) with retrying ability.
If response code >= 500, request will be retried several times. 
First retry in 1 second, second retry in 2 seconds, third retry in 4 seconds and so on, until 256 second limit was reach.

# Installation
    composer require shaggyrec/php-http-client

# Usage

```
$response = Shaggyrec\PhpHttpClient\Client::getInstance('https://hostname.com')->post(
    '/path/to/resource',
    $requestJson,
    [
        'option' => '1',
        'anotherOption' => '1',
    ],
    [
        'X-User-Id' => '666'
    ]);
```

### You can use proxy or another options

```
    Shaggyrec\PhpHttpClient\Client::getInstance(
        'https://hostname.com',
        ['proxy' => 'http://username:password@ip:port/']
    );
```

# Tests

    ./tests/run.sh
