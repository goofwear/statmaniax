<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Data extends CI_Model {
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();

    }





    function user_update($user_list){
	foreach ($user_list as $user){

		if(isset($user['picture_path'])){

			$id = $this->db->escape($this->parse_picture_path($user['picture_path']));
			$first = $this->db->escape($user['first_name']);
			$last = $this->db->escape($user['last_name']);
			$username = $this->db->escape($user['username']);
			$score = $this->db->escape($user['total_score']);
			$picture_path = $this->db->escape($user['picture_path']);
			$country = $this->db->escape($user['country']);

			$sql = "INSERT into user (id, username, first, last, total_score, picture_path, country)
				VALUES ($id,$username,$first,$last,$score,$picture_path,$country)
				ON DUPLICATE KEY UPDATE first=$first, last=$last, total_score=$score, picture_path=$picture_path, country=$country";
			$this->db->query($sql);
		}
	}
    }


    function song_list_generate(){

        $data = file_get_contents('https://data.stepmaniax.com/index.php/web/leaderboard/song?difficulty_id=1');
        $json = json_decode($data, true);
        $num_pages = $json['results']['last_page'];
        $current_page = $json['results']['current_page'];

        $song_list = $json['results']['data'];
        while($current_page < $num_pages){
		$next_page = $current_page+1;
                $url = "https://data.stepmaniax.com/index.php/web/leaderboard/song?difficulty_id=1&page=".$next_page;
                $data = file_get_contents($url);
                $json = json_decode($data, true);
                $song_list = array_merge($song_list, $json['results']['data']);
                $current_page = $json['results']['current_page'];
        }


	// now add/update to database
	foreach($song_list as $song){


		$id = $this->db->escape($song['id']);
                $game_song_id = $this->db->escape($song['game_song_id']);
                $title = $this->db->escape($song['title']);
                $subtitle = $this->db->escape($song['subtitle']);
                $artist = $this->db->escape($song['artist']);
                $genre = $this->db->escape($song['genre']);
                $label = $this->db->escape($song['label']);
                $website = $this->db->escape($song['website']);
                $bpm = $this->db->escape($song['bpm']);
                $cover_path = $this->db->escape($song['cover_path']);
                $updated_at = $this->db->escape($song['updated_at']);

		$sql = "INSERT into song (id, game_song_id, title, subtitle, artist, genre, label, website, bpm, cover_path, updated_at)
			VALUES ($id, $game_song_id, $title, $subtitle, $artist, $genre, $label, $website, $bpm, $cover_path, $updated_at)
			ON DUPLICATE KEY UPDATE subtitle=$subtitle, genre=$genre, label=$label, website=$website, bpm=$bpm, cover_path=$cover_path, updated_at=$updated_at";

		$this->db->query($sql);
	}

   }



    function user_list_db(){

	$sql = "SELECT * from user";
	$query = $this->db->query($sql);
	return $query->result_array();

    }


    function song_list_db(){
	$sql = "SELECT * from song";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    function user_list() {

        $data = file_get_contents('https://data.stepmaniax.com/index.php/web/leaderboard/user');
        $json = json_decode($data, true);


	$num_pages = $json['results']['last_page'];
	$current_page = $json['results']['current_page'];

        $user_list = $json['results']['data'];
	while($current_page < $num_pages){
		$data = file_get_contents($json['results']['next_page_url']);
		$json = json_decode($data, true);
		$user_list = array_merge($user_list, $json['results']['data']);
        	$current_page = $json['results']['current_page'];
	}	

	return $user_list;

    }


    function user_info($userid) {

        $data = file_get_contents('https://data.stepmaniax.com/index.php/web/leaderboard/user');
        $json = json_decode($data, true);


        $num_pages = $json['results']['last_page'];
        $current_page = $json['results']['current_page'];

        $user_list = $json['results']['data'];
        while($current_page < $num_pages){
		foreach ($user_list as $user){
			if (isset($user['picture_path'])){
				$id = $this->parse_picture_path($user['picture_path']);
				if ($userid == $id){
					return $user;
				}
			}
		}
                $data = file_get_contents($json['results']['next_page_url']);
                $json = json_decode($data, true);
                $user_list = array_merge($user_list, $json['results']['data']);
                $current_page = $json['results']['current_page'];
        }
	return Array();

    }


    function parse_picture_path($picture_path){
	$parts = explode('/', $picture_path);
	$image = $parts[2];
	$parts = explode('_', $image);
	return $parts[0];

    }

    function user_score_history($userid){
        $data = file_get_contents('https://data.stepmaniax.com/index.php/web/gamer/history/'.$userid);
        $json = json_decode($data, true);

        $num_pages = $json['scores']['last_page'];
        $current_page = $json['scores']['current_page'];


        print_r($json['scores']);

        $score_list = $json['scores']['data'];
        while($current_page < $num_pages){
                $data = file_get_contents($json['scores']['next_page_url']);
                $json = json_decode($data, true);
                $score_list = array_merge($score_list, $json['scores']['data']);
                $current_page = $json['scores']['current_page'];
        }

        return $score_list;

    }

    function user_highscores($userid){
	$data = file_get_contents('https://data.stepmaniax.com/index.php/web/gamer/history/'.$userid);
        $json = json_decode($data, true);

        $num_pages = $json['scores']['last_page'];
        $current_page = $json['scores']['current_page'];


        $score_list = $json['scores']['data'];
	$highscores = Array();
        while($current_page <= $num_pages){
		foreach($score_list as $score) {
			$song_chart_id = $score['song_chart_id'];
			if(isset($highscores[$song_chart_id])){
				if($highscores[$song_chart_id]['score'] < $score['score'])
		                        $highscores[$song_chart_id] = $score;
			} else {
				$highscores[$song_chart_id] = $score;
			}
		}

                $data = file_get_contents($json['scores']['next_page_url']);
                $json = json_decode($data, true);
                $score_list = array_merge($score_list, $json['scores']['data']);
                $current_page = $json['scores']['current_page'];
        }

        return $highscores;

    }

  function user_highscores_title($userid, $diff=4){
        $data = file_get_contents('https://data.stepmaniax.com/index.php/web/gamer/history/'.$userid);
        $json = json_decode($data, true);

        $num_pages = $json['scores']['last_page'];
        $current_page = $json['scores']['current_page'];

	switch($diff){
		case "1":
			$diff = "basic";
			break;
		case "2":
			$diff = "easy";
			break;
		case "3":
			$diff = "hard";
			break;
		case "4":
			$diff = "wild";
			break;
		case "5":
			$diff = "dual";
			break;
		case "6":
			$diff = "full";
			break;
		case "default":
			break;

	}


        $score_list = $json['scores']['data'];
        $highscores = Array();
        while($current_page <= $num_pages){
                foreach($score_list as $score) {
                        $song_title = $score['title'];
			$song_artist = $score['artist'];
			$song_diff = $score['name'];
			if ($song_diff == $diff){
				if(isset($highscores[$song_title.$song_artist])){
					if($highscores[$song_title.$song_artist]['score'] < $score['score'])
						$highscores[$song_title.$song_artist] = $score;
				} else {
					$highscores[$song_title.$song_artist] = $score;
				}
			}
                }

		if($current_page == $num_pages)
			break;

                $data = file_get_contents($json['scores']['next_page_url']);
                $json = json_decode($data, true);
                $score_list = array_merge($score_list, $json['scores']['data']);
                $current_page = $json['scores']['current_page'];
        }

        return $highscores;

    }


    function leaderboard($diff){



	$data = file_get_contents('https://data.stepmaniax.com/index.php/web/leaderboard/song?&search=&difficulty_id='.$diff);
        $json = json_decode($data, true);

        $num_pages = $json['results']['last_page'];
        $current_page = $json['results']['current_page'];


        $score_list = $json['results']['data'];
        $highscores = Array();
        while($current_page <= $num_pages){
		foreach($score_list as $score){
                        $song_chart_id = $score['game_song_id'];
			$highscores[$song_chart_id] = $score['top_scores'][0];

		}
		$next_page = $current_page+1;
		$url = "https://data.stepmaniax.com/index.php/web/leaderboard/song?&search=&difficulty_id=".$diff."&page=".$next_page;
                $data = file_get_contents($url);
                $json = json_decode($data, true);
        	$score_list = $json['results']['data'];
                $current_page = $json['results']['current_page'];
        }
        return $highscores;

    }
    function leaderboard_title($diff){

        $data = file_get_contents('https://data.stepmaniax.com/index.php/web/leaderboard/song?&search=&difficulty_id='.$diff);
        $json = json_decode($data, true);


        $num_pages = $json['results']['last_page'];
        $current_page = $json['results']['current_page'];


        $score_list = $json['results']['data'];
        $highscores = Array();
        while($current_page <= $num_pages){
                foreach($score_list as $score){
                        $song_title = $score['title'];
                        $song_artist = $score['artist'];
			if (!empty($score['top_scores']))
				#echo "<pre>";
				#print_r($score['top_scores']);
                        	$highscores[$song_title.$song_artist] = $score['top_scores'][0];

                }
                $next_page = $current_page+1;
                $url = "https://data.stepmaniax.com/index.php/web/leaderboard/song?&search=&difficulty_id=".$diff."&page=".$next_page;
                $data = file_get_contents($url);
                $json = json_decode($data, true);
        	$score_list = $json['results']['data'];
                $current_page = $json['results']['current_page'];
        }
        return $highscores;

    }

}
