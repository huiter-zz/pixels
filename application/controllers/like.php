<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 引用流程接入口 
 */


class Like extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();	
		//$this->load->library('rest', array('server' => 'http://localhost/'));	
	}
	
	public function index()
	{
		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->model('work_model', '', TRUE);
        $this->load->model('user_model','',TRUE);
        $this->load->model('user_like_model','',TRUE);
		$this->load->database();
	
	
		
		$user_info = $this->session->userdata('userdata');

		if (empty($user_info)) 
		{
			show_404();
		}
		else
		{
			$this->template['user_info']=$user_info;
			$uid = $user_info['uid'];
			$this->curl->create(base_url("/api/v1/like?uid=$uid&auth=3728"));
		    	$work_info = json_decode($this->curl->execute(),TRUE);
			$this->template['work_info']=$work_info;


			$this->template['js'] = $this->load->view('like/like_css',$this->template,TRUE);
			$this->template['css'] = $this->load->view('like/like_css',$this->template,TRUE);
			$this->template['content'] = $this->load->view('like/like_content',$this->template,TRUE);
			$this->load->view('template_view',$this->template);
		}
	}
}
/* End of file profile.php */
/* Location: ./application/controllers/profile.php */