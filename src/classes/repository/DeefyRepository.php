<?php

namespace iutnc\deefy\repository;

use Ramsey\Uuid\Uuid;
use iutnc\deefy\audio\lists\Playlist;

class DeefyRepository
{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf)
    {
        $dsn = $conf['driver'] . ':host=' . $conf['host'] . ';dbname=' . $conf['dbname'];
        try {
            $this->pdo = new \PDO($dsn, $conf['user'], $conf['pass'], [
                \PDO::ATTR_PERSISTENT => true,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false,
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * @return DeefyRepository|null
     * @throws \Exception
     */
    public static function getInstance(): ?DeefyRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    public static function setConfig(string $file)
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }

        self::$config = $conf;
    }


    /*
     * User management
     */


    /**
     * @param string $email user email
     * @return array user data (id, email, passwrd, role)
     * @throws \Exception
     */
    public function getUserInfoByEmail(string $email): array
    {
        $query = "SELECT * FROM User WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($row)) {
            throw new \Exception("User not found");
        }
        return $row;
    }

    public function getUserInfoByUUID(string $uuid): array
    {
        $query = "SELECT * FROM User WHERE uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($row)) {
            throw new \Exception("User not found");
        }
        return $row;
    }


    /**
     * @param string $email user email
     * @param string $hash password hashed
     * @param int $role role provided
     * @return void
     */
    public function addUser(string $email, string $hash, int $role): void
    {
        $query = "INSERT INTO User (uuid, email, passwd, role) VALUES (:uuid, :email, :passwd, :role)";
        $stmt = $this->pdo->prepare($query);

        $uuid = Uuid::uuid4();

        $stmt->execute(['uuid' => $uuid, 'email' => $email, 'passwd' => $hash, 'role' => $role]);
    }






    /*
     * Playlist management
     */

    /**
     * @param int $id Playlist ID (autoincr))
     * @return string //EDIT TO PLAYLIST OBJECT !
     * @throws \Exception
     */
    public function findPlaylistById(int $id): string
    {
        $query = "SELECT * FROM playlist WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("Playlist not found");
        }
        return "playlist : " . $row['nom'] . ":". $row['id'] . "\n";
    }


    /**
     * @param Playlist $pk a playlist
     * @return Playlist
     */
    public function saveEmptyPlaylist(PlayList $pk): PlayList
    {
        $query = "INSERT INTO playlist (nom) VALUES (:nom)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['nom' => $pk->nom]);
        $pk->setID($this->pdo->lastInsertId());
        return $pk;
    }


    public function findUserPlaylists(string $uuid): array {
        $query = "SELECT id_pl FROM user2playlist WHERE uuid_user = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $list = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        if (empty($list)) {
            throw new \Exception("No playlists found for this user");
        }

        return $list;
    }

}


