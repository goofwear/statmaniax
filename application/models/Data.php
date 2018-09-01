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

	$this->db->order_by('total_score', 'desc');
	$query = $this->db->get('user');
	return $query->result_array();

    }


    function song_list_db(){
	$sql = "SELECT * from song";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function user_info_db($userid){
	if(is_numeric($userid)){
		$this->db->escape($userid);
		$sql = "SELECT * from user where id = $userid";
		$query = $this->db->query($sql);
		return $query->row_array();
	}
    }

    function user_stats_db($userid, $diff=Null){
	if(isset($diff)) {
		if(is_numeric($diff))
			$diff = $this->diff_convert($diff);
		$userid = $this->db->escape($userid);
                $diff = $this->db->escape($diff);
		$sql = "SELECT grade, count(*) as count FROM `score` WHERE gamer_id=$userid and name = $diff group by grade";
	} else {
		$sql = "SELECT grade, count(*) as count FROM score WHERE gamer_id=$userid group by grade";
	}

	$query = $this->db->query($sql);
	return $query->result_array();

    }

    function song_info_db($songid){
	$query = $this->db->get_where('song', array('game_song_id' => $songid));
	return $query->row_array();

    }

    function song_scores_db($title, $artist){
	$title = $this->db->escape($title);
        $artist = $this->db->escape($artist);
	$sql = "select * from score
		inner join user
		on user.id = score.id
		where score.title=$title and score.artist=$artist
		order by score desc";

	$query = $this->db->query($sql);
	return $query->result_array();


    }

    function user_list_api() {

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


    function user_info_api($userid) {

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


    function user_score_history_api($userid){
        $data = file_get_contents('https://data.stepmaniax.com/index.php/web/gamer/history/'.$userid);
        $json = json_decode($data, true);

        $num_pages = $json['scores']['last_page'];
        $current_page = $json['scores']['current_page'];

        $score_list = $json['scores']['data'];
        while($current_page < $num_pages){
                $data = file_get_contents($json['scores']['next_page_url']);
                $json = json_decode($data, true);
                $score_list = array_merge($score_list, $json['scores']['data']);
                $current_page = $json['scores']['current_page'];
        }

        return $score_list;

    }


    function user_score_history_update($data){

	foreach($data as $score){


		$id = $this->db->escape($score['id']);
                $gamer_id = $this->db->escape($score['gamer_id']);
                $song_chart_id = $this->db->escape($score['song_chart_id']);
                $score_points = $this->db->escape($score['score']);
                $machine_serial = $this->db->escape($score['machine_serial']);
                $grade = $this->db->escape($score['grade']);
                $calories = $this->db->escape($score['calories']);
                $perfect1 = $this->db->escape($score['perfect1']);
                $perfect2 = $this->db->escape($score['perfect2']);
                $early = $this->db->escape($score['early']);
                $late = $this->db->escape($score['late']);
                $misses = $this->db->escape($score['misses']);
                $flags = $this->db->escape($score['flags']);
                $green = $this->db->escape($score['green']);
                $yellow = $this->db->escape($score['yellow']);
                $red = $this->db->escape($score['red']);
                $created_at = $this->db->escape($score['created_at']);
                $title = $this->db->escape($score['title']);
                $artist = $this->db->escape($score['artist']);
                $cover_path = $this->db->escape($score['cover_path']);
                $name = $this->db->escape($score['name']);
                $uuid = $this->db->escape($score['uuid']);

		$sql = "INSERT into score (id,gamer_id,song_chart_id,score,machine_serial,grade,calories,perfect1,perfect2,early,late,misses,flags,green,yellow,red,created_at,title,artist,cover_path,name,uuid)
			VALUES($id,$gamer_id,$song_chart_id,$score_points,$machine_serial,$grade,$calories,$perfect1,$perfect2,$early,$late,$misses,$flags,$green,$yellow,$red,$created_at,$title,$artist,$cover_path,$name,$uuid)
			ON DUPLICATE KEY UPDATE artist=$artist,title=$title,created_at=$created_at";
		$this->db->query($sql);
	}




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

	$diff = $this->diff_convert($diff);


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


    function user_highscores_title_db($userid, $diff=4){

        $diff = $this->diff_convert($diff);

	$userid = $this->db->escape($userid);
        $diff = $this->db->escape($diff);

        $sql = "select * from score
		inner join song on score.title=song.title and score.artist=song.artist
		where gamer_id=$userid and name=$diff";
	$query = $this->db->query($sql);
	$scores = $query->result_array();
        $highscores = Array();
	foreach ($scores as $score){
		$song_title = $score['title'];
 		$song_artist = $score['artist'];
 		if(isset($highscores[$song_title.$song_artist])){
			if($highscores[$song_title.$song_artist]['score'] < $score['score'])
				$highscores[$song_title.$song_artist] = $score;
		} else {
			$highscores[$song_title.$song_artist] = $score;
		}
	}

	return $highscores;
    }

    function get_song_id_by_title($title)
    {
        $this->db->select('game_song_id');
        $this->db->where('title', $title);
        $out = $this->db->get('song')->result_array()[0];
        return $out['game_song_id'];
    }

    function song_score_history_db($song, $diff = 4) {
        $diff = $this->diff_convert($diff);

        $this->db->where('title', $song['title']);
        $this->db->where('artist', $song['artist']);
        $this->db->where('name', $diff);
        $this->db->join('user', 'user.id = score.gamer_id');
	$this->db->order_by('created_at', 'DESC');
        return $this->db->get('score')->result_array();
    }


    function song_highscores_db($song, $diff){
        $diff = $this->diff_convert($diff);

	$artist = $this->db->escape($song['artist']);
	$title = $this->db->escape($song['title']);
	$diff =  $this->db->escape($diff);

	$sql = "SELECT * FROM score
		INNER JOIN
		(SELECT gamer_id, max(score) AS score FROM score WHERE title=$title AND artist=$artist AND name=$diff GROUP BY gamer_id) maxscore
		ON (score.gamer_id = maxscore.gamer_id and score.score = maxscore.score)
		INNER JOIN user
		ON score.gamer_id = user.id
		WHERE title=$title AND artist=$artist AND name=$diff
		ORDER BY score.score DESC";

	$query = $this->db->query($sql);
	return $query->result_array();

    }


    function leaderboard_title_api($diff){

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

    function leaderboard_title_db($diff){
	$diff = $this->db->escape($diff);
	$sql = "select l.id as id, l.score as score, u.id as user_id, u.username as username 
		from leaderboard as l
		left join user u 
		on u.id=l.user_id
		where diff=$diff";
	#$sql = "select * from leaderboard where diff=$diff";
	$query = $this->db->query($sql);

	$leaderboard = $query->result_array();
        $highscores = Array();
	foreach($leaderboard as $score){
		$key = $score['id'];
		$highscores[$key] = $score;
	}

	return $highscores;

    }

    function leaderboard_update(){

	for ( $diff=6; $diff>0; $diff--){

		$highscores = $this->leaderboard_title_api($diff);

		foreach($highscores as $key=>$score){
                    if(isset($score['picture_path']))
			$userid = $this->db->escape($this->parse_picture_path($score['picture_path']));
		    else
			$userid = NULL;

			$userid = $this->db->escape($userid);
			$diff = $this->db->escape($diff);
			$key = $this->db->escape($key);
			$score_points = $this->db->escape($score['score']);

			$sql = "INSERT into leaderboard (id, user_id, score, diff) VALUES ($key, $userid, $score_points, $diff)
				ON DUPLICATE KEY UPDATE user_id=$userid, score=$score_points, diff=$diff";
			$this->db->query($sql);
		}
	}// end for diff
    }


    function ranking_update($diff) {
	$users = $this->user_list_db();

	foreach ($users as $user){
		$userid = $this->db->escape($user['id']);
		$diff_str = $this->db->escape($diff);

		$sql = "SELECT gamer_id, basic, easy, hard, wild, `dual`, full, max(score) AS score from score
			inner join song 
			on song.title = score.title and song.artist = score.artist
			where name=$diff_str and gamer_id=$userid GROUP BY song_chart_id";
		$query = $this->db->query($sql);
		$scores = $query->result_array();
	
		echo "<pre>";

		$rank = 0;
		$weight = 1;
		// Ranking Algorigm is simple right now, just take the level of the chart and multiple by the difficulty
		// this makes it so the harder songs are weighted more. maybe this is too aggresive though. Only time will tell!

		foreach($scores as $score ){
			$diff = strtolower($diff);
			$rank+= ($score[$diff]*($weight))*$score['score'];
		}

		$rank = $this->db->escape($rank);
		$sql = "INSERT into ranking (`user_id`, `rank`, `name`) VALUES 
			($userid, $rank, $diff_str) ON DUPLICATE KEY UPDATE `rank`=$rank, `updated_at`=NOW()";
		$this->db->query($sql);
	}


    }


    function parse_picture_path($picture_path){
        $parts = explode('/', $picture_path);
        $image = $parts[2];
        $parts = explode('_', $image);
        return $parts[0];

    }

    function diff_convert($diff){

	if(is_numeric($diff)){
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
	} else {
	    switch($diff){
                case "basic":
                        $diff = "1";
                        break;
                case "easy":
                        $diff = "2";
                        break;
                case "hard":
                        $diff = "3";
                        break;
                case "wild":
                        $diff = "4";
                        break;
                case "dual":
                        $diff = "5";
                        break;
                case "full":
                        $diff = "6";
                        break;
                case "default":
                        break;

            }

	}

	return $diff;

     }

    function gradetostars($stars)
    {
        $out = "";
        if (is_numeric($stars)) {
            switch ($stars) {
                case 6:
                    $out = "https://www.stepmaniax.com/img/grades/1.png";
                    break;
                case 5:
                    $out = "https://www.stepmaniax.com/img/grades/2.png";
                    break;
                case 4:
                    $out = "https://www.stepmaniax.com/img/grades/3.png";
                    break;
                case 3:
                    $out = "https://www.stepmaniax.com/img/grades/4.png";
                    break;
                case 2:
                    $out = "https://www.stepmaniax.com/img/grades/5.png";
                    break;
                case 1:
                    $out = "https://www.stepmaniax.com/img/grades/6.png";
                    break;
                case 0:
                    $out = "https://www.stepmaniax.com/img/grades/7.png";
                    break;
            }
        } else {
            switch ($stars) {
                case "6":
                    $out = "https://www.stepmaniax.com/img/grades/1.png";
                    break;
                case "5":
                    $out = "https://www.stepmaniax.com/img/grades/2.png";
                    break;
                case "4":
                    $out = "https://www.stepmaniax.com/img/grades/3.png";
                    break;
                case "3":
                    $out = "https://www.stepmaniax.com/img/grades/4.png";
                    break;
                case "2":
                    $out = "https://www.stepmaniax.com/img/grades/5.png";
                    break;
                case "1":
                    $out = "https://www.stepmaniax.com/img/grades/6.png";
                    break;
                case "0":
                    $out = "https://www.stepmaniax.com/img/grades/7.png";
                    break;
            }
        }

        return $out;
    }

    function getRank($user, $type)
    {
        $this->db->select('rank');
        $this->db->where('user_id', $user);
        $this->db->where('name', $type);

        return $this->db->get('ranking')->result_array()[0]['rank'];

    }

    function get_ranking_data($diff)
    {
        $this->db->where('name', $diff);
        $this->db->where('rank >', 0);
        $this->db->order_by('rank', 'desc');
        $this->db->join('user', 'user.id = ranking.user_id');
        return $this->db->get('ranking')->result_array();
    }
}
