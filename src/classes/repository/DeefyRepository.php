<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\tracks\AlbumTrack;
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


    public function asPermission(string $uuid, string $permission): bool
    {
        $query = "SELECT * FROM User WHERE uuid = :uuid AND role = :role";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid, 'role' => $permission]);

        $row = $stmt->fetch();
        return $row >= $permission;
    }

    public function isPlaylistOwner(string $uuid, string $pl_uuid): bool
    {
        $query = "SELECT * FROM user2playlist WHERE uuid_user = :uuid_user AND uuid_pl = :uuid_pl";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid_user' => $uuid, 'uuid_pl' => $pl_uuid]);

        $row = $stmt->fetch();
        return $row !== false;
    }

    /*
     * Playlist management
     */

    /**
     * @param string $uuid user uuid
     * @return Playlist //EDIT TO PLAYLIST OBJECT !
     * @throws \Exception
     */
    public function findPlaylistById(string $uuid): Playlist
    {
        //ETAPE 1: TROUVER LA PLAYLIST
        $query = "SELECT * FROM playlist WHERE uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("Playlist not found");
        }

        $pl = new Playlist($row['nom'], array());
        $pl->setID($row['uuid']);

        //ETAPE 2: REMPLIR LA PLAYLIST AVEC SES TRACKS
        $query = "SELECT uuid_track FROM playlist2track WHERE uuid_pl = :uuid_pl";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid_pl' => $pl->uuid]);

        $tracks = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        foreach ($tracks as $track_uuid) {
            $pl->addTrack($this->findTrackById($track_uuid));
        }

        return $pl;
    }


    /**
     * @param Playlist $pk a playlist
     * @return Playlist
     */
    public function saveEmptyPlaylist(PlayList $pk): PlayList
    {
        $uuid = Uuid::uuid4();

        $query = "INSERT INTO playlist (uuid, nom) VALUES (:uuid, :nom)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid, 'nom' => $pk->name]);

        $pk->setID($uuid);
        return $pk;
    }

    public function deletePlaylist(string $uuid): void
    {
        $query = "DELETE FROM playlist WHERE uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $query = "DELETE FROM playlist2track WHERE uuid_pl = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $query = "DELETE FROM user2playlist WHERE uuid_pl = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);
    }

    public function findUserPlaylists(string $uuid): array
    {
        $query = "SELECT uuid_pl FROM user2playlist WHERE uuid_user = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $list = $stmt->fetchAll(\PDO::FETCH_COLUMN);


        if (empty($list)) {
            throw new \Exception("No playlists found for this user");
        }

        $playlists = [];
        foreach ($list as $pl_uuid) {
            $playlists[] = $this->findPlaylistById($pl_uuid);
        }

        return $playlists;
    }

    public function setPlaylistOwner(string $pl_uuid, string $user_uuid): void
    {
        $query = "INSERT INTO user2playlist (uuid_user, uuid_pl) VALUES (:uuid_user, :uuid_pl)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid_user' => $user_uuid, 'uuid_pl' => $pl_uuid]);
    }



    /*
     * Track management
     */

    public function addTrack(AlbumTrack $track): AlbumTrack
    {
        $query = "INSERT INTO track (uuid, titre, genre, duree, file_name, artistes, annee_sortie) VALUES (:uuid, :titre, :genre, :duree, :file_name, :artistes, :annee_sortie)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'uuid' => $track->uuid,
            'titre' => $track->title,
            'genre' => $track->gender,
            'duree' => $track->duration,
            'file_name' => $track->file_name,
            'artistes' => json_encode($track->artists), //array -> json
            'annee_sortie' => $track->date
        ]);

        return $track;
    }

    public function findTrackById(string $uuid): AlbumTrack
    {
        $query = "SELECT * FROM track WHERE uuid = :uuid";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid' => $uuid]);

        $row = $stmt->fetch();
        if ($row === false) {
            throw new \Exception("Track not found");
        }

        return new AlbumTrack(
            $row['uuid'],
            $row['titre'],
            $row['genre'],
            $row['duree'],
            $row['file_name'],
            json_decode($row['artistes']), //json -> array
            $row['annee_sortie']
        );
    }

    public function addTrackToPlaylist(string $pl_uuid, string $track_uuid): string
    {
        try {
            if ($this->findPlaylistById($pl_uuid) === null) return "Playlist not found";
            if ($this->findTrackById($track_uuid) === null) return "Track not found";
            if ($this->isTrackInPlaylist($pl_uuid, $track_uuid)) return "Track already in playlist";
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $query = "INSERT INTO playlist2track (uuid_pl, uuid_track) VALUES (:uuid_pl, :uuid_track)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid_pl' => $pl_uuid, 'uuid_track' => $track_uuid]);

        return "OK";
    }

    public function deleteTrackFromPlaylist(string $pl_uuid, string $track_uuid): string
    {
        if($this->findPlaylistById($pl_uuid) === null){
            return "Playlist not found";
        }

        if($this->findTrackById($track_uuid) === null){
            return "Track not found";
        }

        if(!$this->isTrackInPlaylist($pl_uuid, $track_uuid)){
            return "Track not in playlist";
        }

        $query = "DELETE FROM playlist2track WHERE uuid_pl = :uuid_pl AND uuid_track = :uuid_track";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid_pl' => $pl_uuid, 'uuid_track' => $track_uuid]);

        return "Track deleted from playlist";
    }

    public function isTrackInPlaylist(string $pl_uuid, string $track_uuid): bool
    {
        $query = "SELECT * FROM playlist2track WHERE uuid_pl = :uuid_pl AND uuid_track = :uuid_track";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['uuid_pl' => $pl_uuid, 'uuid_track' => $track_uuid]);

        $row = $stmt->fetch();
        return $row !== false;
    }


    /*
     * RECHERCHE DE TRACK
     */

    public function searchTracks(string $search): array
    {
        $search = strtolower($search);

        $query = "SELECT * FROM track 
              WHERE LOWER(titre) LIKE :search 
                 OR JSON_CONTAINS(LOWER(JSON_UNQUOTE(artistes)), :json_search, '$')";

        $stmt = $this->pdo->prepare($query);

        // Utilisez un placeholder pour le terme de recherche JSON
        $stmt->execute([
            'search' => '%' . $search . '%',
            'json_search' => json_encode($search)
        ]);

        $rows = $stmt->fetchAll();
        if (empty($rows)) {
            return [];
        }

        $tracks = [];
        foreach ($rows as $row) {
            $tracks[] = new AlbumTrack(
                $row['uuid'],
                $row['titre'],
                $row['genre'],
                $row['duree'],
                $row['file_name'],
                json_decode($row['artistes']),
                $row['annee_sortie']
            );
        }

        return $tracks;
    }

}


