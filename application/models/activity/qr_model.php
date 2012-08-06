<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Qr_model extends CI_Model 
{

    function __construct()
    {
        parent::__construct();
    }
    
    function get_entrys_bynothing()
    {
        $this->db->select('*');
        $this->db->from('qr');
        $this->db->order_by("rand()");
        $this->db->limit(6);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function insert_entry($data)
    { 
        $this->db->insert('qr', $data);
        return mysql_insert_id();
    }
}

/* End of qr_model.php */
/* Location: ./system/application/model/activity/qr_model.php */