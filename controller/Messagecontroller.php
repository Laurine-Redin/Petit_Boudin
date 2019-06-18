<?php

namespace Controller;

use controller\Controllerbase;
use app\src\Resquest\Request;
use app\src\App;

class Messagecontroller extends ControllerBase {
    /**
     * ProfileController constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        parent::__construct($app);
    }

    /**
     * addMessage
     * Add Message in database with the post value of actually session start
     *
     * @param  mixed $request
     * @return void
     */
    public function CreateMessage(Request $request) {
        $message = ['message' => $request->GetParameters('message'), 'idCreator' => $_SESSION['id'], 'date' => date("Y-m-d H:i:s")];

        $createMessage = $this->app->getService('messageFinder')->Create($message);
        if (!$createMessage) return $this->app->getService('render')('profile', ['error' => "Le message n'a pas pu être créé"]);
        $this->redirect('/Petit_Boudin/profile');
    }

    /**
     * Delete Message on Database
     *
     * @param Request $request
     * @return mixed
     */
    public function DeleteMessage(Request $request) {
        $id = $request->GetParameters('idmessage');

        $delete = $this->app->getService('messageFinder')->DeleteDB($id);
        if (!$delete) return $this->app->getService('render')('profile', ['error' => "Le message n'a pas pu être supprimer"]);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function like(Request $request, $id) {
        $verif = $this->app->getService('messageFinder')->findOneLikeById($id);

        if ($verif['tweet_id'] === $id and $verif['user_id'] === $_SESSION['id']) {
            $destroy = $this->app->getService('messageFinder')->destroyLikeById($id);
            if (!$destroy) return $this->app->getService('render')('profile', ['error' => "Le message selectionner n'as pas pu être dislike"]);
            $this->redirect('/Petit_Boudin/profile');
        }

        $create = $this->app->getService('messageFinder')->createLikeById($id);

        if (!$create) return $this->app->getService('render')('profile', ['error' => "Le message n'as pas pu être like"]);

        $this->redirect('/Petit_Boudin/profile');
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function retweet(Request $request, $id) {
        $verif = $this->app->getService('messageFinder')->findOneRetweetById($id);

        if ($verif['tweet_id'] === $id and $verif['user_id'] === $_SESSION['id']) {
            $destroy = $this->app->getService('messageFinder')->destroyRetweetById($id);
            if (!$destroy) return $this->app->getService('render')('profile', ['error' => "Le message selectionner n'as pas pu être unretweet"]);
            $this->redirect('/Petit_Boudin/profile');
        }

        $create = $this->app->getService('messageFinder')->createRetweetById($id);

        if (!$create) return $this->app->getService('render')('profile', ['error' => "Le message n'as pas pu être retweet"]);

        $this->redirect('/Petit_Boudin/profile');
    }

    
    /**
     * change message view
     *
     * @param  mixed $request
     * @return void
     */
    public function ChangeMessage(Request $request) {
        $messageid = $request->GetParameters('MessageId');

        $message = $this->app->getService('messageFinder')->findOneById($messageid);

        return $this->app->getService('render')('change', ['message' => $message]);
    }
    
    /**
     * changeDBMessage
     * Change message in Database
     *
     * @param  mixed $request
     * @return void
     */
    public function ChangeMessageDB(Request $request) {
        $message = ['id' => $request->GetParameters('id'), 'message' => $request->GetParameters('message')];
        $change = $this->app->getService('messageFinder')->Change($message);
        if ($change === null || !$change) return $this->app->getService('render')('change', ['error' => 'Erreur lors du changement du message!']);

        $this->redirect('/Petit_Boudin/profile');
    }
}