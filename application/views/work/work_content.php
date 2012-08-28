<ul class="breadcrumb">
  <li>
    像素の逆袭<span class="divider"><i class="icon-chevron-right"></i></span>
  </li>
  <li class="active"><span class="label">作品廊</span></li>
</ul>
<div class="control-group form-inline">
    <span class="controls">
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
	</span>
	<span class="controls">
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
	</span>
	<span class="controls">
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
	</span>
</div>




<script type='text/javascript' charset='utf-8' src='http://open.denglu.cc/connect/commentcode?appid=91577den0UrhiUt6FQ0Wa92DG85pc3&postid=<?php echo $work['workid'];?>'></script>