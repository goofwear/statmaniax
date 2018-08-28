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
                $this->load->view('templates/header');
		$this->load->view('user_list', $data);
		$this->load->view('templates/footer');

	}


	public function songs(){

		$data['songs'] = $this->data->song_list_db();
		$this->load->view('template/header');
		$this->load->view('templates/footer');


		#$this->load->view('song_list', $data);
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
