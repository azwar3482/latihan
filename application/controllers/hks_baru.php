<?php
/**
* 
*/
class khs_baru extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
	}

	function beranda()
	{
		$data['title']= 'Dasboard';
		$this->template->load('template', 'dashboard', $data);
	}

	function index()
	{
		$data = array(
			'title' => 'KHS Mahasiswa',
			'loadpertamajs' => 'loaddata('.$this->session->userdata->("keterangan").')',);
		$this->template->load('template', 'khs_baru/khs',$data);
	}
}
?>