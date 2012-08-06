<script language="javascript" type="text/javascript">

function qrpost() 
{
    var message = $("#showqr").children("img").attr("src");
    $.post("/api/submit/qr",{message:message},function( data ) {
      alert(data['error']);
      },"json");

}


function qrchange() 
{
    document.location.reload();
}
function share(title,content,url,img)
{
	str="";
  str+="<div class='btn-group'>";
  str+="<a title='分享到:' class='btn'>分享到:</a>";
	str+="<a title='分享到新浪微博' class='btn btn-warning' target='_blank' href='http://v.t.sina.com.cn/share/share.php?pic="+img+"&title="+title+"&url="+url+"&rcontent="+content+"'>新浪微博</a>";
        str+="<a title='分享到腾讯微博' class='btn btn-warning' target='_blank' href='http://v.t.qq.com/share/share.php?title="+title+"&pic="+img+"&url="+url+"'>腾讯微博</a>";
  str+="<a title='分享到QQ空间和朋友网' class='btn btn-warning' target='_blank' href='http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title="+title+"&pics="+img+"&url="+url+"'>QQ空间</a>";
	//str+="<a class='btn btn-warning' target='_blank' href='http://share.renren.com/share/buttonshare.do?title="+title+"&link="+url+"&rcontent="+content+"&image="+img+"'>人人网</a>";
  //	str+="<a class='btn' target='_blank' href='http://www.kaixin001.com/repaste/share.php?rtitle="+title+"&rurl=="+url+"&rcontent="+content+"'>开心网</a>";
  //	str+="<a class='btn btn-warning' target='_blank' href='http://www.douban.com/recommend/?title="+title+"&url="+url+"&rcontent="+content+"'>豆瓣</a>";
	str+="</div>";
	return str;
}
function hideall()
{
	document.getElementById('noticefirst').style.display="none";
	document.getElementById('notice').style.display="none";
	document.getElementById('noticeerror').style.display="none";
	document.getElementById('showqr').style.display="none";
}
function shengcheng(neirong)
{
	document.getElementById('showqr').style.display="none";
	document.getElementById('notice').style.display="none";
	document.getElementById('noticeerror').style.display="none";
	document.getElementById('noticefirst').innerHTML="<div class='progress progress-striped active'><div class='bar' style='width: 100%;'></div></div><div>正在生成二维码，请稍后……</div>";
	document.getElementById('noticefirst').style.display="block";
	imagewidth=document.getElementById('imageside').value;
	imageheight=document.getElementById('imageside').value;
	imagebm="UTF-8";
	imagerc="L";
	imagemargin="0";
  	if(imagewidth*imageheight>=3000000)
  	{
  		hideall();
  		document.getElementById('noticeerror').innerHTML="尺寸太大！请重新输入尺寸";
  		document.getElementById('noticeerror').style.display="block";
  	}
  	else
  	{
  		var myDate = new Date();
		image="https://chart.googleapis.com/chart?cht=qr&chs="+imagewidth+"x"+imageheight+"&choe="+imagebm+"&chld="+imagerc+"&chl="+neirong;
          document.getElementById('showqr').innerHTML="<img class='JIATHIS_IMG_OK' style='width: 300px; padding: 0px; margin: 0px; ' src='"+image+"'>"+share("我参加了像素の逆袭第一期活动。这是我想说的话哦，想知道我说得是什么吧？哈哈。用像素去说那些话。——像素の逆袭"+myDate.toLocaleTimeString(),"我参加了像素の逆袭第一期活动。这是我想说的话哦，想知道我说得是什么吧？哈哈。用像素去说那些话。——像素の逆袭","http://pixels.sinaapp.com/activity/1",encodeURIComponent(image))+"</p>";
		//$('#qrimg').attr("src",image);
		var img = new Image();
		img.src = image;
		img.onload = function()
		{
			hideall();
			document.getElementById('showqr').style.display="block";
                  document.getElementById('notice').innerHTML="<p>二维码生成成功！&nbsp;&nbsp<input type='button'  id='qrpost' value='匿名提交' onclick='qrpost()' class='btn' title='匿名提交'></p>";
                 	
                        document.getElementById('notice').style.display="block"; 
			//<a href='"+image+"' target='_blank' title='点击查看大图'>点击查看大图</a>
		};
		img.onerror = function()
		{
			hideall();
			document.getElementById('noticeerror').innerHTML="二维码生成失败，可能是您输入的内容太多或者网速太慢，请重新生成。";
			document.getElementById('noticeerror').style.display="block";
		};
        }
}
function shengchengtext()
{
	neirong=document.getElementById('qrtext').value;
	shengcheng(neirong);
}


</script>