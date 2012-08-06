<ul class="breadcrumb">
  <li>
    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li>
    <?php echo $user_info['name']?>的个人数据<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li class="active"><span class="label">作品集</span></li>
</ul>

 <div class="row">    
        <ul class="archivelist">
<?php if (!empty($work_info)) :?>         
<?php foreach ($work_info as $key => $value) :?>
    <?php $time=mdate("%Y-%m",strtotime($value['createdate']));?>
        <?php if ($key==0):?>
        <?php  $tempdate=$time;?>
            <h3><?php echo $time;?></h3>
        <?php endif;?> 
        <?php if(($key!=0)&&($time!=$tempdate)):?>
            <h3><?php echo $time;?></h3>
            <?php $tempdate=$time;?>
        <?php endif;?>   
            <li class="txt">
                <a target="_blank" href="<?php echo "/work/".$value['workid'];?>" class="alink"></a>
                <img src="<?php echo $value['img'];?>">    
                <p class="data" url="<?php echo "/work/".$value['workid'];?>" style="display: none; "><?php echo mdate("%m月%d日",strtotime($value['createdate']));?></p>
            </li>
<?php endforeach;?>  
<?php endif;?>
</ul>
</div>


