<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'/libraries/oss/sdk.class.php';
require_once APPPATH.'/libraries/oss/conf.inc.php';

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

class V1 extends REST_Controller
{
    //用户个人信息
    function user_get()
    {
        if(!$this->get('uid'))
        {
            $this->response(array('message' => 'no uid'),400);
        }

        $uid = $this->get('uid');
        $this->load->model('user_model','',TRUE);
        $user = $this->user_model->get_entry_byuid($uid);

        if (empty($user)) 
        {
            $this->response(array('message' => 'No This Boy!'), 400);
        }
        else
        {
            $out['name'] = $user['name'];
            $this->response($out,200);
        }

    }

    //获取作品全部信息
    function work_get()
    {
        if(!$this->get('workid'))
        {
            $this->response(array('message' => 'no workid'),400);
        }
        $workid =$this->get('workid');
        $this->load->model('work_model','',TRUE);
        $work = $this->work_model->get_entry_byworkid($workid);

        if (empty($work)) 
        {
            $this->response(array('message' => 'No This work!'), 400);
        }
        else
        {
            //$out['name'] = $user['name'];
            $this->response($work,200);
        }
    }
    //作品提交
    function work_post()
    {
       
        if(!$this->session->userdata('userdata'))
        {
           $this->response(array('message' => '你还没有登陆'), 403);
        }
        $userdata = $this->session->userdata('userdata');
        if(!$this->input->post('img'))
        {
             $this->response(array('message' => 'no img'), 400);
        }

        if(!$this->input->post('cubejson'))
        {
             $this->response(array('message' => 'no cubejson'), 400);
        }

        $tag[1] = $this->input->post('tag1');
        $tag[2] = $this->input->post('tag2');
        $tag[3] = $this->input->post('tag3');

        $tag[1] = strtolower(trim($tag[1]));
        $tag[2] = strtolower(trim($tag[2]));
        $tag[3] = strtolower(trim($tag[3]));

    
        $this->load->model('work_model','',TRUE);
        $this->load->model('tag_model','',TRUE);
        $this->load->model('tag_work_model','',TRUE);

        $tags = $tag[1].';'.$tag[2].';'.$tag[3].';';
        $content  = $this->post('img');
        $data['tags'] = $tags;
        $data['author'] = $userdata['uid'];
        $data['likesnum'] = 0;
        $cubejsonname = time().rand(0,9);
        $data['cubejson'] =  "http://storage.aliyun.com/pixels/cubejson/".$cubejsonname.'.txt';
        $data['createdate'] = date('Y-m-d H:i:s');
        $data['kind'] = 0;
        $imgname = time().rand(0,9);
        $data['img'] = "http://storage.aliyun.com/pixels/work/".$imgname.'.png';

        $workid=$this->work_model->insert_entry($data);
        foreach ($tag as $key => $value) 
        {       
            if(!empty($value))
            {
                $tagentry=$this->tag_model->get_tagid_bytagname($value);
                
                if(empty($tagentry))
                {
                    $tag_data['tagname']=$value;
                    $tag_data['createdate'] =  date('Y-m-d H:i:s');
                    $tag_data['bestauthor'] = 10000;
                    $tag_data['likesnum'] =0;
                    $tag_data['worksnum'] = 1;

                    $tagid=$this->tag_model->insert_entry($tag_data);
                }
                else
                {
                    $tagid = $tagentry['tagid'];
                    $this->tag_model->updata_addwork($value);
                }
                $tag_work_data['tagid'] = $tagid;
                $tag_work_data['workid'] = $workid;
                $this->tag_work_model->insert_entry($tag_work_data);
            }
        }

        $oss_sdk_service = new ALIOSS();
        $oss_sdk_service->set_debug_mode(FALSE);
        $bucket = 'pixels';

        $object = 'work/'.$imgname.'.png'; 
        $content =base64_decode($content); 
        $upload_file_options = array(
            'content' => $content,
            'length' => strlen($content),
            ALIOSS::OSS_HEADERS => array(
                'Expires' => '2014-10-01 08:00:00',
            ),
        );
        $response = $oss_sdk_service ->upload_file_by_content($bucket,$object,$upload_file_options);


        $object = 'cubejson/'.$cubejsonname.'.txt'; 
        $content = $this->input->post('cubejson');
        $upload_file_options = array(
            'content' => $content,
            'length' => strlen($content),
            ALIOSS::OSS_HEADERS => array(
                'Expires' => '2014-10-01 08:00:00',
            ),
        );
        $response = $oss_sdk_service ->upload_file_by_content($bucket,$object,$upload_file_options);
        




        $this->response(array('message' =>'发布成功' ),200);    
    }
    
  
    //个人喜欢集
    function like_get()
    {
        $this->load->model('work_model','',TRUE);
        
        if ($this->get('auth') == '3728') 
        {
            $uid = $this->get('uid');

        }
        else
        {
            $session = $this->session->userdata('userdata');
            
            if(empty($session))
            {
               $this->response(array('message' => 'no auth'),403);     
            }

            $uid = $session['uid'];
        }

        $like = $this->work_model->get_like($uid);

        if($like)
        {
            foreach ($like as $key => $value) 
            {
                
                $out[$key]['workid'] = $value['workid'];
                $out[$key]['img'] = $value['img'];
                $out[$key]['likedate'] = $value['likedate'];     
            }

            $this->response($out, 200); 
        }

        else
        {
            $this->response(array('message' => 'could not be found'), 404);
        }

    }

