<?php

require dirname(__DIR__).'/vendor/autoload.php';

App\Kernel::bootstrapEnv('test');

define('CURRENT_DATETIME', '2018-01-02 23:00:01');