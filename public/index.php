<?php
/**
 * Created by PhpStorm.
 * User: Romuald
 * Date: 19/03/2019
 * Time: 15:40
 */

namespace web;

use app\src\AutoLoader;

require_once __DIR__ . '/../app/src/AutoLoader.php';
AutoLoader::register();


$app = require_once __DIR__ . '/../app/bootstrap.php';
$app->run();