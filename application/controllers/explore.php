<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 引用流程接入口 
 */
class Explore extends Pixel_Controller
{		
	public function __construct()
	{
		parent::__construct();		
	}
	
	public function index()
	{	
		$response = $this->rest->get('api/v1/hottaginfo/format/json',NULL,'json');
		$hottaginfo_json = json_encode($response,TRUE);
		$hottaginfo = json_decode($hottaginfo_json,TRUE);
		$this->template['hottag_info'] = $hottaginfo;
		//$this->curl->create(base_url("/api/explore/hottaginfo/format/json"));
		//$this->template['hottag_info'] = json_decode($this->curl->execute(),TRUE);


		$response = $this->rest->get('api/v1/lastworkinfo/format/json',NULL,'json');
		$lastworkinfo_json = json_encode($response,TRUE);
		$lastworkinfo = json_decode($lastworkinfo_json,TRUE);
		$this->template['lastwork_info'] = $lastworkinfo;

		$this->curl->create(base_url("/api/v1/last18work"));
		$bestwork_info = json_decode($this->curl->execute(),TRUE);
		$this->template['bestwork_info'] = $bestwork_info;
		//$this->curl->create(base_url("/api/explore/lastworkinfo/format/json"));
		//$this->template['lastwork_info'] = json_decode($this->curl->execute(),TRUE);

		$this->template['css'] = $this->load->view('explore/explore_css',$this->template,TRUE);
		$this->template['content'] = $this->load->view('explore/explore_content',$this->template,TRUE);
		$this->template['js'] = $this->load->view('explore/explore_js',$this->template,TRUE);
		
		$this->load->view('template_view',$this->template);
	}

}

/* End of file explore.php */
/* Location: ./application/controllers/explore.php */