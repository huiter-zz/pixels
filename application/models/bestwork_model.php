<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bestwork_model extends CI_Model {

    var $bestworkid = '';
    var $workid = '';

    function __construct()
    {
        parent::__construct();
    }
    
    function get_workids_bynothing()
    {

        $this->db->select('*');
        $this->db->from('bestwork');
        $this->db->order_by("rand()");
        $this->db->limit(2);
        $query = $this->db->get();
        $bestwork_result=$query->result_array();

        foreach ($bestwork_result as $key => $value) {
            $workids[$key]=$value['workid'];
        }

        return $workids;
    }
}

/* End of tag_work_model.php */
/* Location: ./system/application/controllers/tag_work_model.php */