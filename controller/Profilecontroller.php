<?php


namespace Controller;

use controller\Controllerbase;
use app\src\Resquest\Request;
use app\src\App;

class Profilecontroller extends ControllerBase
{
    /**
     * Start a Session
     *
     * ProfileController constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        parent::__construct($app);
        session_start();
    }

    /**
     * Render Accueil php file
     *
     * @param Request $request
     * @return mixed
     */
    public function accueil(Request $request) {
        return $this->app->getService('render')('accueil');
    }

    /**
     * Render Create
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request) {
        return $this->app->getService('render')('create');
    }

    /**
     * Echo for change account with the actual informations account
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request) {
        $this->verif();

        $account = $this->app->getService('profileFinder')->FindOneBySpeudo($_SESSION['username']);
        return $this->app->getService('render')('update', ['back' => $account]);
    }

    /**
     * Recup the post new information and update Database
     *
     * @param Request $request
     * @return mixed
     */
    public function updateDB(Request $request) {
        $this->verif();

        $account = ['FirstName' => $request->GetParameters('FirstName'),
            'LastName' => $request->GetParameters('LastName'),
            'Mail' => $request->GetParameters('Mail'),
            'Pseudo' => $request->GetParameters('Pseudo')];
        $accountBefore = $this->app->getService('profileFinder')->FindOneBySpeudo($_SESSION['username']);

        if ($account['FirstName'] === '') $account['FirstName'] = $accountBefore->getFirstname();
        if ($account['LastName'] === '') $account['LastName'] = $accountBefore->getLastname();
        if ($account['Mail'] === '') $account['Mail'] = $accountBefore->getMail();
        if ($account['Pseudo'] === '') $account['Pseudo'] = $accountBefore->getPseudo();

        $back = $this->app->getService('profileFinder')->FindOneBySpeudo($_SESSION['username']);

        $all = $this->app->getService('profileFinder')->RecupAllUser();
        foreach ($all as $profile) {
            if ($account['Pseudo'] === $profile->getPseudo()) return $this->app->getService('render')('update', ['info' => "Pseudo déjà utilisé!", 'back' => $back]);

            if  ($account['Mail'] === $profile->getMail()) return $this->app->getService('render')('update', ['info' => "Mail déjà utilise!"]);
        }

        $changeDB = $this->app->getService('profileFinder')->ChangeProfileDB($account);
        if (!$changeDB) return $this->app->getService('render')('update', ['error' => 'Account Change is not applied']);

        $_SESSION['username'] = $account['Pseudo'];

        $this->redirect('/Petit_Boudin/profile');
    }

    /**
     * Recover Speudo and password send by form, check if Speudo is not empty if yes render accueil whit a error
     * else find user profile with Speudo, if the result is empty render accueil with a error
     * after all this, compare the speudo and password recover on Database with the recover form var
     * if is equal save the id and Speudo user on $_SESSION variable, to use on this file
     *
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request) {
        $login = ['speudo' => $request->GetParameters('login'), 'mdp' => $request->GetParameters('password')];

        if ($login['speudo'] === '' || $login['mdp'] === '') {
            return $this->app->getService('render')('accueil', ['error' => 'Pseudo or Password is missing!']);
        }

        $result = $this->app->getService('profileFinder')->FindOneBySpeudo($login['speudo']);
        if (!$result) {
            return $this->app->getService('render')('accueil', ['error' => 'Pseudo or Password is wrong or missing!']);
        }

        if ($login['speudo'] === $result->getPseudo() && md5($login['mdp']) === $result->getMdp()) {
            $_SESSION['username'] = $result->getPseudo();
            $_SESSION['id'] = $result->getId();
            $this->redirect('profile');
        }

        return $this->app->getService('render')('accueil', ['error' => 'Pseudo or Password is wrong!']);
    }

    /**
     * First verif, after find whit id, if result is empty render '404'
     * else render Profile with the result
     *
     * @param Request $request
     * @return mixed
     */
    public function public(Request $request, $pseudo, $error = null, $info = null) {
        $this->verif();

        $result = $this->app->getService('profileFinder')->FindOneBySpeudo($pseudo);
        if (!$result) return $this->app->getService('render')('account', ['error' => 'Probleme dans la recuperation du compte trouver']);

        $messages = $this->app->getService('messageFinder')->findPublicMessageById($result->getId());
        if ($messages === null) return $this->app->getService('render')('account', ['error' => 'Probleme dans la récupération des messages!', 'profile' => $result]);
        foreach ($messages as $message) {
            $message->setRetweet($this->app->getService('messageFinder')->findAllRetweetById($message->getId()));
            $message->setLike($this->app->getService('messageFinder')->findAllLikeById($message->getId()));
        }

        $isFollow = $this->app->getService('profileFinder')->findOneFollowersById($result->getId());

        $follow = $this->app->getService('profileFinder')->FindFollowerByID($result->getId());
        $followers = $this->app->getService('profileFinder')->FindFollowById($result->getId());

        return $this->app->getService('render')('account', ['error' => $error, 'info' => $info, 'profile' => $result, 'message' => $messages, 'followers' => $followers, 'follows' => $follow, 'isFollow' => $isFollow]);
    }

