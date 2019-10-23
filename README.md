# Api client

[![Build Status](https://travis-ci.org/shaggyrec/php-http-client.svg?branch=master)](https://travis-ci.org/shaggyrec/php-http-client)

Api client provide a way to make http request (to Hogashop rest api, for example) with retrying ability.
If response code >= 500, request will be retried several times. 
First retry in 1 second, second retry in 2 seconds, third retry in 4 seconds and so on, until 256 second limit was reach.

# Usage

```
$response = Hogashop\ApiClient\Client::getInstance($host)->post(
    '/order/department/2',
    $basketJson,
    [
        'allowDuplicates' => '1',
        'ignoreMutations' => '1',
    ],
    [
        'X-User-Id' => '1'
    ]);
```
