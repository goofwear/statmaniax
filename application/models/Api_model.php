<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api_model extends CI_Model {
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();

    }

    function user_list($id, $first, $last){

	if(is_numeric($id))
		$this->db->where('id', $id);
	else
		$this->db->order_by('username', 'asc');
	$this->db->limit($last,$first);
	$query = $this->db->get('user');
	return $query->result_array();

    }


    function song_list($id = null)
    {
        if (isset($id)) {
            $this->db->where('id', $id);

        }
        $query = $this->db->get('song');
        return $query->result_array();
    }


    function user_rank($diff, $first, $last){
	$this->db->where('name', $diff);
	$this->db->join('user', 'on user.id = ranking.user_id');
	$this->db->order_by('rank', 'desc');
	$this->db->limit($last,$first);
	$query = $this->db->get('ranking');
	return $query->result_array();
    }

    function user_scores($userid, $diff, $first, $last){
	if(isset($userid)){
		$this->db->where('gamer_id', $userid);
		$this->db->where('name', $diff);
		$this->db->join('song', 'on song.id = score.song_id');
		$this->db->order_by('created_at', 'desc');
		$this->db->limit($last,$first);
		$query = $this->db->get('score');
		return $query->result_array();
	}

    }

    function user_highscores_all($userid, $diff){
	$song_list = $this->song_list();
	
	$this->db->where('', $diff);
        $this->db->order_by('song.title');
        $this->db->join('song', 'on song.id = score.song_id');
        $this->db->join('user', 'on user.id = score.gamer_id');
        $this->db->select('user.id as user_id, user.username, song_id, score, score.name, song.title, song.artist');
        $query = $this->db->get('score');
        $scores = $query->result_array();
	print_r($scores);

/**	$highscores = Array();
	foreach ($song_list as $song){
		$song_id = $song['id'];
		if(isset($highscores[$song_id])){

		}


	}
*/
    }


    function song_score($id){
    	$this->db->where('score.id', $id);
        $this->db->join('song', 'on song.id = score.song_id');
	$this->db->join('user', 'on user.id = score.gamer_id');
	$this->db->orderby('timestamp', 'desc');
        $query = $this->db->get('score');
        return $query->result_array();

    }

    function highscores($diff, $first, $last){

	$this->db->where('name', $diff);
	$this->db->order_by('song.title');
	$this->db->join('song', 'on song.id = score.song_id');
        $this->db->join('user', 'on user.id = score.gamer_id');
	$this->db->select('user.id as user_id, user.username, song_id, score, '.$diff.',song.title, song.artist');
	$query = $this->db->get('score');
        $scores = $query->result_array();
      
	$highscores = Array();
        foreach ($scores as $score){
		$song_id = $score['song_id'];
                if(isset($highscores[$song_id])){
                        if($highscores[$song_id]['score'] < $score['score'])
                                $highscores[$song_id] = $score;
                } else {
                        $highscores[$song_id] = $score;
                }
        }
        return $highscores;
    }


    function song_scorehistory($id, $diff, $first, $last){
	$this->db->order_by('date', 'desc');
	$this->db->where('song.id', $id);
	$this->db->where('score.name', $diff);
	$this->db->join('song', 'on song.id = score.song_id');
	$this->db->join('user', 'on user.id = score.gamer_id');
        $this->db->limit($last,$first);
	$this->db->select('score.id as id, song_id, gamer_id, user.username, user.picture_path,
                        score, grade, perfect1, perfect2, early, late, misses, flags, green, yellow, red,
                        score.name as diff, '.$diff.' as level, created_at as date');
	$query = $this->db->get('score');
	$scores = $query->result_array();

	return $scores;
    }


    function song_highscores($id) {
	$this->db->where('song.id', $id);
	$this->db->join('song', 'on song.id = score.song_id');
        $this->db->join('user', 'on user.id = score.gamer_id');
        $query = $this->db->get('score');
        $scores = $query->result_array();

	$highscores = Array();
        foreach ($scores as $score){
                $diff = $score['name'];
                if(isset($highscores[$diff])){
                        if($highscores[$diff]['score'] < $score['score'])
                                $highscores[$diff] = $score;
                } else {
                        $highscores[$diff] = $score;
                }
        }
        return $highscores;
    }


    function user_highscores($id, $diff) {
	$this->db->where('score.name', $diff);
        $this->db->where('score.gamer_id', $id);
        $this->db->join('song', 'on song.id = score.song_id', 'left');
        $this->db->join('user', 'on user.id = score.gamer_id');
        $this->db->select('score.id as id, user.id as user_id, user.username, song_id, song.title, song.artist,
			score, grade, perfect1, perfect2, early, late, misses, flags, green, yellow, red,
			score.name as diff, '.$diff.' as level, created_at as date');
        $query = $this->db->get('score');
        $scores = $query->result_array();

	$highscores = Array();
        foreach ($scores as $score){
                $song_id = $score['song_id'];
                if(isset($highscores[$song_id])){
                        if($highscores[$song_id]['score'] < $score['score'])
                                $highscores[$song_id] = $score;
                } else {
                        $highscores[$song_id] = $score;
                }
        }
        return $highscores;
    }

    function user_stats_db($userid, $diff=Null){
	if (isset($diff)) {
		if(is_numeric($diff))
			$diff = $this->diff_convert($diff);
		$userid = $this->db->escape($userid);
                $diff = $this->db->escape($diff);
		$sql = "SELECT grade, count(*) as count FROM `score` WHERE gamer_id=$userid and name = $diff group by grade";
	} else {
		$sql = "SELECT grade, count(*) as count FROM score WHERE gamer_id=$userid group by grade";
	}

	$query = $this->db->query($sql);
	return json_encode($query->result_array());

    }

    function user_stars_unique_db($userid, $diff=Null){
	$userid = $this->db->escape($userid);
	if(isset($diff)) {
		if(is_numeric($diff))
			$diff = $this->diff_convert($diff);
		$diff = $this->db->escape($diff);
		$sql =  "select grade, count(*) as count from (select title, artist, grade, name, count(*) as count FROM `score`
                        WHERE gamer_id=$userid and name=$diff  group by grade, title, artist, name) results
                        group by grade";
	} 	else {
		$sql = "select grade, count(*) as count from (select title, artist, grade, name, count(*) as count FROM `score`
			WHERE gamer_id=$userid group by grade, title, artist, name) results
			group by grade";
	}
	$query = $this->db->query($sql);
	return json_encode($query->result_array());

    }

    function user_world_records($userid){
	$userid = $this->db->escape($userid);

	$sql = "select title, artist, name, score from score where gamer_id=$userid";
	$query = $this->db->query($sql);
	$highscores = $query->result_array();

	$sql = "select * from leaderboard";
	$query = $this->db->query($sql);
        $world = $query->result_array();

	$leaderboard = Array();
	foreach ($world as $score)
		$leaderboard[$score['id'].$score['diff']] = $score;

	$user_scores = Array();
        foreach($highscores as $score){
		$title = $score['title'];
		$artist = $score['artist'];
		$diff = $this->diff_convert($score['name']);
		if (isset($user_scores[$title.$artist.$diff])){
			if($user_scores[$title.$artist.$diff]['score'] < $score['score'])
				$user_scores[$title.$artist.$diff] = $score;
		} else {
			$user_scores[$title.$artist.$diff] = $score;
		}
	}

	$wr=0;
	foreach($user_scores as $score){
		$diff = $this->diff_convert($score['name']);
		if($leaderboard[$score['title'].$score['artist'].$diff]['score'] <= $score['score'])
			$wr+=1;

	}
	return $wr;

    }

    function song_info_db($songid){
	$query = $this->db->get_where('song', array('id' => $songid));
	return json_encode($query->row_array());

    }

    function song_scores_by_title_db($title, $artist){
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

    function song_scores_by_id_db($song_id){
        $id = $this->db->escape($song_id);
        $sql = "select * from score
                inner join song
                on song.id = score.song_id
                where score.id==$song_id
                order by score desc";

        $query = $this->db->query($sql);
        return json_encode($query->result_array());


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
	#$sql = "SELECT user.username, user.picture_path, `grade`, `perfect1`, `perfect2`, `early`, `late`, `misses`, `flags`, `green`, `yellow`, `red`, gamer_id, max(score) AS score, created_at FROM score 
	#	INNER JOIN user ON
	#	user.id = score.gamer_id
	#	WHERE title=$title AND artist=$artist AND name=$diff GROUP BY gamer_id  
	#	ORDER BY `score`  DESC";


	$query = $this->db->query($sql);
	$scores =  $query->result_array();
	$highscores = Array();
	foreach($scores as $score){
		if(isset($highscores[$score['gamer_id']])){
			if($highscores[$score['gamer_id']]['created_at'] < $score['created_at'])
				$highscore[$score['gamer_id']] = $score;
		} else
			$highscores[$score['gamer_id']] = $score;

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

    function most_played_songs(){
	$sql = "SELECT count(*) as count, score.title, score.artist, score.cover_path, song.website, song.game_song_id, song.genre, song.label, song.bpm FROM `score` 
		inner join song on 
		song.artist = score.artist 
		and
		song.title = score.title
		group by `title`,`artist`
		order by count desc";
	$query = $this->db->query($sql);
	return $query->result_array();
    }


}
