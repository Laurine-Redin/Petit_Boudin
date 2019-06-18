<?php


namespace model\finder;

use model\finder\Finderinterface;
use model\gateway\Messagegateway;
use App\SRC\App;

class MessageFinder implements FinderInterface
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
     * ProfileFinder constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->app = $app;
        $this->conn = $this->app->getService('database')->GetConnexion();
    }

    /**
     * findOnebyId
     * Used for recup one message for change or juste focus message
     *
     * @param  mixed $id
     * @return void
     */
    public function findOnebyId($id) {
        $query = $this->conn->prepare('SELECT t.id, t.Message, t.Creator, t.Date
        , c.Pseudo FROM tweet t INNER JOIN compte c ON c.id = t.Creator WHERE t.id = :id');
        $query->execute([
            ':id' => $id
        ]);
        $element = $query->fetch(\PDO::FETCH_ASSOC);
        if ($element === 0) return null;

        $message = new MessageGateway($this->app);
        $message->hydrate($element);

        return $message;
    }

    /**
     * findAllMyMessageById
     * Recup all message of id account
     * 
     * @param  mixed $id
     * @return void
     */
    public function findAllMyMessageById($id) {
        $query = $this->conn->prepare('SELECT t2.* FROM (SELECT t.id, c.Pseudo, t.Creator, t.Message, t.Date
            FROM `follower` f
                INNER JOIN tweet t ON t.Creator = f.id_profile
                INNER JOIN compte c ON c.id = f.id_profile
                WHERE f.id_follower = :id
        UNION
        SELECT t.id, c.Pseudo, t.Creator, t.Message, t.Date
            FROM tweet t
                INNER JOIN compte c ON c.id = t.Creator
                WHERE t.Creator = :id
        UNION
        SELECT t.id, c.Pseudo, t.Creator, t.Message, t.Date
        	FROM tweet_retweet tr
            	INNER JOIN tweet t ON t.id = tr.tweet_id
                INNER JOIN compte c ON c.id = t.Creator
                WHERE tr.user_id = :id
        )AS t2 ORDER BY t2.Date DESC');
        $query->execute([
            ':id' => $id
        ]); 
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);
        if (count($elements) === 0) return null;
        
        $messages = [];
        $message = null;
        foreach($elements as $element) {
            $message = new MessageGateway($this->app);
            $message->hydrate($element);

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * Return the 20 last messages send by all accounts
     *
     * @return array|null
     */
    public function findFlux() {
        $query = $this->conn->prepare('SELECT t.id, c.Pseudo, t.Creator, t.Message, t.Date FROM `tweet` t INNER JOIN compte c ON c.id = t.Creator ORDER BY `t`.`Date` DESC LIMIT 20');
        $query->execute();

        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);
        if (count($elements) === 0) return null;

        $messages = [];
        $message = null;
        foreach($elements as $element) {
            $message = new MessageGateway($this->app);
            $message->hydrate($element);

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * Find all message for public profile view
     *
     * @param $id
     * @return array|null
     */
    public function findPublicMessageById($id) {
        $query = $this->conn->prepare('SELECT t2.* FROM (
                SELECT t.id, c.Pseudo, t.Creator, t.Message, t.Date
                    FROM tweet t
                        INNER JOIN compte c ON c.id = t.Creator
                        WHERE t.Creator = :id
            UNION
                SELECT t.id, c.Pseudo, t.Creator, t.Message, t.Date
                    FROM tweet_retweet tr
                        INNER JOIN tweet t ON t.id = tr.tweet_id
                        INNER JOIN compte c ON c.id = t.Creator
                        WHERE tr.user_id = :id
                )AS t2 ORDER BY t2.Date DESC
            ');
        $query->execute([
            ':id' => $id
        ]);
        $elements = $query->fetchAll(\PDO::FETCH_ASSOC);
        if (isset($elements[0]['id']) === null) return null;

        $messages = [];
        $message = null;
        foreach($elements as $element) {
            $message = new MessageGateway($this->app);
            $message->hydrate($element);

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * findAllRetweetById
     *
     * @param  mixed $id
     * @return void
     */
    public function findAllRetweetById($id) {
        $query = $this->conn->prepare('SELECT COUNT(id) as nombre FROM `tweet_retweet` WHERE tweet_id = :id');
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Find One retweet of message and return this
     *
     * @param $id
     * @return mixed
     */
    public function findOneRetweetById($id) {
        $query = $this->conn->prepare('SELECT * FROM `tweet_retweet` us WHERE us.tweet_id = :id AND us.user_id = :current');
        $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Destroy a rt of the current account for message choose
     *
     * @param $id
     * @return bool
     */
    public function destroyRetweetById($id) {
        $query = $this->conn->prepare('DELETE FROM `tweet_retweet` WHERE tweet_id = :id AND user_id = :current');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    /**
     * Create rt for a message with the current account
     *
     * @param $id
     * @return bool
     */
    public function createRetweetById($id) {
        $query = $this->conn->prepare('INSERT INTO tweet_retweet(tweet_id, user_id) VALUES (:id, :current)');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    /**
     * Find One like of message and return this
     *
     * @param $id
     * @return mixed
     */
    public function findOneLikeById($id) {
        $query = $this->conn->prepare('SELECT * FROM `tweet_like` us WHERE us.tweet_id = :id AND us.user_id = :current');
        $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Destroy like for message of this current account
     *
     * @param $id
     * @return bool
     */
    public function destroyLikeById($id) {
        $query = $this->conn->prepare('DELETE FROM `tweet_like` WHERE tweet_id = :id AND user_id = :current');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    /**
     * Create like for a message with current account
     *
     * @param $id
     * @return bool
     */
    public function createLikeById($id) {
        $query = $this->conn->prepare('INSERT INTO tweet_like(tweet_id, user_id) VALUES (:id, :current)');
        return $query->execute([
            ':id' => $id,
            ':current' => $_SESSION['id']
        ]);
    }

    /**
     * findAllLikeById
     * Return the life count of message
     *
     *
     * @param  mixed $id
     * @return void
     */
    public function findAllLikeById($id) {
        $query = $this->conn->prepare('SELECT COUNT(id) as nombre FROM `tweet_like` WHERE tweet_id = :id');
        $query->execute([
            ':id' => $id
        ]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Create
     *
     * @param  mixed $tab
     * @return void
     */
    public function Create($tab) {
        $query = $this->conn->prepare('INSERT INTO tweet (Creator, Message, Date) VALUES (:creator, :message, :date)');
        return $query->execute([
            ':creator' => $tab['idCreator'],
            ':message' => $tab['message'],
            ':date' => $tab['date']
        ]);
    }

    /**
     * Change information on database for message choose
     *
     * @param  mixed $tab
     * @return void
     */
    public function Change($tab) {
        $query = $this->conn->prepare('UPDATE tweet SET Message = :message WHERE id = :id');
        return $query->execute([
            ':id' => $tab['id'],
            ':message' => $tab['message'] . ' (edit)'
        ]);
    }
}