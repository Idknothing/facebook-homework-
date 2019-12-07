<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup_model extends CI_Model
{
	public $tableName = "user";
	public function __construct()
	{
		parent::__construct();
	}
	public function insert($data = array()){
		return $this->db->insert($this->tableName, $data);//database verilerini oluşturup dönücem buraya
	}
	public function get($data = array()){
		return $this->db->where($data)->get($this->tableName);
	}

}

/* End of file .php */
