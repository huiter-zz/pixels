<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function get_entry_bytagname($tagname)
    {

        $this->db->select('*');
        $this->db->from('tag');
        $this->db->join('user','user.uid = tag.bestauthor');
        $this->db->like('tag.tagname', $tagname); 
        $query = $this->db->get();
        return $query->row_array();
    }

    function get_entrys_bytagids($tagids)
    {

        $this->db->select('*');
        $this->db->from('tag');
        $this->db->where_in('tagid',$tagids);
        $query = $this->db->get();
        return $query->result_array();
    }

    function updata_addlike($tagnames)
    {
        
        $this->db->set('likesnum','likesnum+1',FALSE);
        $this->db->where_in('tagname',$tagnames);
        $this->db->update('tag');        
    }

    function insert_entry($tag_data)
    { 
        $this->db->insert('tag', $tag_data);
        return mysql_insert_id();
    }
}

/* End of tag_model.php */
/* Location: ./system/application/controllers/tag_model.php */