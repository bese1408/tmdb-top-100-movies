<?php
/**
 * Description of functions
 *
 * @author Bese
 */
class movies {
    private $top_100_id = [];
    private $top_100 = [];
    
    public function __construct() {
        $this->get_top_hundred_id();
        $this->set_top_hundred_data();
    }
    
    /**
     * Top 100 film azonositojanak begyujtese
     */
    private function get_top_hundred_id() {
        for ($i=0; $i<5; $i++) {
            $json = file_get_contents(API_BASE_URL . "movie/top_rated?api_key=" . API_KEY . "&language=" . API_LANG . "&page=" . ($i+1));
            $movies = json_decode($json, true);
            foreach ($movies["results"] as $movie) {
                array_push($this->top_100_id, $movie["id"]);
            }
        }
    }
    
    /**
     * Top 100 film adatainak begyujtese
     */
    private function set_top_hundred_data() {
        if (!empty($this->top_100_id)) {
            for ($i=0; $i<100; $i++) {
                // Top 100 film alapadatainak begyujtese 
                $movie_json = file_get_contents(API_BASE_URL . "movie/" . $this->top_100_id[$i] . "?api_key=" . API_KEY . "&language=" . API_LANG);
                $movie = json_decode($movie_json, true);
                // Mufajok begyujtese
                $genres = [];
                if (!empty($movie["genres"])) {
                    $genres = array_column($movie["genres"], "name");
                }
                // A filmes stab alapadatainak begyujtese
                $credits_json = file_get_contents(API_BASE_URL . "movie/" . $this->top_100_id[$i] . "/credits?api_key=" . API_KEY . "&language=" . API_LANG);
                $credits = json_decode($credits_json, true);
                // A rendezo(k) megkeresese, valamint adataiknak begyujtese
                $director_name = $director_tmdb_id = $director_biography = $director_birth = "";
                foreach ($credits["crew"] as $crew) {
                    if ($crew["job"] === "Director") {
                        $director_json = file_get_contents(API_BASE_URL . "person/" . $crew["id"] . "?api_key=" . API_KEY . "&language=" . API_LANG);
                        $director = json_decode($director_json, true);
                        $director_name .= empty($director_name) ? $director["name"] : (", " . $director["name"]);
                        $director_tmdb_id .= empty($director_tmdb_id) ? $this->director_name_pre($director["name"]) . $director["id"] : (", " . $this->director_name_pre($director["name"]) . $director["id"]);
                        $director_biography .= empty($director_biography) ? $this->director_name_pre($director["name"]) . (empty($director["biography"]) ? "ismeretlen" : $director["biography"]) : (", " . $this->director_name_pre($director["name"]) . (empty($director["biography"]) ? "ismeretlen" : $director["biography"]));
                        $director_birth .= empty($director_birth) ? $this->director_name_pre($director["name"]) . (empty($director["birthday"]) ? "ismeretlen" : $director["birthday"]) : (",<br>" . $this->director_name_pre($director["name"]) . (empty($director["birthday"]) ? "ismeretlen" : $director["birthday"]));  
                    }
                }

                $this->top_100[] = [
                    "title" => $movie["title"],
                    "length" => $movie["runtime"] . " mins",
                    "genres" => implode(", ", $genres),
                    "release_date" => $movie["release_date"],
                    "overview" => $movie["overview"],
                    "poster_url" => $movie["poster_path"],
                    "tmdb_id" => $movie["id"],
                    "tmdb_vote_average" => $movie["vote_average"],
                    "tmdb_vote_count" => $movie["vote_count"],
                    "directors_name" => $director_name,
                    "directors_tmdb_id" => $director_tmdb_id,
                    "directors_biography" => $director_biography,
                    "directors_birth" => $director_birth
                ];
            }
        }
    }
    
    /**
     * Top 100 film adatait tarolo tomb kinyerese
     * 
     * @return array
     */
    public function get_top_hundred_data() :array {
        return $this->top_100;
    }
    
    /**
     * A rendezo nevenek elszeparalasa
     * 
     * @param string $director_name Rendezo neve
     * @return string
     */
    private function director_name_pre(string $director_name) :string {
        return "[" . $director_name . "] - ";
    }
}
