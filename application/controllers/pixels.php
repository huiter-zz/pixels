<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 工作区 
 */
class Pixels extends CI_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}

	public function index()
	{
		$this->load->helper('url');
		if($this->session->userdata('userdata'))
		redirect(base_url('/workspace'));
		else
		$this->load->view('pixels/pixels_view');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */