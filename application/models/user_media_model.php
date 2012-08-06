<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_media_model extends CI_Model {

    var $uid = '';
    var $mediaID = '';
    var $mediaUserID = '';
    

    function __construct()
    {
        parent::__construct();
    }
    
    function get_entry_bymediaUserID($mediaUserID)
    {
        $sql = "SELECT * FROM user_media WHERE mediaUserID = ?";
        $query = $this->db->query($sql, array("$mediaUserID"));
        return $query->row_array();
    }

    function insert_entry($uid_media_data)
    {
        $this->uid=$uid_media_data['uid'];
        $this->mediaID=$uid_media_data['mediaID'];
        $this->mediaUserID=$uid_media_data['mediaUserID'];
        $this->db->insert('user_media', $this);
    }

    function delete_entry_bymediaUserID($mediaUserID)
    {
        $this->db->delete('user_media', array('mediaUserID' => $mediaUserID)); 
    }

}

/* End of uid_media_models.php */
/* Location: ./system/application/controllers/uid_media_models.php */