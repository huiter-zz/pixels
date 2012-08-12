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
            <?php if(!empty($hottag_info)):?>
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
                     <?php endif;?>
      </tbody>
      </table>
  </div>

<div class="span4">
  <h1><small>发布状态</small></h1>
      <div class="accordion" id="accordion1">
<?php if(!empty($lastwork_info)):?>
   <?php foreach ($lastwork_info as $key => $value) :?> 

      <div class="accordion-group">
        <div class="accordion-heading">
          <span class="accordion-toggle" data-toggle="collapse" data-parent="#accordion<?php echo $key;?>" href="#collapse<?php echo $key;?>">
            <a href="/book/<?php echo $value['authorid']?>"><?php echo $value['authorname']?></a>于<?php echo $value['createdate']?>创造了此作品.
          </span>
        </div>
        <div id="collapse<?php echo $key;?>" class="accordion-body collapse" style="height: 0px; ">
          <div class="accordion-inner">
            <a href="/work/<?php echo $value['workid']?>">
            <img src="<?php echo $value['img']?>" alt="">
          </a>
          </div>
        </div>
      </div>

  <?php endforeach;?>
  <?php endif;?>
      </div>
</div>
  
</div>