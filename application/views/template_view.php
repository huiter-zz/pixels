<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
            <title>像素の逆袭，By Shortytall</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="当我们能拍出数千万像素的照片时，当我们能看到1080P的动漫时，我却开始怀念小时候8BIT所带来的美好。Mario、洛克人那些曾经的经典浮现在我眼前，文艺也好，XX也罢，我怀着一颗对数字和艺术狂热的心等待   那‘像素的逆袭’。">
            <meta name="author" content="Shortytall 小组，90后技术宅。">
            <!-- Le styles -->
            <link href="http://storage.aliyun.com/pixels/assets/css/bootstrap.css" rel="stylesheet">
            <?php if ( isset ($css) && ! empty($css)): ?>
                <?php print $css;?>
            <?php endif ?>  
            <style type="text/css">
            body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
            }
            </style>
                            
            <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
            <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
            <![endif]-->
            <!-- Le fav and touch icons -->         
            <link rel="shortcut icon" href="http://storage.aliyun.com/pixels/assets/ico/favicon.ico">
    </head>

    <body>
                        <!-- Login Modal BEGIN -->
            <div id="Login" class="modal hide fade">
                <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <img alt="像素の逆袭" style="width:25px;height:25px;"src="http://storage.aliyun.com/pixels/assets/img/px.png">
                <small>像素の逆袭</small>
                </div>
                <div class="modal-body-denglu">
                    <!-- Denglu Plugin BEGIN -->
                        <ul class="nav nav-pills" style="margin-top :20px;margin-left:35px;">
                            <li class="span2"><a target="_blank" style="height:15px;background:url(http://storage.aliyun.com/pixels/assets/img/denglu/SINA.png) no-repeat scroll 0 0 transparent;"href="http://open.denglu.cc/transfer/sina?appid=21390" title="用新浪微博账号登录"></a></li>
                            <li class="span2"><a target="_blank" style="height:15px;background:url(http://storage.aliyun.com/pixels/assets/img/denglu/QQ.png) no-repeat scroll 0 0 transparent;"href="http://open.denglu.cc/transfer/QQ?appid=21390" title="用QQ账号登录"></a></li>
                            <li class="span2"><a target="_blank" style="height:15px;background:url(http://storage.aliyun.com/pixels/assets/img/denglu/RENREN.png) no-repeat scroll 0 0 transparent;"href="http://open.denglu.cc/transfer/renren?appid=21390" title="用人人网账号登录"></a></li>
                            <li class="span2"><a target="_blank" style="height:15px;background:url(http://storage.aliyun.com/pixels/assets/img/denglu/DOUBAN.png) no-repeat scroll 0 0 transparent;"href="http://open.denglu.cc/transfer/DOUBAN?appid=21390" title="用豆瓣网账号登录"></a></li>
                            <li class="span2"><a target="_blank" style="height:15px;background:url(http://storage.aliyun.com/pixels/assets/img/denglu/TAOBAO.png) no-repeat scroll 0 0 transparent;"href="http://open.denglu.cc/transfer/QQ?appid=21390" title="用淘宝账号登录"></a></li>
                            <li class="span2"><a target="_blank" style="height:15px;background:url(http://storage.aliyun.com/pixels/assets/img/denglu/KAIXIN.png) no-repeat scroll 0 0 transparent;"href="http://open.denglu.cc/transfer/DOUBAN?appid=21390" title="用开心网账号登录"></a></li>
                        </ul>
                    <!-- Denglu Plugin END -->
                </div>
            </div>
        <!-- Login Modal END -->
        <div id="backtop" style="left: auto; right: 30px; position: fixed; top: auto; bottom: 30px; z-index: 99999999; overflow: hidden; ">
            <a href="#" title="返回顶部" style="margin-left: 3px; background-image: url(http://img.ujian.cc/style/1.png); background-attachment: scroll; background-color: transparent; display: block; float: right; outline-width: 0px; outline-style: none; text-indent: -9999em; width: 32px; height: 32px; background-position: -64px 0px; background-repeat: no-repeat no-repeat; ">返回顶部</a>
        </div>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="#">像素の逆袭</a>
                    <a class="logo" href="#"><img alt="像素の逆袭" src="http://storage.aliyun.com/pixels/assets/img/px.png"></a>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <li <?php if($class=='workspace'):?>class="active"<?php endif;?>><a href="/workspace">工作间</a></li>
                            <li <?php if($class=='explore'):?>class="active"<?php endif;?>><a href="/explore">作品廊</a></li>
                            <li <?php if($class=='activity'):?>class="active"<?php endif;?>><a href="/activity">活动区</a></li>
                            <li <?php if($class=='about'):?>class="active"<?php endif;?>><a href="/about">关于</a></li>
                            <li class="divider-vertical"></li>
                        </ul>
                        
                        <form class="navbar-search pull-left" action="/search" method="post">
                            <input name="context" type="text" class="search-query span2" placeholder="8bit">
                            <button class="btn btn-primary " type="submit">搜索</button>
                        </form>
                        <ul class="nav pull-right">
                            <li class="divider-vertical"></li>

                            <?php if(!$this->session->userdata('userdata')):?>
                            <li><a data-toggle="modal" href="#Login">登录</a></li>
                            <?php endif;?>

                            <?php if($this->session->userdata('userdata')):
                            $userdata=$this->session->userdata('userdata');
                            ?>
                            <li class="dropdown">
                                
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i>&nbsp;<?php echo $userdata['name'];?> <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="/like">我的喜欢</a></li>
                                    <li><a href="/book/<?php echo $userdata['uid'];?>">我的作品集</a></li>
                                    <li class="divider"></li>
                                    <li><a href="/quit">退出</a></li>
                                </ul>
                            </li>
                            <?php endif;?>

                        </ul>
                    </div><!--/.nav-collapse --> 
                </div>
            </div>
        </div>

    
    <div class="container">
        
    <header style="text-align:center;">
        <h1>让像素来创造另一个世界吧！</h1>
        <p>Pixels create everything.</p>
    </header>
      <!-- Main hero unit for a primary marketing message or call to action -->
        <?php $message=$this->session->flashdata('message');?>
        <?php if ( isset ($message) && ! empty($message)): ?>
		     <?php /*处理消息提示*/echo $message;?>
        <?php endif;?>

        <?php if ( isset ($content) && ! empty($content)): ?>
            <?php print $content; ?>
        <?php endif ?>
        
    <footer class="footer well" >
                
        <h3>本站正在不断改进中。。。</h3>       
        <div class="progress progress-striped active">
            <div class="bar" style="width: 35%"></div>
        </div>
        <p >Copyright &copy;2012 Shortytall, All Rights Reserved.</p>
        <!-- /微博加关注 BEGIN-->
        <div class="full-right">
            <iframe width="136" height="24" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0" scrolling="no" border="0" src="http://widget.weibo.com/relationship/followbutton.php?language=zh_cn&width=136&height=24&uid=2525207340&style=2&btn=red&dpc=1"></iframe>
        </div>
        <!-- /微博加关注 END -->
    </footer>   
    </div> 
    <!-- /container -->
    <!--[if IE 6]>
    <script src="http://letskillie6.googlecode.com/svn/trunk/letskillie6.zh_CN.pack.js"></script>
    <![endif]-->
        <script src="http://storage.aliyun.com/pixels/assets/js/jquery-1.7.2.min.js"></script>
        <script src="http://storage.aliyun.com/pixels/assets/js/bootstrap.min.js"></script> 
        <script src="http://storage.aliyun.com/pixels/assets/js/application.js"></script>  
        <script src="http://storage.aliyun.com/pixels/assets/js/pixels.js"></script>
         <?php if ( isset ($js) && ! empty($js)): ?>
            <?php print $js;?>
        <?php endif ?>  

    <!-- Baidu Button BEGIN -->
        <script type="text/javascript" id="bdshare_js" data="type=slide&img=5&pos=right&uid=282508" ></script>
        <script type="text/javascript" id="bdshell_js"></script>
        <script type="text/javascript">
            var bds_config = {"bdTop":170};
            document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + new Date().getHours();
            </script>
    <!-- Baidu Button END -->
    <!-- RENREN Button BEGIN -->
        <script type="text/javascript">
            window.__fwdCfg = {
            "api_key" : "7ba2c0906089496290c6da9eaae429af",
            "text_hover_icon" : "icon-3-2.png"
            };
        </script>
        <script type="text/javascript" src="http://widget.renren.com/js/forward.js" async="true"></script>
    <!-- RENREN Button END -->
    </body>
</html>
