<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();


		$this->output->enable_profiler(TRUE);}


    public function index($diff = "wild")
    {

		#$this->update_all();
        $this->db->where('name', $diff);
        $this->db->where('rank >', 0);
        $this->db->order_by('rank', 'desc');
        $this->db->join('user', 'user.id = ranking.user_id');
        $data['rankings'] = $this->db->get('ranking')->result_array();
        $data['diff'] = $diff;

        $this->load->view('templates/header');
        $this->load->view('ranking_list', $data);
        $this->load->view('templates/footer');
	}
	
	public function generateRankings() {

		$diffs = Array('basic', 'easy', 'hard', 'wild', 'dual', 'full');

		foreach ($diffs as $diff){
			echo $diff;
			$this->data->ranking_update($diff);
			echo "<br>";
		}


	}
	

}
