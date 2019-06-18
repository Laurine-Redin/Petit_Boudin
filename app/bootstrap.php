<?php
/**
 * Created by PhpStorm.
 * User: Romuald
 * Date: 19/03/2019
 * Time: 15:38
 */

namespace App;

use app\src\App;
use app\Routing;
use app\src\Response\Response;
use app\src\servicecontainer\Servicecontainer;
use database\Database;
use model\finder\Profilefinder;
use model\finder\Messagefinder;

$container = new ServiceContainer();
$app = new App($container);

$app->setService('database', new DataBase(getenv('MYSQL_ADDON_HOST'), getenv('MYSQL_ADDON_DB'), getenv('MYSQL_ADDON_USER'), getenv('MYSQL_ADDON_PASSWORD'),getenv('MYSQL_ADDON_PORT') ));

$app->setService('profileFinder', new ProfileFinder($app));
$app->setService('messageFinder', new MessageFinder($app));

$app->setService('render', function (String $template, Array $params = []) {
    ob_start();
    include __DIR__ . '/../view/' . $template . '.php';
    $content = ob_get_contents();
    ob_end_clean();

    if($template === '404') {
        header("HTTP/1.0 404 Not Found");
        $reponse = new Response($content, 404, ['HTTP/1.0 404 Not Found']);
        return $reponse;
    }

    return $content;
});

$routing = new Routing($app);
$routing->setup();

return $app;