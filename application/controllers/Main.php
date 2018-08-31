<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();


        #$this->output->enable_profiler(TRUE);

	}


	public function index() {
		$this->load->view('templates/header');
		$this->load->view('home');
		$this->load->view('templates/footer');
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

		$data['diff'] = $diff;
		$data['songid'] = $songid;
		$diff = $this->data->diff_convert($diff);
		$data['song'] = $this->data->song_info_db($songid);
		$data['scores'] = $this->data->song_highscores_db($data['song'], $diff);
                $data['score_history'] = $this->data->song_score_history_db($data['song'], $diff);

        $this->load->view('templates/header');
		$this->load->view('song', $data);
 		$this->load->view('templates/footer');

	}

	public function scores($userid, $diff=Null){

		if(empty($diff)){
			redirect("/player/$userid/wild");
		}	

		$data['diff'] = $diff;
		$data['userid'] = $userid;
		$diff = $this->data->diff_convert($diff);

		$data['user_stats'] = $this->data->user_stats_db($userid, $diff);
		$data['user_scores']= $this->data->user_highscores_title_db($userid, $diff);
		$data['world_scores'] = $this->data->leaderboard_title_db($diff);
                $data['user_info'] = $this->data->user_info_db($userid);
		
		$this->load->view('templates/header');
		$this->load->view("user_score", $data);
		$this->load->view('templates/footer');

	}

	public function rival($userid, $rivalid, $diff=Null) {

		if(empty($diff))
			redirect("/player/$userid/compare/$rivalid/wild");

		$data['diff'] = $diff;
		$data['userid'] = $userid;
		$data['rivalid'] = $rivalid;
		$diff = $this->data->diff_convert($diff);

		$data['user_stats'] = $this->data->user_stats_db($userid, $diff);
		$data['user_scores']= $this->data->user_highscores_title_db($userid, $diff);
		$data['user_info'] = $this->data->user_info_db($userid);

		if ($rivalid == "world"){
			$data['rival_scores'] = $this->data->leaderboard_title_db($diff);
		} else {
			$data['rival_scores'] = $this->data->user_highscores_title_db($rivalid, $diff);
		}

		$this->load->view('templates/header');
		$this->load->view("user_score", $data);
		$this->load->view('templates/footer');

	}

    public function search()
    {
        if (isset($_POST['search'])) {
            $this->db->like('username', $_POST['query']);
            $data['results'] = $this->db->get('user')->result_array();

            $this->load->view('templates/header');
            $this->load->view("search/results", $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header');
            $this->load->view("search/prompt");
            $this->load->view('templates/footer');
        }
    }

    public function get_diff_by_details($title, $artist, $diff)
    {
        $this->db->where('title', $title);
        $this->db->where('artist', $artist);
        $song = $this->db->get('song')->result_array()[0];
        $lvl = 0;
        switch ($diff) {
            case "basic":
                $lvl = $song['basic'];
                break;
            case "easy":
                $lvl = $song['easy'];
                break;
            case "hard":
                $lvl = $song['basic'];
                break;
            case "wild":
                $lvl = $song['basic'];
                break;
            case "basic":
                $lvl = $song['basic'];
                break;
            case "basic":
                $lvl = $song['basic'];
                break;
            case "basic":
                $lvl = $song['basic'];
                break;
        }

    }

}
