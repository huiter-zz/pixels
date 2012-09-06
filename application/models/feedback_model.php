<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_model extends CI_Model 
{

    function __construct()
    {
        parent::__construct();
    }
    
    function get_entrys()
    {
        $this->db->select('*');
        $this->db->from('feedback');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function insert_entry($data)
    { 
        $this->db->insert('feedback', $data);
        return mysql_insert_id();
    }
}

/* End of tetris_model.php */
/* Location: ./system/application/model/activity/tetris_model.php */