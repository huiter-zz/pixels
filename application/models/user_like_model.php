<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_like_model extends CI_Model {

    var $uid = '';
    var $workid = '';

    function __construct()
    {
        parent::__construct();
    }
    
    function get_entry_byuidandworkid($uid,$workid)
    {
        $sql = "SELECT * FROM user_like WHERE uid = ? AND workid = ?";
        $query = $this->db->query($sql, array($uid,$workid));
        return $query->row_array();
    }

    

    function get_workids_byuid($uid)
    {
            
        
        $sql = "SELECT workid FROM user_like WHERE uid = ?";
        $query = $this->db->query($sql, array($uid));
        $like_work_result=$query->result_array();
        $workids ="";
        foreach ($like_work_result as $key => $value) {
            $workids[$key]=$value['workid'];
        }

        return $workids;
    }

    function insert_entry($user_like_data)
    {
        $this->db->insert('user_like', $user_like_data);
    }
   
}

/* End of tag_work_model.php */
/* Location: ./system/application/controllers/tag_work_model.php */