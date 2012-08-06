<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Controller
 * @author      Phil Sturgeon
 * @link        http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Submit extends REST_Controller
{
    
    function like_post()
    {
        

        if(!$this->input->post('workid'))
        {
                $this->response(array('error' => '没有获取到作品ID'), 200);
        }

        $workid = $this->input->post('workid');

        
        $this->load->model('user_like_model', '', TRUE);
        $this->load->model('work_model', '', TRUE);
        $this->load->model('tag_model', '', TRUE);

        $this->load->helper('date');

        if($this->session->userdata('userdata'))
        {
            $user = $this->session->userdata('userdata');
            $uid=$user['uid'];


            $workentry = $this->work_model->get_entry_byworkid($workid);

            if(!empty($workentry))
            {
                $likeentry = $this->user_like_model->get_entry_byuidandworkid($uid,$workid);
                if(empty($likeentry))
                {
                    $user_like_data['likedate']=date("Y-m-d");
                    $user_like_data['uid'] = $uid;
                    $user_like_data['workid'] = $workid;
                    $this->user_like_model->insert_entry($user_like_data);
                    $this->work_model->updata_addlike($workid);
                    $work=$this->work_model->get_entry_byworkid($workid);
                    $tagnames =explode(";",$work['tags']);
                    $this->tag_model->updata_addlike($tagnames);
                    $this->response(array('error' => '喜欢成功'), 200);  
                }
                else
                {
                    $this->response(array('error' => '已经喜欢过了'), 200);  
                }
            }
            else
            {
                $this->response(array('error' => '没这个作品'), 200);
            }
        }
        else
        {
            $this->response(array('error' => '你还没有登录哦'), 200);
        }

    }

    function qr_post()
    {
        
        $this->load->model('activity/qr_model', '', TRUE);
        if(!$this->input->post('message'))
        {
                $this->response(array('error' => '没有获取内容'), 200);
        }
 
        $qr_data['message'] = $this->input->post('message');
        $id=$this->qr_model->insert_entry($qr_data);

        if(!empty($id))
        {
            $this->response(array('error' => '提交成功'), 200);  
        }
        else
        {
            $this->response(array('error' => '提交失败了'), 200);  
        }
           

    }
}