<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 引用流程接入口 
 */
class Activity extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}

	public function index($page='')
	{

		if(empty($page))
		{
			$this->template['css'] = $this->load->view('activity/activity_css',$this->template,TRUE);
			$this->template['content'] = $this->load->view('activity/activity_content',$this->template,TRUE);
			$this->template['js'] = $this->load->view('activity/activity_js',$this->template,TRUE);
			$this->load->view('template_view',$this->template);
		}
		else
		{
			if($page > 3)
				show_404();
			if($page==1)
			{
				
				$response = $this->rest->get('api/v1/qr',NULL,'json');

				$qr_json = json_encode($response,TRUE);
				$qr = json_decode($qr_json,TRUE);
				$this->template['qr'] = $qr;
				
			}
			if($page==2)
			{
				
				$response = $this->rest->get('api/v1/tetris',NULL,'json');
				$tetris_json = json_encode($response,TRUE);
				$tetris = json_decode($tetris_json,TRUE);
				$this->template['tetris'] = $tetris;
				
			}

			$this->template['js'] = $this->load->view("activity/".$page."_js",$this->template,TRUE);
			$this->template['content'] = $this->load->view("activity/".$page."_content",$this->template,TRUE);
			$this->template['css'] = $this->load->view("activity/".$page."_css",$this->template,TRUE);
			$this->load->view('template_view',$this->template);
		}
		
	}
}

/* End of file activity.php */
/* Location: ./application/controllers/activity.php */