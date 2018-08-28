<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();


		$this->output->enable_profiler(TRUE);

	}




	public function index()
	{

		// Pull from DB isntead
		$data['users'] = $this->data->user_list_db();
		$this->load->view("user_list", $data);
	}


	public function songs(){

		$data['songs'] = $this->data->song_list_db();
		$this->load->view('template/header');

		#$this->load->view('song_list', $data);
	}

	public function update_users(){

                $data['users'] = $this->data->user_list_api();

		$this->data->user_update($data['users']);

	}


	public function update_scores($userid){

                $user_scores= $this->data->user_score_history_api($userid);
		$this->data->user_score_history_update($user_scores);
	}

	public function update_songs(){


		$this->data->song_list_generate();
	}

	public function update_leaderboard(){

		$this->data->leaderboard_update();
	}

	public function update_all(){


		$this->update_songs();
		$this->update_users();
		
		$userlist = $this->data->user_list_db();
		foreach ($userlist as $user){
			$this->update_scores($user['id']);
		};
	}

	public function scores($userid, $diff='wild'){


		$diff = $this->data->diff_convert($diff);

		$data['user_scores']= $this->data->user_highscores_title_db($userid, $diff);
		$data['world_scores'] = $this->data->leaderboard_title_db($diff);
                $data['user_info'] = $this->data->user_info_db($userid);
		
		$this->load->view('templates/header');
		$this->load->view("user_score", $data);

	}

}
