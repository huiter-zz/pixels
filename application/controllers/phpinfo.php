<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 引用流程接入口 
 */
class phpinfo extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}
	
	public function index()
	{
		echo phpinfo();
	}
}
/* End of file about.php */
/* Location: ./application/controllers/about.php */