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
		$workid = $this->input->get('id');
		if($workid)
		{
			$this->curl->create(base_url("/api/v1/work/?workid=".$workid));
			$work = json_decode($this->curl->execute(),TRUE);
			$this->template['work'] = $work; 
		}
		$this->template['css'] = $this->load->view('workspace/workspace_canvas_css2',$this->template,TRUE);
		$this->template['content'] = $this->load->view('workspace/workspace_canvas_content2',$this->template,TRUE);
		$this->template['js'] = $this->load->view('workspace/workspace_canvas_js2',$this->template,TRUE);
		
		$this->load->view('template_view',$this->template);
	}
}

/* End of file workspace.php */
/* Location: ./application/controllers/workspace.php */