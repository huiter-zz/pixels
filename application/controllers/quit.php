<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quit extends Pixel_Controller {


	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->helper('url');
		//退出销毁session
		$this->session->sess_destroy();
	    redirect(base_url(("")));
	}
}


/* End of quit.php */
/* Location: ./application/controllers/quit.php */