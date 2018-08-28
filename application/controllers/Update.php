<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();


		$this->output->enable_profiler(TRUE);

	}


	public function index() {

		$this->update_all();
		$this->load->view('updated');
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
	}

}
