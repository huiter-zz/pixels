<ul class="breadcrumb">
  <li>
    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li class="active"><span class="label">作品廊</span></li>
</ul>
<div class="row">

<div class="span8">
    <h1><small>标签墙</small></h1>
      <table class="table table-striped table-bordered table-condensed">
          <thead>
          <tr>
              <th style="width: 20%"><i class="icon-tag"></i>热门标签</th> 
              <th style="width: 20%"><i class="icon-heart"></i>喜欢</th>
              <th><i class="icon-user"></i>活跃创造者</th>
              <th style="width: 10%">&nbsp;作品量</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($hottag_info as $key => $value) :?>
          <tr>
              <td>
                <a class="hottag" href="/tag/<?php echo $value['tagname']?>"><strong><?php echo $value['tagname']?></strong></a>
              </td>
              <td><span><?php echo $value['likesnum']?></span></td>
              <td><a class="hottag" href="/book/<?php echo $value['bestauthorid']?>"><strong><?php echo $value['bestauthorname']?></strong></a></td>
              <td><span class="label"><?php echo $value['worksnum']?></span></td>
              
          </tr> 
        <?php endforeach;?>
                     
      </tbody>
      </table>
  </div>

<div class="span4">
  <h1><small>发布状态</small></h1>
    <div class="accordion" id="accordion2">
      <div class="accordion-group">
        <div class="accordion-heading">
          <span class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
            <a href="/book/<?php echo $lastwork_info[0]['authorid']?>"><?php echo $lastwork_info[0]['authorname']?></a>于<?php echo $lastwork_info[0]['createdate']?>创造了此作品.
          </span>
        </div>
        <div id="collapseOne" class="accordion-body collapse" style="height: 0px; ">
          <div class="accordion-inner">
            <a href="/work/<?php echo $lastwork_info[0]['workid']?>">
            <img class="JIATHIS_IMG_OK" src="http://storage.aliyun.com/pixels/image/px.png" alt="">
          </a>
          </div>
        </div>
      </div>
      <div class="accordion-group">
        <div class="accordion-heading">
          <span class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
            <a href="/book/<?php echo $lastwork_info[1]['authorid']?>"><?php echo $lastwork_info[1]['authorname']?></a>于<?php echo $lastwork_info[1]['createdate']?>创造了此作品.
          </span>
        </div>
        <div id="collapseTwo" class="accordion-body collapse" style="height: 0px; ">
          <div class="accordion-inner">
            <a href="/work/<?php echo $lastwork_info[2]['workid']?>">
            <img class="JIATHIS_IMG_OK" src="http://storage.aliyun.com/pixels/image/px.png" alt="">
          </a>
          </div>
        </div>
      </div>
      <div class="accordion-group">
        <div class="accordion-heading">
          <span class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
            <a href="/book/<?php echo $lastwork_info[2]['authorid']?>"><?php echo $lastwork_info[2]['authorname']?></a>于<?php echo $lastwork_info[2]['createdate']?>创造了此作品.
          </span>
        </div>
        <div id="collapseThree" class="accordion-body in collapse" style="height: auto; ">
          <div class="accordion-inner">
            <a href="/work/<?php echo $lastwork_info[2]['workid']?>">
            <img class="JIATHIS_IMG_OK" src="http://storage.aliyun.com/pixels/image/px.png" alt="">
            </a>
          </div>
        </div>
      </div>
    </div>
</div>
  
</div>