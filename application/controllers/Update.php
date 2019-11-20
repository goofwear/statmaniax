<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();
	}


	public function index() {

		$this->update_all();
		$this->load->view('updated');
	}

	public function update_all_songs() {

		$songs = $this->data->song_list_db();

		foreach($songs as $song){
			$this->update_song_info($song['id']);
		}
	}

	public function update_song_info($song_id) {

		$this->data->update_song_info($song_id);
	}

	public function update_users() {

		$data['users'] = $this->data->user_list_api();
		$this->data->user_update($data['users']);

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

    public function update_rankings()
    {
        $diffs = Array('basic', 'easy', 'hard', 'wild', 'dual', 'full', 'wildfull');

        foreach ($diffs as $diff) {
            $this->data->ranking_update($diff);
        }
    }

}
