<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hottag_model extends CI_Model {

    var $tagid = '';
    var $workid = '';

    function __construct()
    {
        parent::__construct();
    }
    
    function get_tags_bynothing()
    {
        $this->db->select('*');
        $this->db->from('hottag');
        $query =$this->db->get();
        $hottag_result = $query->result_array();

        foreach ($hottag_result as $key => $value) 
        {
            $tags[$key]=$value['tagid'];
        }

        return $tags;
    }

    function get_entrys_bynothing()
    {
        $this->db->select('*');
        $this->db->from('hottag');
        $this->db->join('tag','hottag.tagid = tag.tagid');
        $this->db->join('user','tag.bestauthor = user.uid');
   
        $query = $this->db->get();

        return $query->result_array();
    }
}

/* End of hottag_model.php */
/* Location: ./system/application/controllers/hottag_model.php */