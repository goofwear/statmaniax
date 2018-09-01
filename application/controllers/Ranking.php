<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();


		$this->output->enable_profiler(TRUE);}


	public function index() {

		#$this->update_all();
        $this->db->group_by('user_id');
        $data['rankings'] = $this->db->get('ranking')->result_array();

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
