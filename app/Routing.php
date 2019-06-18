<?php
/**
 * Created by PhpStorm.
 * User: Romuald
 * Date: 19/03/2019
 * Time: 16:47
 */

namespace app;

use controller\Profilecontroller;
use controller\Messagecontroller;
use app\src\App;

class Routing
{
    private $app;

    /**
     * Routing constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
    }

    public function setup() {
        $login = new ProfileController($this->app);
        $message = new MessageController($this->app);

        // Home
        $this->app->get('/', [$login, 'accueil']);

        // Connect or Disconnect
        $this->app->post('/login', [$login, 'login']);
        $this->app->get('/LogOut', [$login, 'disconnect']);

        // View Route profile
        $this->app->get('/account/(\w+)', [$login, 'public']);
        $this->app->get('/profile', [$login, 'view']);

        // Update Profile
        $this->app->get('/UpdateProfile', [$login, 'update']);
        $this->app->post('/ChangeAccountDB', [$login, 'updateDB']);

        // Add Like, Retweet or Remove
        $this->app->get('/like/(\d+)', [$message, 'like']);
        $this->app->get('/retweet/(\d+)', [$message, 'retweet']);

        // Add Follower
        $this->app->post('/follow', [$login, 'addFollower']);
        $this->app->post('/unfollow', [$login, 'removeFollower']);

        // Create Message
        $this->app->post('/AMessage', [$message, 'CreateMessage']);
        $this->app->post('/DMessage', [$message, 'DeleteMessage']);
        $this->app->post('/CMessage', [$message, 'ChangeMessage']);
        $this->app->post('/CMessageDB', [$message, 'ChangeMessageDB']);

        // Search other Account
        $this->app->post('/search', [$login, 'searchA']);
        
        // Create Account
        $this->app->get('/CreateAccount', [$login, 'create']);
        $this->app->post('/AccountCr', [$login, 'createDBHandler']);
    }
}