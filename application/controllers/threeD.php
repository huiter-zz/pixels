<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 工作区 
 */
class ThreeD extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}

	public function index($workid = '')
	{
		
		$this->load->model('work_model','',TRUE);
		$result = $this->work_model->get_entry_byworkid($workid);
		if(empty($result))
		{
			 $work = array();
		}
		else
		{
			$this->curl->create(base_url("/api/v1/work/?workid=".$workid));
			$work = json_decode($this->curl->execute(),TRUE);
		}

		$this->template['work'] = $work; 
		$this->load->view('3D/3D_view',$this->template);
	
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */