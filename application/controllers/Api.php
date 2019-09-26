<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();
		$this->load->model('api_model');
//		$this->output->enable_profiler(TRUE);

	}


	public function index() {
		#$this->load->view('updated');
	}


	public function get_user_highscore($userid, $diff='wild'){
		$data['scores'] = $this->api_model->getUserHighScores($userid, $diff);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	public function get_region_highscores($region='all', $diff='wild'){
		$data['scores'] = $this->api_model->getRegionHighScores($region, $diff);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	public function user_score_history($id, $first=0, $last=100){
		$data['scores'] = $this->api_model->user_scores($id, 'none',$first, $last);
		echo json_encode($data, JSON_PRETTY_PRINT);

	}

	public function songs($id=Null) {
		if (isset($id)){
			$data['songs'] = $this->api_model->song_list($id);
		} else {
			$data['songs'] = $this->api_model->song_list();
		}
		
		echo json_encode($data, JSON_PRETTY_PRINT);
		
	}

	public function rank($diff, $first=0, $last=100){
		$data['rank'] = $this->api_model->user_rank($diff, $first, $last);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	public function score($id=Null){
		$data['scores'] = $this->api_model->song_score($id);
		echo json_encode($data, JSON_PRETTY_PRINT);


	}

	public function users($id=Null, $first=0, $last=100) {
		$data['users'] = $this->api_model->user_list($id, $first, $last);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}



	public function highscores($diff='wild', $first=0, $last=300){
		$data['scores'] = $this->api_model->highscores($diff, $first, $last);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	public function song_highscores($songid){
		$data['scores'] = $this->api_model->song_highscores($songid);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	public function user_highscores($userid, $diff='wild'){
		$data['scores'] = $this->api_model->user_highscores_all($userid, $diff);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}


	public function song_scorehistory($songid, $diff='wild', $first=0, $last=100){
		$data['scores'] = $this->api_model->song_scorehistory($songid, $diff, $first, $last);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}

	public function user_scores($id, $diff='wild', $first=0, $last=100) {
		$data['user'] = $this->api_model->user_list($id, $first, $last);
		$data['scores'] = $this->api_model->user_scores($id, $diff, $first, $last);
		echo json_encode($data, JSON_PRETTY_PRINT);
	}


	public function getScores($song_title, $song_artist, $diff = 4) {
		$song['title'] = $song_title;
		$song['artist'] = $song_artist;
		$data['scores'] = $this->data->song_score_history_db($song, $diff);
		echo json_encode($data);
	}

	public function update_scores($userid) {
		$user_scores= $this->data->user_score_history_api($userid);
		$this->data->user_score_history_update($user_scores);
	}

	public function update_songs() {
		$this->data->song_list_generate();
	}

	public function update_leaderboard() {
		$this->data->leaderboard_update();
	}

	public function update_all() {
		$this->update_users();
		$this->update_leaderboard();
		$this->update_songs();
		
		$userlist = $this->data->user_list_db();
		foreach ($userlist as $user){
			$this->update_scores($user['id']);
		};

		$this->update_rankings();
	}

    public function update_rankings() {
        $diffs = Array('basic', 'easy', 'hard', 'wild', 'dual', 'full', 'wildfull');

        foreach ($diffs as $diff) {
            $this->data->ranking_update($diff);
        }
    }

}
