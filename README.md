# Flash PHP v0.1 
Created by Ingo Andelhofs  
Student at UHasselt 2018-2020  

# Composer install
You need to have composer installed. Just open your terminal and type the following command.
```bash
$ composer require ingoandelhofs/flash-php
```

If the composer package is installed you can just create a new `index.php` file and test your FlashPHP installation.
```php
<?php 

use FlashPHP\core\http\Router;
require_once 'vendor/autoload.php';

$home = new Router('home');
$home->simple_get('index', [], [], function() {
  print('Hello FlashPHP');
});

Router::start();
```

You can than serve your project using the php serve command as shown below. This will start a php server on localhost.
```bash
$ php -S localhost:80
```

Now you just need to type `localhost/?route=/home/index` into your browsers url bar and your project will run.




# Documentation
[FlashPHP v0.1 Docs](https://ingoandelhofs.gitbook.io/flash-php/v/v0.1/)