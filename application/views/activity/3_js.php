		<script src="http://storage.aliyun.com/pixels/assets/js/close-pixelate.js"></script>
		<!-- Photo Pixelator BEGIN -->
		<script type="text/javascript">
			function getImageFromUrl() {
				var $imgUrl = document.getElementById('imageUrl');
				var img = new Image();
				img.src = $imgUrl.value;


				if (img.width > 720) {
					alert('请选择宽度小于720px的图片。');
					return;
				}
				
				var $imgOrig = document.getElementById('imgOriginal');
				var $imgProc = document.getElementById('imgProcessed');

				$imgOrig.setAttribute('src', $imgUrl.value);

				$('#original').attr('style','display:block;width:'+img.width+'px;margin:0 auto;');
			
			}
			var timecount;
			var count =0;
			function progress(){
								
				$("#progressbar").css("width",(count/200)*100+"%");
				count++;
				if(count==201){
					clearInterval(timecount);
					count=0;
				}
			}

			function pixelate(o) {
				clearInterval(timecount);
				count = 0;
				
				var pixelOptions = [{
					shape : 'diamond',
					resolution : 48,
					size : 50
				}, {
					shape : 'diamond',
					resolution : 48,
					offset : 24
				}, {
					shape : 'circle',
					resolution : 8,
					size : 6
				}];
				
				$("#progressbar").css("width","0%");
				
				document.getElementById('imgProcessed').outerHTML='<img id="imgProcessed" src="" style="margin:0 auto;display:block;" alt="">';

				var $imgOrig = document.getElementById('imgOriginal');
				var $imgProc = document.getElementById('imgProcessed');
				
				$imgProc.setAttribute('src', $imgOrig.src);
				timecount=setInterval("progress()",100);

				var img = $imgProc;
				if (img) {
					try {
						img.closePixelate(pixelOptions);
						
					} catch(err) {
						alert('错误：' + err);
						return;
					}
					$('#hack').attr('style','display:block;width:'+$imgOrig.width+'px;margin:0 auto;');
					$('#placeForImage').modal('show');
				} else {
					alert('没有图像');
				}

			}
			


				
		</script>