    //个人作品集
    function book_get()
    {
        if(!$this->get('uid'))
        {
            $this->response(array('message' => 'no uid'),400);
        }

        $uid = $this->get('uid');
        $this->load->model('work_model', '', TRUE);
        $this->load->model('user_model','',TRUE);

        $user = $this->user_model->get_entry_byuid($uid);
        if (empty($user)) 
        {
            $this->response(array('message' => 'No This Boy!'), 400);
        }

        $book = $this->work_model->get_book($uid);

        if($book)
        {
            foreach ($book as $key => $value) 
            {
                
                $out[$key]['workid'] = $value['workid'];
                $out[$key]['img'] = $value['img'];
                $out[$key]['createdate'] = $value['createdate'];     
            }

            $this->response($out, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('message' => 'could not be found'), 404);
        }


    }

    //作品廊 【热门TAG信息】
    function hottaginfo_get()
    {

        $this->load->model('hottag_model','', TRUE);

        $hottaginfo = $this->hottag_model->get_entrys_bynothing();
      
        if($hottaginfo)
        {   
            foreach ($hottaginfo as $key => $value) 
            {
                $out[$key]['tagname'] = $value['tagname'];
                $out[$key]['likesnum'] = $value['likesnum'];
                $out[$key]['bestauthorid'] = $value['uid'];
                $out[$key]['bestauthorname'] = $value['name'];
                $out[$key]['worksnum'] = $value['worksnum'];
            }



            $this->response($out, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'could not be found'), 404);
        }
    } 
     //作品廊 【最新作品信息】
    function lastworkinfo_get()
    {
        $this->load->model('work_model','',TRUE);

        $lastworkinfo = $this->work_model->get_lasttenentrys_bynothing();
        //print_r($lastworkinfo);
        if($lastworkinfo)
        {
            foreach ($lastworkinfo as $key => $value) 
            {
                $out[$key]['authorid'] = $value['uid'];
                $out[$key]['authorname'] = $value['name'];
                $out[$key]['img'] = $value['img'];  
                $out[$key]['workid'] = $value['workid'];
                $out[$key]['createdate'] = $value['createdate'];
            }
            
            $this->response($out, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'lastworkinfo could not be found'), 400);
        }
    }



    //TAG页 【TAG信息】
    function tag_get()
    {
        if(!$this->get('tagname'))
        {
                $this->response(NULL, 400);
        }

        $tagname = $this->get('tagname');

        $this->load->model('tag_model', '', TRUE);
     

        $tag = $this->tag_model->get_entry_bytagname($tagname);   

 
        if(!empty($tag))
        {

            $out['tagname'] = $tag['tagname'];
            $out['createdate'] = $tag['createdate'];
            $out['likesnum'] = $tag['likesnum'];
            $out['bestauthorid'] = $tag['uid'];
            $out['bestauthorname'] = $tag['name'];
            $out['worksnum'] = $tag['worksnum'];
        
            $this->response($out, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'User could not be found'), 400);
        }
    }
    //TAG页 【作品】
    function tagwork_get()
    {
        if(!$this->get('tagname'))
        {
                $this->response(NULL, 400);
        }

        $tagname = $this->get('tagname');

        if(!$this->get('page'))
        {
                $page = 1;
        }
        else
        {
                $page = $this->get('page');
        }


        $this->load->model('work_model', '', TRUE);

        $works = $this->work_model->get_tagwork($tagname,$page);

        foreach ($works as $key => $value)
        {
            $out[$key]['workid'] = $value['workid'];
            $out[$key]['authorname'] = $value['name'];
            $out[$key]['authorid'] = $value['uid'];
            $out[$key]['likesnum'] = $value['likesnum'];
            $out[$key]['img'] = $value['img'];
            $out[$key]['tags'] = $value['tags'];
            $out[$key]['kind'] = $value['kind'];
            $out[$key]['cubejson'] = $value['cubejson'];
            $out[$key]['createdate'] = $value['workcreatedate'];
        }
        
        if($out)
        {
            $this->response($out, 200); // 200 being the HTTP response code
        }

        else
        {
            $this->response(array('error' => 'bestworkinfo could not be found'), 404);
        }
    }
    //TAG页 【精选】
    function bestwork_get()
    {
        $this->load->model('work_model', '', TRUE);
        $works = $this->work_model->get_bestwork();

        if($works)
        {
            foreach ($works as $key => $value)
            {

                $out[$key]['workid'] = $value['workid'];
                $out[$key]['authorname'] = $value['name'];
                $out[$key]['authorid'] = $value['uid'];
                $out[$key]['likesnum'] = $value['likesnum'];
                $out[$key]['img'] = $value['img'];
                $out[$key]['tags'] = $value['tags'];
                $out[$key]['kind'] = $value['kind'];
                $out[$key]['cubejson'] = $value['cubejson'];
                $out[$key]['createdate'] = $value['createdate'];

            }
            $this->response($out, 200); // 200 being the HTTP response code
        }
        else
        {
            $this->response(array('error' => 'bestworkinfo could not be found'), 400);
        }
    }
    //活动【二维码获取】
    function qr_get()
    {
        $this->load->model('/activity/qr_model', '', TRUE);
        
        $qrs = $this->qr_model->get_entrys_bynothing();

                foreach ($qrs as $key => $value)
                {
                    $out[$key]['message'] = $value['message'];
                } 
        //$out['message'] =$qr['message'];
        
        $this->response($out, 200);
    }

    //活动 【二维码提交】
    function qr_post()
    {

        if(!$this->input->post('message'))
        {
            $this->response(array('error' => '没有获取内容'), 200);
        }
        $in['message'] = $this->input->post('message');
        
        $this->load->model('activity/qr_model', '', TRUE);
        
        $data['message'] = $in['message'];

        $id=$this->qr_model->insert_entry($data);

        if(empty($id))
        {
            $this->response(array('message' => '提交失败了'), 200);
        }
        $this->response(array('message' => '提交成功'), 200);  

    }
        //活动【俄罗斯方块排名获取】
    function tetris_get()
    {
        $this->load->model('/activity/tetris_model', '', TRUE);
        
        $tetris = $this->tetris_model->get_charts();


        foreach ($tetris as $key => $value)
        {
            $out[$key]['name'] = $value['name'];
            $out[$key]['score'] = $value['score'];
        } 
       $this->response($out, 200);
    }

    //活动 【俄罗斯方块新成绩提交】
    function tetris_post()
    {
    
        $in['name'] = $this->input->post('name');
        $in['score'] = $this->input->post('score');

        $this->load->model('activity/tetris_model', '', TRUE);
        
        $data['name'] = $in['name'];
        $data['score'] = $in['score'];


        $id=$this->tetris_model->insert_entry($data);

        if(empty($id))
        {
            $this->response(array('message' => '提交失败了'), 400);
        }

        $this->response(array('message' => '提交成功'), 200);  

    }
}