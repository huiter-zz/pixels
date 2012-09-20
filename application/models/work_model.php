<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_model extends CI_Model 
{

    function __construct()
    {
        parent::__construct();
    }
    //通过 $workid获取数据，返回一行。
    

    function get_lasttenentrys_bynothing()
    {
        $this->db->select('*');
        $this->db->from('work');
        $this->db->join('user','user.uid = work.author');
        $this->db->limit(3);
        $this->db->order_by("workid", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_entry_byworkid($workid)
    {
        $this->db->select('*');
        $this->db->from('work');
        $this->db->join('user','user.uid = work.author');
        $this->db->where_in('work.workid',$workid);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    function get_book($uid)
    {
        $this->db->select('*');
        $this->db->from('work');
        $this->db->join('user','user.uid = work.author');
        $this->db->where_in('user.uid',$uid);
        $this->db->order_by("createdate", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_like($uid)
    {
   
        $this->db->select('*');
        $this->db->from('work');
        $this->db->join('user_like','work.workid=user_like.workid');
        $this->db->where('user_like.uid', $uid);
        $this->db->order_by("likedate", "desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_bestwork()
    {
        $this->db->select('*');
        $this->db->from('work');
        $this->db->join('bestwork','work.workid=bestwork.workid');
        $this->db->join('user','user.uid = work.author');
        $this->db->order_by("rand()");
        $this->db->limit(8);
        $query = $this->db->get();
        return $query->result_array();
    }
    function get_last18work()
    {
        $this->db->select('*');
        $this->db->from('work');
        $this->db->join('user','user.uid = work.author');
        $this->db->order_by("workid","desc");
        $this->db->limit(18);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_tagwork($tagname,$page)
    {
        $begin=($page-1)*12;

        $this->db->select('*,work.createdate as workcreatedate');
        $this->db->from('work');
        $this->db->join('tag_work','work.workid=tag_work.workid');
        $this->db->join('tag','tag.tagid= tag_work.tagid');
        $this->db->join('user','user.uid = work.author');
        $this->db->where('tag.tagname',$tagname);
        $this->db->order_by("work.createdate","desc");
        $this->db->limit(12,$begin);

        $query = $this->db->get();
        return $query->result_array();
    }

    function updata_addlike($workid)
    {
        $sql = "UPDATE work SET likesnum = likesnum+1 WHERE workid = $workid";
        $query = $this->db->query($sql);
    }

    function insert_entry($data)
    {

        $this->db->insert('work', $data);
        return mysql_insert_id();
    }

    function delete_entry_byuid($workid)
    {

        $this->db->delete('work', array('work' => $workid)); 
    }

}

/* End of work_model.php */
/* Location: ./system/application/model/work_model.php */