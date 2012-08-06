<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

    var $uid = '';
    var $name = '';
  

    function __construct()
    {
        parent::__construct();
    }
    
    function get_entry_byuid($uid)
    {
        $sql = "SELECT * FROM user WHERE uid = ?";
        $query = $this->db->query($sql, array("$uid"));
        return $query->row_array();
    }

    function insert_entry($user_data)
    { 
        $this->db->insert('user', $user_data);
        return mysql_insert_id();
    }

    function delete_entry_byuid($uid)
    {

        $this->db->delete('user', array('uid' => $uid)); 
    
    }
    
}

/* End of user_model.php */
/* Location: ./system/application/controllers/user_model.php */