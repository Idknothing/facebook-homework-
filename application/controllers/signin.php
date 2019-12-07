<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signin extends CI_Controller {

	public function __construct()
	{

		parent::__construct();
	}

	public function index()
	{

		$viewData= new stdClass();
		$viewData->title = "Facebook - Giriş Yap veya Kaydol";
		$this->load->view('signin/index', $viewData);
	}

}

/* End of file Controllername.php */
