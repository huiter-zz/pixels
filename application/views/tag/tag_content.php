 <ul class="breadcrumb">
  <li>
    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li>
    作品廊<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li class="active"><span class="label"><?php echo $tag_info['tagname']?></span></li>
</ul>
                <div>
                <h2>标签:<?php echo $tag_info['tagname']?></h2>
                <table class="table table-striped table-bordered table-condensed">
                <thead>
                <tr>
                    <th style="width: 20%"><i class="icon-calendar"></i>创建时间</th> 
                    <th style="width: 20%"><i class="icon-heart"></i>有这么多人喜欢过</th>
                    <th><i class="icon-user"></i>活跃创造者</th>
                    <th style="width: 10%">总作品量</th>
                </tr>
                </thead>
                <tbody>     
                <tr>
                    <td><span><?php 
                    $datestring = "%Y-%m-%d";
                    echo mdate($datestring,  strtotime($tag_info['createdate']));
                    ?></span></td>
                    <td><span><?php echo $tag_info['likesnum']?></span></td>
                    <td><a href="/book/<?php echo $tag_info['bestauthorid']?>"><?php echo $tag_info['bestauthorname']?></a></td>
                    <td><span class="label"><?php echo $tag_info['worksnum']?></span></td>
                </tr>            
            </tbody>
            </table>
            </div>

    
            <div>
                <hr>
                <ul class="thumbnails">
                <?php if (!empty($works_info)):?>
                <?php foreach ($works_info as $key => $value):?>
                    <li class="span3">
                        <div class="thumbnail">
                            <a data-toggle="modal" href="#modal<?php echo $value['workid'] ?>"><img class="tagimg" src="<?php echo $value['img'] ?>" alt=""></a>
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
                                <a class="btn btn-danger pull-left" onclick="likepost(<?php echo $value['workid'] ?>)">喜欢</a>
                                <a class="btn pull-right" href="/workspace?id=<?php echo $value['workid'] ?>">再创作</a>
                                </div>
                                </div>
                        </div>
                        <div id="modal<?php echo $value['workid']?>" class="modal hide fade">
                            <div class="modal-body">
                                <a href="/work/<?php echo $value['workid'];?>"><img src="<?php echo $value['img'] ?>" alt=""></a>
                                <a class="btn span6" href="/work/<?php echo $value['workid'];?>">进入详情页查看更多内容</a>
                            </div>
                        </div>
                    </li>

                <?php endforeach;?>
            <?php endif;?>
                    
                </ul>
            </div>
    




    <?php if ( isset ($pagination) && ! empty($pagination)): ?>
        <?php print $pagination; ?>
    <?php endif ?>


