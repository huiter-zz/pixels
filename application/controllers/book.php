<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 引用流程接入口 
 */


class Book extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}
	
	public function index($uid = 0)
	{
		$this->load->helper('date');
		$this->load->model('user_model','',TRUE);
		$user_result=$this->user_model->get_entry_byuid($uid);

		if (empty($user_result)||$uid==0) 
		{
			show_404();
		}

		else
		{
			$uid=$user_result['uid'];

			$this->curl->create(base_url("/api/v1/user/?uid=$uid"));
		        $user_info = json_decode($this->curl->execute(),TRUE);
			$this->template['user_info']=$user_info;

			$this->curl->create(base_url("/api/v1/book?uid=$uid"));
		        $work_info = json_decode($this->curl->execute(),TRUE);
			$this->template['work_info']=$work_info;
			

			$this->template['js'] = $this->load->view('book/book_js',$this->template,TRUE);	
			$this->template['css'] = $this->load->view('book/book_css',$this->template,TRUE);	
			$this->template['content'] = $this->load->view('book/book_content',$this->template,TRUE);
			$this->load->view('template_view',$this->template);
		}
	}
}
/* End of file profile.php */
/* Location: ./application/controllers/profile.php */