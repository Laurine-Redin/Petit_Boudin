<?php


namespace model\finder;

use model\finder\Finderinterface;
use model\gateway\Profilegateway;
use App\SRC\App;

class ProfileFinder implements FinderInterface
{
    /**
     * @var \PDO
     */
    private $conn;

    /**
     * @var App
     */
    private $app;

    /**
     * __construct
     *
     * ProfileFinder constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
        $this->conn = $this->app->getService('database')->GetConnexion();
    }

    /**
     * Find account by Pseudo
     *
     * @param $speudo
     * @return ProfileGateway|null
     */
    public function FindOneBySpeudo($pseudo) {
        $query = $this->conn->prepare('SELECT * FROM compte WHERE Pseudo = :pseudo');
        $query->execute([':pseudo' => $pseudo]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element === null || !$element) return null;

        $city = new ProfileGateway($this->app);
        $city->hydrate($element);

        return $city;
    }

    /**
     * Find one account by id
     *
     * @param Int $id
     * @return ProfileGateway|null
     */
    public function FindOneById(Int $id) {
        $query = $this->conn->prepare('SELECT * FROM compte c WHERE id = :id');
        $query->execute([':id' => $id]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);

        if ($element === null) return null;

        $element['MDP'] = null;

        $city = new ProfileGateway($this->app);
        $city->hydrate($element);

        return $city;
    }

    /**
     * find all follower by id
     *
     * @param $id
     * @return array|null
     */
    public function FindFollowerByID($id) {
        $query = $this->conn->prepare('SELECT c.id, c.Pseudo FROM follower f INNER JOIN compte c ON c.id = f.id_profile WHERE f.id_follower = :id');
        $query->execute([':id' => $id]);
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($elements) === 0) return null;

        $followers = [];
        $follower = null;
        foreach ($elements as $element) {
            $follower = new ProfileGateway($this->app);
            $follower->hydrate($element);

            $followers[] = $follower;
        }

        return $followers;
    }

    /**
     * @param $id
     * @return array|null
     */
    public function FindFollowById($id) {
        $query = $this->conn->prepare('SELECT c.id, c.Pseudo FROM follower f INNER JOIN compte c ON c.id = f.id_follower WHERE f.id_profile = :id');
        $query->execute([':id' => $id]);
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($elements) === 0) return null;

        $followers = [];
        $follower = null;
        foreach ($elements as $element) {
            $follower = new ProfileGateway($this->app);
            $follower->hydrate($element);

            $followers[] = $follower;
        }

        return $followers;
    }

    /**
     * Recup all user on the database
     *
     * @return array|null
     */
    public function RecupAllUser() {
        $query = $this->conn->prepare('SELECT * FROM compte WHERE 1');
        $query->execute();
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($elements) === 0) return null;

        $followers = [];
        $follower = null;
        foreach ($elements as $element) {
            $follower = new ProfileGateway($this->app);
            $follower->hydrate($element);

            $followers[] = $follower;
        }

        return $followers;
    }

    /**
     * Create account
     *
     * @param array $profile
     * @return bool
     */
    public function Create(array $profile) {
        $query = $this->conn->prepare('INSERT INTO compte (FirstName, LastName, MailAdresse, MDP, Pseudo) VALUES (:FirstName, :LastName, :Mail, :MDP, :Pseudo)');
        return $query->execute([
            ':FirstName' => $profile['FirstName'],
            ':LastName' => $profile['LastName'],
            ':Mail' => $profile['Mail'],
            ':MDP' => md5($profile['Mdp']),
            ':Pseudo' => $profile['Pseudo']
        ]);
    }

    /**
     * Add followers by id
     *
     * @param int $id
     * @return bool
     */
    public function addFollowerById($id) {
        var_dump($id);
        $query = $this->conn->prepare('INSERT INTO `follower` (`id`, `id_profile`, `id_follower`) VALUES (NULL, :id, :current);');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function removeFollowerById($id) {
        $query = $this->conn->prepare('DELETE FROM follower WHERE id_profile = :id AND id_follower = :current');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOneFollowersById($id) {
        $query = $this->conn->prepare('SELECT * FROM follower WHERE id_profile = :id AND id_follower = :current');
        $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
        $element = $query->fetchAll(\PDO::FETCH_ASSOC);

        if (count($element) > 0) return true;
        return false;
    }

    /**
     * @param array $account
     * @return bool
     */
    public function ChangeProfileDB(array $account) {
        $query = $this->conn->prepare('UPDATE compte
	            SET FirstName = :firstname, LastName = :lastname, MailAdresse = :mail, Pseudo = :pseudo
                WHERE id = :id');
        return $query->execute([
            ':id' => $_SESSION['id'],
            ':firstname' => $account['FirstName'],
            ':lastname' => $account['LastName'],
            ':mail' => $account['Mail'],
            ':pseudo' => $account['Pseudo']
        ]);
    }

    /**
     * @param $search
     * @return array|null
     */
    public function searchAccount($search) {
        $query = $this->conn->prepare('SELECT c.id, c.Pseudo FROM compte c WHERE c.Pseudo like :search ORDER BY c.Pseudo');
        $query->execute([
            ':search' => '%' . $search . '%'
        ]);
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);

        if ($elements === 0 || $elements === null) return null;

        $accounts = [];
        $account = null;

        foreach ($elements as $element) {
            $account = new ProfileGateway($this->app);
            $account->hydrate($element);

            $accounts[] = $account;
        }

        return $accounts;
    }
}