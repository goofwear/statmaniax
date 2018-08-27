<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Data extends CI_Model {
    function __construct() {
        // Call the Model constructor
        parent::__construct();
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
