 <ul class="breadcrumb">
  <li>
    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li class="active"><span class="label">精选</span></li>
</ul>
<div class="alert alert-block alert-error ">
            <h4 class="alert-heading">Oh ! 你发现了一个新标签。</h4>
            <p>目前还没有相关的作品被创造，以下是我们精选出的作品。</p>
            <p>当然你也可以动手创造作品来占领这个标签哦！</p>
            <p>
              <a class="btn btn-danger" href="#">去创造世界</a> <a class="btn" href="#">换一换</a>
            </p>
</div>
            <div>
                <hr>
                <ul class="thumbnails">
                <?php if (!empty($bestwork_info)):?>
                <?php foreach ($bestwork_info as $key => $value):?>
                    <li class="span3">
                        <div class="thumbnail">
                            <a data-toggle="modal" href="#modal<?php echo $value['workid'] ?>"><img  src="<?php echo $value['img'] ?>" alt=""></a>
                            <div class="caption">
                                <i class="icon-tag"></i>
                                <?php $tags =explode(";",$value['tags']);?>
                                <?php foreach ($tags as $key => $tag) :?> 
                                <?php if (!empty($tag)):?>            
                                <a class="tag" href="/tag/<?php echo $tag;?>">
                                    <span class="label label-info "><?php echo $tag;?></span>
                                </a>
                                <?php endif;?>
                                <?php endforeach;?>
                                <hr class="taghr">
                                 
                                <div style="height:50px;border:1px black soild;">
                                <p><a target="_blank" href="<?php echo "/book/".$value['authorid'];?>" ><?php echo $value['authorname'] ?></a>于<?php echo date("Y-m-d",strtotime($value['createdate'])); ?>绘制此作品</p>
                                </div>
                                <div style="height:30px;">
                                <a class="btn btn-danger pull-left" onclick="likepost(<?php echo $value['workid'] ;?>)">喜欢</a>
                                <a class="btn pull-right"href="/workspace?id=<?php echo $value['workid'] ;?>">再创作</a>
                                </div>
                                </div>
                        </div>
                        <div id="modal<?php echo $value['workid']?>" class="modal hide fade">
                            <div class="modal-body">
                                <img src="<?php echo $value['img'] ?>" alt=""></a>
                            </div>
                        </div>
                    </li>

                <?php endforeach;?>
            <?php endif;?>
                    
                </ul>
            </div>
    


