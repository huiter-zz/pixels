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

	public function index($workid = '')
	{
		if(empty($workid))
		{
			show_404();
		}
		$this->load->model('work_model','',TRUE);
		$result = $this->work_model->get_entry_byworkid($workid);
		if(empty($result))
		{
			show_404();
		}
		$this->curl->create(base_url("/api/v1/work/?workid=".$workid));
		$work = json_decode($this->curl->execute(),TRUE);

		$this->template['work'] = $work; 
		$this->template['js'] = $this->load->view("work/work_js",$this->template,TRUE);
		$this->template['content'] = $this->load->view("work/work_content",$this->template,TRUE);
		$this->template['css'] = $this->load->view("work/work_css",$this->template,TRUE);
		$this->load->view('template_view',$this->template);
	}
}

/* End of file workspace.php */
/* Location: ./application/controllers/workspace.php */