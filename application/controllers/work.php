<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 工作区 
 */
class Work extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}

	public function index()
	{
		
		$this->template['content'] = "<h1>本部分还在开发中。</h1>";
		
		
		$this->load->view('template_view',$this->template);
	}
}

/* End of file workspace.php */
/* Location: ./application/controllers/workspace.php */