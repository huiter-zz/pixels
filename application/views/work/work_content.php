<ul class="breadcrumb">
  <li>
    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li class="active"><span class="label"><?php echo $work['workid'];?>号作品</span></li>
</ul>
<span id="cubejson" style="display:none;"><?php echo file_get_contents($work['cubejson']);?></span>

<div>
	<div id="workplace" style="margin:0 auto;width:840px;">
		<canvas id="world" width="840 px" height="560 px" style="z-index:999;"></canvas>
	</div>
</div>
<div class="thumbnail thumbnail-vertical">
	<div id="speedslider" style="width:100px;display:inline-block;margin-left:20px;"></div>
	<div class="btn btn-small" id="play"><i class="icon-play"></i></div>
	<div class="btn btn-small" id="stop"><i class="icon-stop"></i></div>

	<span id="cp" class="input-append color offset1" data-color="#FFFFFF" data-color-format="hex">
	  <span class="label label-inverse">背景色
	  <span class="add-on"><i style="background-color: #FFFFFF"></i></span>
	  </span>
	</span>
	<div class="btn btn-small" id="save"><i class="icon-camera"></i></div>
	
	<div class="btn btn-small" id="rotate2left"><i class="ui-icon ui-icon-arrowthick-1-nw"></i></div>
	<div class="btn btn-small" id="rotate2right"><i class="ui-icon ui-icon-arrowthick-1-ne"></i></div>
	<div class="btn btn-small offset1" id="move2left"><i class="icon-arrow-left"></i></div>
	<div class="btn btn-small" id="move2right"><i class="icon-arrow-right"></i></div>
	<div class="btn btn-small" id="move2up"><i class="icon-arrow-up"></i></div>
	<div class="btn btn-small" id="move2down"><i class="icon-arrow-down"></i></div>
	<div class="btn btn-small" id="refresh"><i class="icon-refresh"></i></div>

</div>
<hr>
<div class="row">
    <div class="span1" style="marigin-left:20px;">
    <script type="text/javascript">
    var photos = [{src:"<?php echo $work['img'];?>", alt:"作品编号<?php echo $work['workid'];?>"}],
        re = [];
    for(var i=0,l=photos.length;i<l;i++){
        re.push("src["+i+"]="+photos[i].src);
        re.push("alt["+i+"]="+(photos[i].alt||""));
    }
    var ec = encodeURIComponent, url = ec(window.location.href), ti = ec(document.title);
    document.write('<a title="分享到点点" target="_blank" href="http://www.diandian.com/share?lo='+url+'&ti='+ti+'&type=image&'+re.join("&")+'"><img src="http://s.libdd.com/img/share/share-s-1.png" alt="分享到点点"/></a>');
	</script>
	</div>
	<div class="span1" style="margin-left:-20px;">
		<script type="text/javascript" charset="utf-8">
		(function(){
		  var _w = 32 , _h = 32;
		  var param = {
		    url:location.href,
		    type:'1',
		    count:'', /**是否显示分享数，1显示(可选)*/
		    appkey:'', /**您申请的应用appkey,显示分享来源(可选)*/
		    title:'作品编号<?php echo $work['workid'];?>', /**分享的文字内容(可选，默认为所在页面的title)*/
		    pic:"<?php echo $work['img'];?>", /**分享图片的路径(可选)*/
		    ralateUid:'', /**关联用户的UID，分享微博会@该用户(可选)*/
			language:'zh_cn', /**设置语言，zh_cn|zh_tw(可选)*/
		    rnd:new Date().valueOf()
		  }
		  var temp = [];
		  for( var p in param ){
		    temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
		  }
		  document.write('<iframe allowTransparency="true" frameborder="0" scrolling="no" src="http://hits.sinajs.cn/A1/weiboshare.html?' + temp.join('&') + '" width="'+ _w+'" height="'+_h+'"></iframe>')
		})()
		</script>
	</div>
	<div class="span1" style="margin-left:-20px;">
		<script type="text/javascript">
		(function(){
		var p = {
		url:location.href,
		showcount:'0',/*是否显示分享总数,显示：'1'，不显示：'0' */
		desc:'',/*默认分享理由(可选)*/
		summary:'',/*分享摘要(可选)*/
		title:'作品编号<?php echo $work['workid'];?>',/*分享标题(可选)*/
		site:'',/*分享来源 如：腾讯网(可选)*/
		pics:'<?php echo $work['img'];?>', /*分享图片的路径(可选)*/
		style:'201',
		width:39,
		height:39
		};
		var s = [];
		for(var i in p){
		s.push(i + '=' + encodeURIComponent(p[i]||''));
		}
		document.write(['<a version="1.0" class="qzOpenerDiv" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?',s.join('&'),'" target="_blank">分享</a>'].join(''));
		})();
		</script>
		<script src="http://qzonestyle.gtimg.cn/qzone/app/qzlike/qzopensl.js#jsdate=20111201" charset="utf-8"></script>
	</div>
	<div class="span1" style="margin-left:-20px;">
		<script type="text/javascript" src="http://widget.renren.com/js/rrshare.js"></script>
			<a name="xn_share" onclick="shareClick()" type="icon_large" href="javascript:;"></a>
			<script type="text/javascript">
				function shareClick() {
					var rrShareParam = {
						resourceUrl : '',	//分享的资源Url
						srcUrl : '',	//分享的资源来源Url,默认为header中的Referer,如果分享失败可以调整此值为resourceUrl试试
						pic : '<?php echo $work['img'];?>',		//分享的主题图片Url
						title : '作品编号<?php echo $work['workid'];?>',		//分享的标题
						description : ''	//分享的详细描述
					};
					rrShareOnclick(rrShareParam);
				}
		</script>
	</div>
</div>
<script type='text/javascript' charset='utf-8' src='http://open.denglu.cc/connect/commentcode?appid=91577den0UrhiUt6FQ0Wa92DG85pc3&postid=<?php echo $work['workid'];?>'></script>