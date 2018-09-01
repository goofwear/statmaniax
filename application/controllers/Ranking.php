<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ranking extends CI_Controller {

	function __construct() {
        	// Call the Model constructor
        	parent::__construct();


		$this->output->enable_profiler(TRUE);}


	public function index() {

		#$this->update_all();
		$this->load->view('updated');
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
