<?php


namespace model\gateway;

use App\SRC\App;

class Profilegateway
{
    /**
     * @var \PDO
     */
    private $conn;

    private $id;
    private $firstname;
    private $lastname;
    private $mail;
    private $mdp;
    private $pseudo;


    /**
     * ProfileGateway constructor.
     * @param App $app
     */
    public function __construct(App $app) {
        $this->conn = $app->getService('database')->GetConnexion();
    }

    /**
     * Function for insert a new Profile
     */
    public function insert() : void {
        $query = $this->conn->prepare('INSERT INTO compte (FirstName, LastName, MailAdresse, MDP, Speudo)
                VALUES (:FirstName, :LastName, :MailAdresse, :MDP, :Speudo)');
        $executed = $query->execute([
            ':FirstName' => $this->firstname,
            ':LastName' => $this->lastname,
            ':MailAdresse' => $this->mail,
            ':MDP' => $this->mdp,
            ':Speudo' => $this->pseudo
        ]);

        if (!$executed) throw new \Error('Insert Failed.');

        $this->id = $this->conn->lastInsertId();
    }

    /**
     * Function for update Profile
     */
    public function update() : void {
        if (!$this->id) throw new \Error('Instance does not exist in City Base.');

        $query = $this->conn->prepare('UPDATE city SET FirstName = :FirstName, LastName = :LastName, MailAdresse = :MailAdresse,
                MDP = :MDP, Speudo = :Speudo
                WHERE id = :id');
        $executed = $query->execute([
            ':id' => $this->id,
            ':FirstName' => $this->firstname,
            ':LastName' => $this->lastname,
            ':MailAdresse' => $this->mail,
            ':MDP' => $this->mdp,
            ':Speudo' => $this->pseudo
        ]);

        if (!$executed) throw new \Error('Update Failed.');
    }

    /**
     * 
     * @param int $id
     */
    public function delete(int $id) : void {
        $query = $this->conn->prepare('DELETE * FROM city WHERE id = :id');
        $executed = $query->execute([
            ':id' => $id
        ]);

        if (!$executed) throw new \Error('Delete Failed');

        $this->id = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->mail = null;
        $this->mdp = null;
        $this->pseudo = null;
    }

    /**
     * 
     * @param array $element
     */
    public function hydrate(array $element) {
        $this->id = $element['id'];
        $this->firstname = $element['FirstName'] ?? null;
        $this->lastname = $element['LastName'] ?? null;
        $this->mail = $element['MailAdresse'] ?? null;
        $this->mdp = $element['MDP'] ?? null;
        $this->pseudo = $element['Pseudo'] ?? null;
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
    public function getMdp()
    {
        return $this->mdp;
    }

    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $speudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }
}