<?php
use PDO as PDO;

class db {

    private $conn = "",
            $db_host = DB_SERVER_HOST,
            $db_name = DB_NAME,
            $user_name = DB_USER,
            $password = DB_PASSWD,
            $charset = DB_CHARSET;
    
    public function __construct($method = 1) {
        switch ($method) {
            case db_method::CONNECT:
                $this->connect_database();
                break;
            
            case db_method::CREATE:
                $this->create_database();
                break;
        }
    }
    
    /**
     * Adatbazis letrehozasa
     */
    private function create_database() {
        try {
            $conn = new PDO("mysql:host=" . $this->db_host, $this->user_name, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS " . $this->db_name;
            $conn->exec($sql);
        } catch (PDOException $e) {
            trigger_error($e, E_USER_NOTICE);
        }
    }
    
    /**
     * Kapcsolodas a szukseges adatbazishoz
     */
    private function connect_database() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->user_name, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec('SET CHARACTER SET ' . $this->charset);
        } catch (PDOException $e) {
            trigger_error($e, E_USER_NOTICE);
        }
    }
    
    /**
     * A filmek adatait tarolo tabla letrehozasa
     */
    public function create_movies_table() {
        $sql = "CREATE TABLE IF NOT EXISTS TOP_MOVIES (
                    ID int(11) AUTO_INCREMENT PRIMARY KEY,
                    TITLE VARCHAR(255) NOT NULL,
                    LENGTH SMALLINT(6) NOT NULL,
                    GENRES VARCHAR(255) NOT NULL,
                    RELEASE_DATE DATE NOT NULL,
                    OVERVIEW TEXT NOT NULL,
                    POSTER_URL TEXT NOT NULL,
                    TMDB_ID INT(11) NOT NULL,
                    TMDB_VOTE_AVERAGE FLOAT NOT NULL,
                    TMDB_VOTE_COUNT MEDIUMINT NOT NULL,
                    DIRECTORS_NAME VARCHAR(255) NOT NULL,
                    DIRECTORS_TMDB_ID VARCHAR(255) NOT NULL,
                    DIRECTORS_BIOGRAPHY TEXT NOT NULL,
                    DIRECTORS_BIRTH VARCHAR(255) NOT NULL)";
        $result = $this->conn->exec($sql);
        return $result >= 0 ? true : false;
    }
    
    /**
     * Tabla feltoltese a filmek adataival
     * 
     * @param array $movies Az osszes film adatait tarolo tomb
     * @return bool
     * @throws Exception
     */
    public function insert_movies(array $movies) :bool {
        $return_value = false;
        if (!empty($movies)) {
            $sql = "INSERT INTO TOP_MOVIES
                    (
                    TITLE,
                    LENGTH,
                    GENRES,
                    RELEASE_DATE,
                    OVERVIEW,
                    POSTER_URL,
                    TMDB_ID,
                    TMDB_VOTE_AVERAGE,
                    TMDB_VOTE_COUNT,
                    DIRECTORS_NAME,
                    DIRECTORS_TMDB_ID,
                    DIRECTORS_BIOGRAPHY,
                    DIRECTORS_BIRTH
                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);

            try {
                $this->conn->beginTransaction();
                foreach ($movies as $movie) {
                    $stmt->execute([
                        $movie["title"],
                        $movie["length"],
                        $movie["genres"],
                        $movie["release_date"],
                        $movie["overview"],
                        $movie["poster_url"],
                        $movie["tmdb_id"],
                        $movie["tmdb_vote_average"],
                        $movie["tmdb_vote_count"],
                        $movie["directors_name"],
                        $movie["directors_tmdb_id"],
                        $movie["directors_biography"],
                        $movie["directors_birth"]
                    ]);
                }
                $return_value = $this->conn->commit();
            } catch (Exception $e) {
                $return_value = $this->conn->rollback();
                throw $e;
            }
        }
        return $return_value;
    }
}

