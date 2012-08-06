<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Pixel_Controller {


	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$context = $this->input->get_post('context');
		if(empty($context))
		{
			$context='8bit';
		}
		$text='/tag/'.$context;
		redirect(base_url("$text"));
	}
}


/* End of search.php */
/* Location: ./application/controllers/search.php */