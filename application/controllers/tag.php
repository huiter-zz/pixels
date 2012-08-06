<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 引用流程接入口 
 */
class Tag extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}

	public function index($tagname='')
	{	
		if(empty($tagname))
			show_404();
		else
		{
		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->database();
		
		$this->load->model('tag_model','',TRUE);
		$this->load->model('tag_work_model','',TRUE);
		$this->load->model('work_model','',TRUE);
	   


		$tag_result=$this->tag_model->get_entry_bytagname($tagname);


		//如果TAG不存在
		if(empty($tag_result))
		{

			$this->curl->create(base_url("/api/v1/bestwork"));
			$bestwork_info = json_decode($this->curl->execute(),TRUE);
			
			$this->template['bestwork_info'] = $bestwork_info;
			$this->template['content'] = $this->load->view('tag/notag_content',$this->template,TRUE);
			$this->template['css'] = $this->load->view('tag/notag_css',$this->template,TRUE);
			$this->template['js'] = $this->load->view('tag/notag_js',$this->template,TRUE);
			$this->load->view('template_view',$this->template);

		}
		//如果TAG存在
		else
		{

		$tagid=$tag_result['tagid'];//获得对应的tagid
		$total_rows_array=$this->tag_work_model->get_entrynums_bytagid($tagid);
		$total_rows=$total_rows_array['count(*)'];//获取总行数

		//算出合理的页码
		$current_page=$this->uri->segment(3);
		if(empty($current_page))
			$current_page=1;
		else
		{
			$current_page=intval($current_page);
			if($total_rows>=($current_page*3))
 				$current_page=$current_page;
			else
				$current_page=floor($total_rows/3)+1;
		}
		
		

		
		$this->curl->create(base_url("/api/v1/tag?tagname=$tagname"));
		$tag_info = json_decode($this->curl->execute(),TRUE);

		$this->curl->create(base_url("/api/v1/tagwork?tagname=$tagname&page=$current_page"));
		$work_info = json_decode($this->curl->execute(),TRUE);
		
		


		$this->template['tag_info']=$tag_info;//将TAG信息存储至视图数据数组
		$this->template['works_info']=$work_info;//获取TAG作品信息存储至视图数据数组
		$this->template['pagination']=$this->genePage($total_rows,$current_page,$tagname);//获取页面页码信息存储至视图数据数组

		
		//获取到了最终的content。
		$this->template['content'] = $this->load->view('tag/tag_content',$this->template,TRUE);
		$this->template['js'] = $this->load->view('tag/tag_js',$this->template,TRUE);
		$this->template['css'] = $this->load->view('tag/tag_css',$this->template,TRUE);
		$this->load->view('template_view',$this->template);
		}
	}
	
	}

	public function genePage($total_rows,$current_page,$tagname)
	{	
			$html = '';
			/*分页*/			
			$this->load->library('pagination');
			$config['base_url'] = "/tag/$tagname";
			$config['total_rows'] = $total_rows;
			$config['per_page'] = 2; 
			$config['use_page_numbers'] = TRUE;
			$config['num_links'] = 2;
			$config['full_tag_open'] = '<div class="pagination"><ul class="pull-right">';
			$config['full_tag_close'] = '</ul></div>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';			
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['next_link'] = '→';
			$config['next_tag_close'] = '<li>';
			$config['next_tag_close'] = '</li>';	
			$config['prev_link'] = '←';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';			
			$config['cur_tag_open'] = '<li class="active"><a>';
			$config['cur_tag_close'] = '</a></li>';	
			$config['use_page_numbers'] = TRUE;

			/*处理结果集*/
        	$total_pages = 0;
        	if ($config['total_rows'] > 0)
        	{
            	$total_pages = ceil($config['total_rows'] / $config['per_page']);
        	}

        	if ($current_page > $total_pages) $current_page = $total_pages;
        
			
			/*起作用了*/
			$config['cur_page'] = $current_page;
			$this->pagination->initialize($config);       

			
			
			$html = $this->pagination->create_links();	
			
			return $html;
		}		
}			

/* End of file tag.php */
/* Location: ./application/controllers/tag.php */