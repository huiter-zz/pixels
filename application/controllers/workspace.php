<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 工作区 
 */
class Workspace extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}

	public function index()
	{
		
		$this->template['css'] = $this->load->view('workspace/workspace_canvas_css',$this->template,TRUE);
		$this->template['content'] = $this->load->view('workspace/workspace_canvas_content',$this->template,TRUE);
		$this->template['js'] = $this->load->view('workspace/workspace_canvas_js',$this->template,TRUE);
		
		$this->load->view('template_view',$this->template);
	}
}

/* End of file workspace.php */
/* Location: ./application/controllers/workspace.php */