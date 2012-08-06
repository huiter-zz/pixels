<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_work_model extends CI_Model {

    var $tagid = '';
    var $workid = '';

    function __construct()
    {
        parent::__construct();
    }
    
    function get_entry_bytagid($tagid)
    {
        $sql = "SELECT * FROM tag_work WHERE tagid = ?";
        $query = $this->db->query($sql, array("$tagid"));
        return $query->result_array();
    }

    function get_entrynums_bytagid($tagid)
    {
        $sql = "SELECT count(*) FROM tag_work WHERE tagid = ?";
        $query = $this->db->query($sql, array("$tagid"));
        return $query->row_array();
    }

    function get_workids_bytagidandpage($tagid,$page)
    {
            
        $begin=($page-1)*3;
        $sql = "SELECT workid FROM tag_work WHERE tagid = ?  LIMIT ?, 3";
        $query = $this->db->query($sql, array($tagid,$begin));
        $tag_work_result=$query->result_array();

        foreach ($tag_work_result as $key => $value) {
            $workids[$key]=$value['workid'];
        }

        return $workids;
    }


    function insert_entry($tagid_workid_data)
    {
        $this->db->insert('tag_work', $tagid_workid_data);
    }
}

/* End of tag_work_model.php */
/* Location: ./system/application/controllers/tag_work_model.php */