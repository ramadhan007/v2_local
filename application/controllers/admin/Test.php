<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

	function __construct(){
		
	}
	
	function index(){
		$date = date('l jS \of F Y h:i:s A');
		echo $date;
		// $this->session->set_userdata('jam_sekarang', $date);
		$_SESSION['jam_sekarang'] = $date;
	}
	
	function readdata(){
		// echo $this->session->userdata('jam_sekarang');
		echo "Jam Sekarang: ".$_SESSION['jam_sekarang'];
	}
}

?>