    /**
     * Render the login file and put $_SESSION with user information
     *
     * @param Request $request
     * @return mixed
     */
    public function view(Request $request) {
        $this->verif();
        $messages = $this->app->getService('messageFinder')->findAllMyMessageById($_SESSION['id']);
        if ($messages !== null) {
            foreach ($messages as $message) {
                $message->setRetweet($this->app->getService('messageFinder')->findAllRetweetById($message->getId()));
                $message->setLike($this->app->getService('messageFinder')->findAllLikeById($message->getId()));
            }
        }
        $follows = $this->app->getService('profileFinder')->FindFollowerByID($_SESSION['id']);
        $followers = $this->app->getService('profileFinder')->FindFollowById($_SESSION['id']);

        $fluxs = $this->app->getService('messageFinder')->findFlux();
        foreach ($fluxs as $flux) {
            $flux->setRetweet($this->app->getService('messageFinder')->findAllRetweetById($flux->getId()));
            $flux->setLike($this->app->getService('messageFinder')->findAllLikeById($flux->getId()));
        }

        if ($fluxs === null) return $this->app->getService('render')('profile', ['info' => "Flux non récupérer", 'message' => $messages, 'followers' => $followers]);

        return $this->app->getService('render')('profile', ['message' => $messages, 'followers' => $followers, 'follows' => $follows]);
    }

    /**
     * Function for verif $_Session is not empty or null
     * if yes redirect to home and set $_SESSION variable to null
     */
    public function verif() {
        if (empty($_SESSION['username']) || empty($_SESSION['id'])) {
            session_destroy();
            $this->redirect('/Petit_Boudin/');
        }
    }

    /**
     * Disconnect the user unset username and id and redirect to home
     */
    public function disconnect() {
        $this->verif();

        session_destroy();
        $this->redirect('/Petit_Boudin/');
    }

    /**
     * Add account on the database
     *
     * @param Request $request
     * @return mixed
     */
    public function createDBHandler(Request $request) {
        $account = ['FirstName' => $request->GetParameters('FirstName'),
                    'LastName' => $request->GetParameters('LastName'),
                    'Pseudo' => $request->GetParameters('Pseudo'),
                    'Mdp' => $request->GetParameters('Password'),
                    'Mail' => $request->GetParameters('Mail')];

        foreach ($account as $data) {
            if ($data === '' || $data === NULL) return $this->app->getService('render')('create', ['error' => "Vous avez une information vide", 'back' => $account]);
        }

        $all = $this->app->getService('profileFinder')->RecupAllUser();
        foreach ($all as $profile) {
            if ($account['Pseudo'] === $profile->getPseudo()) return $this->app->getService('render')('create', ['error' => "Pseudo déjà utilisé!"]);

            if  ($account['Mail'] === $profile->getMail()) return $this->app->getService('render')('create', ['error' => "Mail déjà utilise!"]);
        }

        $accountCreate = $this->app->getService('profileFinder')->Create($account);

        if (!$accountCreate) return $this->app->getService('render')('create', ['error' => "Erreur dans la création du compte", 'Michel' => $account]);

        return $this->app->getService('render')('accueil', ['message' => "Compte créé correctement"]);
    }

    /**
     * Function get ID and add followers with the current account connect
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function addFollower(Request $request) {
        $this->verif();
        $id = $request->GetParameters('id');
        $account = $this->app->getService('profileFinder')->FindOneById($id);
        if ($id === null) $this->public($request, $account->getPseudo(), null, 'Impossible de récupérer les infos du compte en question');

        $verif = $this->app->getService('profileFinder')->findOneFollowersById($id);
        if ($verif) $this->public($request, $account->getPseudo(), null, 'Impossible de récupérer les infos du compte en question');

        $addFollower = $this->app->getService('profileFinder')->addFollowerById($id);

        if (!$addFollower) {
            $account = $this->app->getService('profileFinder')->FindOneById($id);
            $this->public($request, $account->getPseudo(), null,"Probléme rencontré dans le follow du compte actuel");
        }

        $this->redirect('/Petit_Boudin/account/' . $account->getPseudo());
    }

    /**
     * Delete on database the followers choose
     *
     * @param Request $request
     * @param mixed $id
     * @return mixed
     */
    public function removeFollower(Request $request) {
        $this->verif();

        $id = $request->GetParameters('id');

        $account = $this->app->getService('profileFinder')->FindOneById($id);

        $verif = $this->app->getService('profileFinder')->findOneFollowersById($id);
        if (!$verif) $this->public($request, $account->getPseudo(), null,"Vous n'êtes pas un followers de cette personne");

        $removefollower = $this->app->getService('profileFinder')->removeFollowerById($id);
        if (!$removefollower) {
            $account = $this->app->getService('profileFinder')->FindOneById($id);
            $this->public($request, $account->getPseudo(), null,"Probléme rencontré dans le follow du compte actuel");
        }

        $this->redirect('/Petit_Boudin/account/' . $account->getPseudo());
    }


    /**
     * recup the search name and go to take the account like research string
     *
     * @param Request $request
     * @return mixed
     */
    public function searchA(Request $request) {
        $this->verif();

        $search = $request->GetParameters('search');

        if ($search === null || $search === '') return $this->app->getService('render')('profile', ['error' => "Le mot clé de la recherche est invalide"]);

        $searchRelease = $this->app->getService('profileFinder')->searchAccount($search);

        if ($searchRelease === null || $searchRelease === 0) return $this->app->getService('render')('profile', ['error' => "La recherche n'a pas put aboutir"]);

        return $this->app->getService('render')('search', ['search' => $searchRelease]);
    }
}