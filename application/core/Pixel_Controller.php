<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 扩展Controller
 */
class Pixel_Controller extends CI_Controller
{
	/**
	 * 设置统一Template 
	 * @var array
	 */
	protected $template;
	
	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
		$this->load->database();
		$this->load->library('rest', array('server' => base_url()));	

        /*Catch 当前加载的Method,Class 用来做A 标签的 Active*/
        $this->template['url'] = $this->router->fetch_method();
        $this->template['class'] = $this->router->fetch_class();


        /*
        if ( ! $this->session->userdata('userdata'))
		{          	
			Message::set('未登录无法对作品保存哦！','error');	
		}
		*/
		/*3小时*/
		/* $this->output->enable_profiler(TRUE); */
		/*$this->output->cache(180);*/    
    }
}
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php*/