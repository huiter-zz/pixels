<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 引用流程接入口 
 */
class About extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}
	
	public function index()
	{
		$this->template['css'] = $this->load->view('about/about_css',$this->template,TRUE);
		$this->template['content'] = $this->load->view('about/about_content',$this->template,TRUE);
		$this->template['js'] = $this->load->view('about/about_js',$this->template,TRUE);
		$this->load->view('template_view',$this->template);
	}
}
/* End of file about.php */
/* Location: ./application/controllers/about.php */