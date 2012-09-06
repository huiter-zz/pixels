<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 工作区 
 */
class ThreeD extends CI_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}

	public function index()
	{
		$this->load->view('3D/3D_view');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */