<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();


		$this->output->enable_profiler(TRUE);

	}


	public function index() {
		$this->users();
	}

	public function users() {

		// Pull from DB isntead
		$data['users'] = $this->data->user_list_db();
                $this->load->view('templates/header');
		$this->load->view('user_list', $data);
		$this->load->view('templates/footer');

	}


	public function songs(){

		$data['songs'] = $this->data->song_list_db();
		$this->load->view('templates/header');
		$this->load->view('song_list', $data);
		$this->load->view('templates/footer');
	}

	public function song($songid, $diff='wild'){

		$diff = $this->data->diff_convert($diff);
		$data['song'] = $this->data->song_info_db($songid);
		
		$this->load->view('templates/header');
		$this->load->view('song', $data);
 		$this->load->view('templates/footer');

	}

	public function scores($userid, $diff='wild'){


		$diff = $this->data->diff_convert($diff);

		$data['user_scores']= $this->data->user_highscores_title_db($userid, $diff);
		$data['world_scores'] = $this->data->leaderboard_title_db($diff);
                $data['user_info'] = $this->data->user_info_db($userid);
		
		$this->load->view('templates/header');
		$this->load->view("user_score", $data);
		$this->load->view('templates/footer');

	}

}
