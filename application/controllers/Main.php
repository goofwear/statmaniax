<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->model('data');


		#$this->output->cache(20);
		#$data['users'] = $this->data->user_list();


		// Pull from DB isntead
		$data['users'] = $this->data0->user_list_db();
		$this->load->view("user_list", $data);
	}


	public function songs(){

                $this->load->model('data');


		$data['songs'] = $this->data->song_list_db();
		$this->load->view('template/header');

		#$this->load->view('song_list', $data);
	}

	public function update(){

                $this->load->model('data');
                $data['users'] = $this->data->user_list();

		$this->data->user_update($data['users']);

	}

	public function update_songs(){

		$this->load->model('data');

		$this->data->song_list_generate();
	}
	public function scores($userid, $diff='wild'){

		/**
		Tyep:
			Full 6
			Dual 5
			Wild 4
			Hard 3
			East 2
			Basic 1
			
	
		*/
		if (!is_numeric($diff)){
			switch($diff){
				case "full";
					$diff = 6;
					break;
				case "dual";
					$diff = 5;
					break;
				case "wild";
					$diff = 4;
					break;
				case "hard";
					$diff = 3;
					break;
				case "easy";
					$diff = 2;
				case "basic":
					$diff = 1;
				default:
					$diff = 4;
					break;
			}
		}


		$this->output->cache(30);
		$this->load->model('data');
		$data['user_scores']= $this->data->user_highscores_title($userid, $diff);
		$data['world_scores'] = $this->data->leaderboard_title($diff);
                $data['user_info'] = $this->data->user_info($userid);
		$this->load->view('templates/header');
		$this->load->view("user_score", $data);

	}

}
