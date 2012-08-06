<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tetris_model extends CI_Model 
{

    function __construct()
    {
        parent::__construct();
    }
    
    function get_charts()
    {
        $this->db->select('*');
        $this->db->from('tetris');
        $this->db->order_by('score','desc');
        $this->db->limit(7);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function insert_entry($data)
    { 
        $this->db->insert('tetris', $data);
        return mysql_insert_id();
    }
}

/* End of tetris_model.php */
/* Location: ./system/application/model/activity/tetris_model.php */