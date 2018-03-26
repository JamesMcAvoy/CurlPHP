# curlPHP
PHP class for cURL
Send HTTP requests

### Installation
```Bash
composer require lordarryn/curlphp
```

### Example :
```PHP
<?php
use CurlPHP\CurlPHP as Curl;

require __DIR__.'/vendor/autoload.php';

$app = new Curl('google.com');

$app->setUserAgent('my user agent')
    ->setTimeout(60)
    ->setFollow(false);

$return = $app->run();

echo $return.PHP_EOL; //google page

echo $app->getCookieFile(); //cookie list

$app->setUrl('path/to/website')->setPost(array(
	'foo' => 'bar'
)); //POST request
```
