<?php


namespace model\gateway;

use App\SRC\App;

class Messagegateway
{
    /**
     * @var \PDO
     */
    private $conn;

    private $id;
    private $idCreator;
    private $message;
    private $date;
    private $Pseudo;
    private $retweet;
    private $like;


    /**
     * CityGateway constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->conn = $app->getService('database')->GetConnexion();
    }

    /**
     * Function for insert a new City
     */
    public function insert() : void {
        $query = $this->conn->prepare('INSERT INTO compte (Creator, Message, Date)
                VALUES (:creator, :message, :date)');
        $executed = $query->execute([
            ':creator' => $this->idCreator,
            ':message' => $this->message,
            ':date' => $this->date,
        ]);

        if (!$executed) throw new \Error('Insert Failed.');

        $this->id = $this->conn->lastInsertId();
    }

    /**
     * Function for update City
     */
    public function update() : void {
        if (!$this->id) throw new \Error('Instance does not exist in City Base.');

        $query = $this->conn->prepare('UPDATE tweet SET Creator = :creator, Message = :message, Date = :date
                WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id,
            ':creator' => $this->idCreator,
            ':message' => $this->message,
            ':date' => $this->date
        ]);

        if (!$executed) throw new \Error('Update Failed.');
    }

    /**
     * @param int $id
     */
    public function delete(int $id) : void {
        $query = $this->conn->prepare('DELETE * FROM tweet WHERE id = :id');
        $executed = $query->execute([
            ':id' => $id
        ]);

        if (!$executed) throw new \Error('Delete Failed');

        $this->id = null;
        $this->idCreator = null;
        $this->message = null;
        $this->date = null;
        $this->Pseudo = null;
        $this->retweet = null;
        $this->like = null;
    }

    /**
     *
     * @param array $element
     */
    public function hydrate(array $element) {
        $this->id = $element['id'];
        $this->idCreator = $element['Creator'] ?? null;
        $this->message = $element['Message'] ?? null;
        $this->date = $element['Date'] ?? null;
        $this->Pseudo = $element['Pseudo'] ?? null;
        $this->retweet = null;
        $this->like = null;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getIdCreator()
    {
        return $this->idCreator;
    }

    /**
     * @return mixed
     */
    public function getRetweet()
    {
        return $this->retweet;
    }

    /**
     * @return mixed
     */
    public function setRetweet($retweet)
    {
        $this->retweet = $retweet;
    }

    /**
     * @return mixed
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * @return mixed
     */
    public function setLike($like)
    {
        $this->like = $like;
    }

    /**
     * @param mixed $idCreator
     */
    public function setIdCreator($idCreator): void
    {
        $this->idCreator = $idCreator;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->Pseudo;
    }

    /**
     * @param mixed $date
     */
    public function setPseudo($Pseudo): void
    {
        $this->Pseudo = $Pseudo;
    }
}