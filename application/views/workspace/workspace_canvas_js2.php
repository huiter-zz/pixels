<script src="http://storage.aliyun.com/pixels/assets/js/canvas2image.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/base64.js"></script>
<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/Jquery-ui/jquery-ui-1.8.20.js"></script>
<script src="http://storage.aliyun.com/pixels/assets/js/jquery.Jcrop.js"></script>
<script type="text/javascript">
			var Pixels2D = (function() {
				var API ={};
				var action = [];
				var reAction =[];
				var pickedColor = '#cccccc';
				var status = 'add';

				var worldcanvas;//画布
				var worldcontext;
				
				var gridcanvas;//网格
				var gridcontext;
				
				var cubewidth=[10,14,20,28];//格子大小
				var cwlevel=3;	
				var gridflag=true;	
				
				var worldwidth=$("#world").width();
				var worldheight=$("#world").height();
				var bgwidth=$("#worldbackground").width();
				var bgheight=$("#worldbackground").height();
				
				API.Initialize = function(){  
					gridcanvas=document.getElementById('worldbackground');
					gridcontext=gridcanvas.getContext('2d');
					worldcanvas=document.getElementById('world');
					worldcontext=worldcanvas.getContext('2d');
					worldcontext.translate(worldwidth/2,worldheight/2);
					if($("#cubejson").length > 0){
						var tempjson=$("#cubejson").text();
						loadJSON(tempjson);		
					}
					cleargrid();
					paintgrid();
				}
				
				var cubelist=[];
				API.ResetCubeList = function(){
					cubelist.length=0;
				}
				
				API.PaintOneCube = function(event){
				
					event.preventDefault();
					mousex = getOffset(event).offsetX-(worldwidth/2);
					mousey = getOffset(event).offsetY-(worldheight/2);

					x = (Math.floor(mousex/cubewidth[cwlevel]))*cubewidth[cwlevel];
					y = (Math.floor(mousey/cubewidth[cwlevel]))*cubewidth[cwlevel];
					
					if(cubelist.length==0){
						if(status == 'add')
							{
								worldcontext.fillStyle = pickedColor;
								worldcontext.fillRect(x,y,cubewidth[cwlevel],cubewidth[cwlevel]);
								action.push({a:'a',c:pickedColor,x:x,y:y,w:cubewidth[cwlevel]});
								reAction.length=0;			
							}
						else if(status =='delete')
							{
								worldcontext.clearRect(x,y,cubewidth[cwlevel],cubewidth[cwlevel]);
								action.push({a:'d',c:pickedColor,x:x,y:y,w:cubewidth[cwlevel]});
								reAction.length=0;
							}
						else if(status =='getcolor'){
								var current_pos_color="#FFFFFF";
								var tempflag=0;
								if(action.length!=0){
									//console.log(action);
									var i=action.length-1;
									for(;i>-1;i--){
										if(action[i].x==x&&action[i].y==y){
											tempflag=1;
											break;
										}
									}
									
									if(tempflag==1&&action[i].a=='a'){
										current_pos_color=action[i].c;
									}
								}
								current_pos_color=current_pos_color.substring(1,7);
								//console.log(current_pos_color);
								$("#colorpad").show();
								$(".cx-colorvalue").val(current_pos_color);
								$("#cx-previewColor").click();
						    }
						/*
							event.preventDefault();
							mousex = getOffset(event).offsetX-(worldwidth/2);
							mousey = getOffset(event).offsetY-(worldheight/2);
							console.log(mousex,mousey);
					
					
							worldcontext.beginPath();
							worldcontext.moveTo(mousex,mousey);
							worldcontext.lineTo(mousex+2,mousey+2);
							worldcontext.stroke();
							var myImageData = worldcontext.getImageData(mousex, mousey, 10, 10);
							console.log(myImageData);*/
						
							cubelist.push({cx:x,cy:y});
					}
					else{
						var flag=1;
						$.each(cubelist,function(index){
							if(cubelist[index].cx==x&&cubelist[index].cy==y){
								flag=0;
								return false;
							}
						});
						if(flag==1){
							if(status == 'add')
								{
									worldcontext.fillStyle = pickedColor;
									worldcontext.fillRect(x,y,cubewidth[cwlevel],cubewidth[cwlevel]);
									action.push({a:'a',c:pickedColor,x:x,y:y,w:cubewidth[cwlevel]});
									reAction.length=0;			
								}
							else if(status =='delete')
								{
									worldcontext.clearRect(x,y,cubewidth[cwlevel],cubewidth[cwlevel]);
									action.push({a:'d',c:pickedColor,x:x,y:y,w:cubewidth[cwlevel]});
									reAction.length=0;
								}
								cubelist.push({cx:x,cy:y});
						}
					}
				}
				
				API.UnDo = function(){
					var actionlen=action.length;
					if(actionlen!=0){
						var lastaction = action[actionlen-1];
						reAction.push(lastaction);
						action.pop();
						//console.log(action.length);
						repaint();
						
						if(actionlen==1)
						$("#undo").css("background-position","-136px -102px");
						
						$("#redo").css("background-position","-68px -102px");
					}
				}
				
				API.ReDo = function(){
					var actionlen=reAction.length;
					if(actionlen!=0){
						var lastaction= reAction[actionlen-1];
						reAction.pop();
						action.push(lastaction);
						repaint();
						
						if(actionlen==1){
							$("#redo").css("background-position","-33px -102px");
						}
						 $("#undo").css("background-position","0 -68px");
					}
				}
				
				API.SetStatus = function(sta){
					status = sta;
				}
				
				API.SwitchGrid = function(){
					if(gridflag){
						cleargrid();
						gridflag=false;
					}
					else{
						paintgrid();
						gridflag=true;
					}
				}
				
				API.ChangeGridWidth = function(tempchoice){
					switch(tempchoice){
					case('num0'):
						choice=0;
						break;
					case('num1'):
						choice=1;
						break
					case('num2'):
						choice=2;
						break;
					case('num3'):
						choice=3;
						break;
					default:
						choice=1;
						break;
				}		
				}
				
				API.RepaintGrid = function(){
					cwlevel=choice;
					resetworld();
				}
				
				API.setcolor = function(color){
					pickedColor = "#"+color;
					return 'ok';
				}
				
				API.CubeJSON = function (){
						var dataUri	=JSON.stringify(action);
						return dataUri;
				};
				
				API.ResetWorld = function(){
					resetworld();
				}
				
				API.Save = function(){
					var win=window.open('','','top=10000,left=10000');  
					win.document.write(JSON.stringify(action));  
				}
				
				API.Load = function(model){
					loadJSON(model);	
				}
				
				API.Move2Left=function(){
					$.each(action,function(index){
						action[index].x=action[index].x-cubewidth[cwlevel];
					});
					repaint();
				}
				API.Move2Right=function(){
					$.each(action,function(index){
						action[index].x=action[index].x+cubewidth[cwlevel];
					});
					repaint();
				}
				API.Move2Up=function(){
					$.each(action,function(index){
						action[index].y=action[index].y-cubewidth[cwlevel];
					});
					repaint();
				}
				API.Move2Down=function(){
					$.each(action,function(index){
						action[index].y=action[index].y+cubewidth[cwlevel];
					});
					repaint();
				}
				
				API.InputImage=function(img){
					loadJSON(img);
				}
				
				function loadJSON(mapJson){
						action.length=0;//clear action
						if(mapJson=='PictureFail'){
							$('<div id="Ialert" class="alert alert-error alert-block" style="width:330px;"><button type="button" class="close" data-dismiss="alert">×</button><strong>错误!</strong>亲~图片来源(百度相册、163神马的...)不支持我们的处理哦...换个图片地址试试~~</div>').appendTo("#imagetoolsection");
						}
						else{
							action=JSON.parse(mapJson);
							$.each(cubewidth,function(index){
								if(action[0].w==cubewidth[index]){
									cwlevel=index;
								}
							});
							repaint();
							$("#undo").css("background-position","0 -68px");
							cleargrid();
							paintgrid();
							$('#inputimage').modal('hide');
						}
				};
				
				function resetworld(){
					action.length=0;
					reAction.length=0;
					$("#undo").css("background-position","-136px -102px");
                    $("#redo").css("background-position","-33px -102px");
					cleargrid();
					paintgrid();
					clearworld();
				}
				
				function paintgrid(){
					var cw=cubewidth[cwlevel];	
					gridcontext.strokeStyle = "#DDDDDD";
					gridcontext.lineWidth = 1;
					
					var widthlen = Math.ceil(worldwidth/cw);
					var heightlen = Math.ceil(worldheight/cw);
					var tempcount=0;
					
					gridcontext.beginPath();
					gridcontext.moveTo(0,0);
					gridcontext.lineTo(0,worldheight);
					gridcontext.moveTo(worldwidth,0);
					gridcontext.lineTo(0,0);
					gridcontext.stroke();
					
					for(tempcount=1;tempcount<=widthlen;tempcount++){
						gridcontext.beginPath();
						gridcontext.moveTo(tempcount*cw-0.5,0);//减0.5是为了去掉canvas直线半渲染的效果
						gridcontext.lineTo(tempcount*cw-0.5,worldheight);
						gridcontext.stroke();
					}
					for(tempcount=1;tempcount<=heightlen;tempcount++){
						gridcontext.beginPath();
						gridcontext.moveTo(0,tempcount*cw-0.5);
						gridcontext.lineTo(worldwidth,tempcount*cw-0.5);
						gridcontext.stroke();
					}
				}
				
				function getOffset(e){
				  var target = e.target;
				  if (target.offsetLeft == undefined)
				  {
					target = target.parentNode;
				  }
				  var pageCoord = getPageCoord(target);
				  var eventCoord =
				  {     
					x: window.pageXOffset + e.clientX,
					y: window.pageYOffset + e.clientY
				  };
				  var offset =
				  {
					offsetX: eventCoord.x - pageCoord.x,
					offsetY: eventCoord.y - pageCoord.y
				  };
				  return offset;
				}
				
				function getPageCoord(element){
				  var coord = {x: 0, y: 0};
				  while (element)
				  {
					coord.x += element.offsetLeft;
					coord.y += element.offsetTop;
					element = element.offsetParent;
				  }
				  return coord;
				}
				
				function repaint(){
					clearworld();
					$.each(action,function(index){
						var a = $(this).attr('a'),
							x = $(this).attr('x');
							y= $(this).attr('y');
							c= $(this).attr('c');
							w = $(this).attr('w');
						if(a == 'a')
						{							
							worldcontext.fillStyle = c;
							worldcontext.fillRect(x,y,w,w);				
						}
						else
						{
							worldcontext.clearRect(x,y,w,w);
						}
					});
				}
				
				function clearworld(){
					worldcontext.clearRect(-worldwidth/2,-worldheight/2,worldwidth,worldheight);
				}//清除画布
				
				function cleargrid(){
					gridcontext.clearRect(0,0,bgwidth,bgheight);
				}//清除网格	
				
				return API;
			   })();
			Pixels2D.Initialize();
			$('.cx-button').tooltip({placement:'right'});
			$('.cx-icon-palette').tooltip({placement:'top'});
			$('.cx-button-colorpad').tooltip({placement:'right'});
			$('.Active').tooltip({placement:'right'});
			$('.Current').tooltip({placement:'right'});
			$('.cx-dialog-label').tooltip({placement:'right'});
			$('.cx-colorvalue').tooltip({placement:'right'});
		</script>
<script type="text/javascript">
			var status="none";
			$("#workplace").mousedown(function(event){
				status="mousedown";
				Pixels2D.ResetCubeList();
				Pixels2D.PaintOneCube(event);
				$("#redo").css("background-position","-33px -102px");//重做图标失效
				$("#undo").css("background-position","0 -68px");//撤销图标生效
				$("#workplace").mousemove(function(event){
					if(status=="mousedown")
						Pixels2D.PaintOneCube(event);
				});
			}); 
			$("#workplace").mouseup(function(event){
				status="mouseup";
				Pixels2D.ResetCubeList();
			});
			$("#clean").click(function(e){
				Pixels2D.SetStatus('delete');
			});	
			$("#singleCube").click(function(e){
				Pixels2D.SetStatus('add');
			});
			$("#undo").click(function(){
				Pixels2D.UnDo();		 
			});
			
			$("#redo").click(function(){
				Pixels2D.ReDo();		
			});
			
			$("#grid").click(function(){
				Pixels2D.SwitchGrid();
			});
			
			$("#getcolor").click(function(event){
				Pixels2D.SetStatus('getcolor');
			});
			var canvasx = $("#worldbackground").offset().left;//position().left;
			var canvasy = $("#worldbackground").offset().top;
			var canvasw = $("#worldbackground").width();
			var canvash = $("#worldbackground").height();
			
			$( "#colorpad-window" ).draggable({ containment: [canvasx,canvasy,canvasx+canvasw-$("#colorpad").width(),canvasy+canvash-$("#colorpad").height()],scroll:false });
			
			$( "#tool-window" ).draggable({ containment: [canvasx-45,canvasy,canvasx+canvasw+2,canvasy+canvash-$(".cx-toolbar").height()],scroll:false });
			
			$( "#color-window" ).draggable({ containment: [canvasx,canvasy-45,canvasx+canvasw-$(".cx-colorpalette").width()-20,canvasy+canvash+4],scroll:false });
			
			$("#movetable").draggable({containment:[canvasx,canvasy,canvasx+canvasw-$("#Move").width(),canvasy+canvash-$("#Move").height()],scroll:false});
			
			$(".cx-changebtn").click(function(event){
				var tempchoice=$(this).attr("id");
				Pixels2D.ChangeGridWidth(tempchoice);		
			});
			
			$("#changegrid").click(function(){
				Pixels2D.RepaintGrid();
			});
			
			$("#clearconfirmed").click(function(){
				Pixels2D.ResetWorld();
			});
			
			$("#left").click(function(){
				Pixels2D.Move2Left();
			});
			$("#right").click(function(){
				Pixels2D.Move2Right();
			});
			$("#up").click(function(){
				Pixels2D.Move2Up();
			});
			$("#down").click(function(){
				Pixels2D.Move2Down();
			});
			
			$("#save").click(function(){
				Pixels2D.Save();
			});

			$("#openconfirmed").click(function(){
				var model = $('#modeltext').attr('value');
				Pixels2D.Load(model);
			});
			
			var img = new Image();
			var imgw;
			var imgh;
			var spiltx=0;
			var spilty=0;
			var spiltw=1;
			var spilth=1;
			
			var Itempflag=0;
			$("#apply_input").click(function(){
				$(this).html('导入中...');
				$(this).attr('disabled','true');

                if($("#Ialert").length>0){
                    $("#Ialert").alert('close');//清空已有错误信息
                }
				
				$('#Iprogresssection').css({'display':'none'});
				
				var $imgUrl = document.getElementById('imageURL');

				var reg=/((.png)|(.jpg)|(.jpeg))$/;

				if((($imgUrl).value)==""){
                    Ishowerror('亲~输个地址吧~');
				}
				else if(!(reg.test(($imgUrl).value))){
                    Ishowerror('亲~我们只能处理png和jpg格式的图片哦...换个地址吧~~');
				}
				else{
					//console.log( document.getElementById('imageURL'));
                    if(Itempflag!= 0){
                        jcrop_api.destroy();
                    }

                    img.src = $imgUrl.value;

                    if(img.complete){//alert("OK"); 图片已经在浏览器缓存中了
                       Iloadsuccess($imgUrl.value);
                    }
                    else{
                        img.onload=function(){
                            //alert("here");
                            img.onload=null;
                            Iloadsuccess($imgUrl.value);
                        };
                        img.onerror=function(){
                            Ishowerror('提供图片的地址不支持我们的引用，换一个地址吧~');
                        };    
                    }
				}
			});

            function Ishowerror(info){//显示错误信息
                if($("#Ialert").length>0){
                    $("#Ialert").alert('close');//清空已有错误信息
                }
                if(Itempflag!= 0){
                            jcrop_api.destroy();
                    }

                $("#apply_input").html('导入图片');
                $("#apply_input").removeAttr("disabled"); 

                $("#imageholder").attr('src','http://storage.aliyun.com/pixels/assets/img/failpic.png');
                $("#imageholder").css({'visibility':'visible'});    
                //document.getElementById('imageholder').style.visibility='visible';

                $('<div id="Ialert" class="alert alert-error alert-block" style="width:330px;"><button type="button" class="close" data-dismiss="alert">×</button><strong>错误!</strong>'+info+'</div>').appendTo("#imagetoolsection");
            }
            function Iloadsuccess(url){

                $("#apply_input").html('导入图片');
                $("#apply_input").removeAttr("disabled"); 

                imgw=$('#imageholder').width();
                imgh=$('#imageholder').height();

                $('#imageholder').attr('src',url);//显示图片 

                jQuery(function() {
                    jQuery('#imageholder').Jcrop({
                        onChange: getspiltposition,
                        onSelect: getspiltposition
                    },function(){
                      jcrop_api = this;
                    });
                });

                $('#Iprogresssection').css({'display':'block'});//显示按钮
                $('.jcrop-holder').css({'margin':'0 auto'});//裁剪控件居中

                //console.log(imgw);
                Itempflag=1;       
            }
			function getspiltposition(c){
				if(c.w!=0&&c.h!=0){
					spiltx=c.x/imgw;
					spilty=c.y/imgh;
					spiltw=c.w/imgw;
					spilth=c.h/imgh;
				}
				else{
					 spiltx=0;
					 spilty=0;
					 spiltw=1;
					 spilth=1;
				}
				//console.log(spiltx+" "+spilty+" "+spiltw+" "+spilth);
			}
			
			var Icolor=1;
			var Ipixel=14;
			var Iprocess=2;
			var Iwhite=0;
			
			$('.Icolorchoice').click(function(){
				if($(this).html()=='黑白'){
					Icolor=0;
				}
				else if($(this).html()=='彩色'){
					Icolor=1;
				}
				$('#Icolor').html("图像色彩-"+$(this).html());
			});
			$('.Ipixelschoice').click(function(){
				if($(this).html()=='小'){
					Ipixel=10;
				}
				else if($(this).html()=='较小'){
					Ipixel=14;
				}
				else if($(this).html()=='中'){
					Ipixel=20;
				}
				else if($(this).html()=='大'){
					Ipixel=28;
				}
				$('#Ipixels').html("像素大小-"+$(this).html());
			});
			$('.Iprocesschoice').click(function(){
				if($(this).html()=='填充画布'){
					Iprocess=0;
				}
				else if($(this).html()=='裁剪'){
					Iprocess=1;
				}
				else if($(this).html()=='按比例拉伸'){
					Iprocess=2;
				}
				$('#Iprocessway').html("裁剪方式-"+$(this).html());
			});
			$('.Iwhitechoice').click(function(){
				if($(this).html()=='否'){
					Iwhite=0;
				}
				else if($(this).html()=='是'){
					Iwhite=1;
				}
				$('#Iwhite').html("是否除白-"+$(this).html());
			});
			
			$("#progress_input").click(function(){
				$(this).html('处理中...');
				$(this).attr('disabled',"true");
				$.ajax({
						  url: "http://pixels.huiter.me/imagetopixel.php",
						  data: {
							'url': $('#imageURL').val(),
							'canvasWidth': $("#world").width(),//worldwidth,
							'canvasHeight': $("#world").height(),//worldheight,
							'pixelWidth': Ipixel,
							'color': Icolor,
							'white': Iwhite,
							'spiltx':spiltx,
							'spilty':spilty,
							'spiltw':spiltw,
							'spilth':spilth,
							'way':Iprocess
						  },
						  success: function(data) {
							$("#progress_input").html('处理图片');
							$("#progress_input").removeAttr("disabled"); 
							return Pixels2D.InputImage(data);
							//return $('#result').val(data.toString());
						  },
						  error: function(data) {
						    $(this).html('处理图片');
							$(this).removeAttr("disabled"); 
							return console.log(data);
						  }
						});
				if($("#Ialert").length>0)
					$("#Ialert").alert('close');
			});
			
			
			
			var flag_inputhexcolor=false;
			$(document).keydown(function(event){
			  //console.log($("#colorpad").css('display'));		
             // console.log(event.keyCode);	  
			  var tempflag=1;
			    $.each($(".modal"),function(){	
					if(navigator.userAgent.indexOf("Firefox")>0){
						$str = $(this).css('display');
					}
					else{
						$str = $.trim($(this).css('display'));
					}
					if($str =='block'){
						tempflag=0;
					}
			
				});

				if(tempflag!=0){
					switch(event.keyCode){
						case 65:
						if(!flag_inputhexcolor)
							$("#singleCube").click();
							break;
						case 69:
						if(!flag_inputhexcolor)
							$("#clean").click();
							break;
						case 71:
							$("#grid").click();
							break;
						case 83:
							$("#save").click();
							break;
						case 85:
							$("#undo").click();
							break;
						case 82:
							$("#redo").click();
							break;
						case 68:
						if(!flag_inputhexcolor)
							$("#dustbin").click();
							break;
						case 79:
							$("#open").click();
							break;
					}
				}
			});
			$(".cx-button").click(function(){
				if(this.id=="singleCube"||this.id=="clean"||this.id=='getcolor'){
					$(".cx-button").removeClass("focusaction");
					$("#"+this.id).addClass("focusaction");
				}
			});
		</script>
<script type="text/javascript">
			//cx-begin					
				//选色板取色
				var currentcolor;//当前选择的颜色
				
				$(".QuickColor").click(function(){
				
					currentcolor=$(this).attr("title");
					
					if(currentcolor!="透明色"){				
					
						$(".cx-colorvalue").val(currentcolor);
					
						
						$(".Active").css('background-color','#'+currentcolor);
						
					}	
                    else{
					
						$(".Active").css({'background-color': "transparent" });
						$(".cx-colorvalue").val("输入值");
						
					}	
					$(".cx-colorvalue").blur();
				});
				
				var colorconfirmed="#DDDDDD";
				
				var oldcolor=1;
				
				$("#cx-applyColor").click(function(){
				
				    if(currentcolor!="透明色"){
					
						var hexnumber=$(".cx-colorvalue").attr("value");	
						
						if(checkNumber(hexnumber)=="1"){
						
						    //改变历史颜色栏的样式
							if(currentcolor!=colorconfirmed){
							
								$(".ocolor").removeClass("ocolorsel");
								
								$("#ocolor"+oldcolor).addClass("ocolorsel");
								
								$("#ocolor"+oldcolor).css('background-color','#'+hexnumber);
								
								$("#ocolor"+oldcolor).attr({"title":hexnumber});
								
								oldcolor=(oldcolor+1)%12;
								
							}	
							
							colorconfirmed=$(".cx-colorvalue").attr("value");	
							
							$(".Current").css('background-color','#'+colorconfirmed);
							Pixels2D.setcolor(colorconfirmed);
							$("#colorpad").hide();
							$("#singleCube").click();
						}
						
					}
					else{
						alert("亲~透明色是没法画的哟~");
					}
					
				}); 
				$(".Current").click(function(){
					if(colorconfirmed!="透明色"){
						$(".Active").css('background-color',colorconfirmed);
						$(".cx-colorvalue").val(colorconfirmed);
						$(".cx-colorvalue").blur();
					}
					else{
						$(".Active").css({'background-color': "transparent" });
						$(".cx-colorvalue").val("输入值");
						$(".cx-colorvalue").blur();
						
					}
					
				});
				$("#cx-colorpad-close").click(function(){
				
				    if(colorconfirmed!="透明色"){
						$("#colorpad").fadeOut(100);
						
					}
					$(".cx-colorvalue").blur();
				});
				$("#colorp").click(function(){
				
					//$("#colorpad").fadeIn(400);
					$("#colorpad").show();
				});
				
				$(".cx-colorvalue").click(function(){
					$(".cx-colorvalue").select();
					$(".cx-colorvalue").focus();
					
				});
				$(".cx-colorvalue").focus(function(){
					flag_inputhexcolor=true;	//全局变量flag_inputhexcolor			
				});
				$(".cx-colorvalue").blur(function(){
					flag_inputhexcolor=false;	//全局变量flag_inputhexcolor	
						//console.log(flag_inputhexcolor);
				});
				function checkNumber(hex){
					var rightnumber=1;
					if(hex.length<6)
						alert("是六位的十六进制数字哟~");	
					else{
						for(var i=0;i<6;i++){
							var number=hex.charAt(i);
							if(!((number>='0'&&number<='9')||(number>='a'&&(number<='f'))||(number>='A'&&number<="F"))){
								rightnumber=0;
								break;
							}		
						}	
					}
					return rightnumber;
				};
				$("#cx-previewColor").click(function(){
					
					var hex=$(".cx-colorvalue").attr("value");
					var rightnumber=checkNumber(hex);
					if(rightnumber){
						currentcolor=$(".cx-colorvalue").attr("value");		
						$(".Active").css('background-color','#'+currentcolor);	
						$(".cx-colorvalue").blur();
					}
					else
						alert("十六进制数字的每一位是0-9或者a-f");
				});
				
				//选择历史颜色栏的颜色
				
				$(".ocolor").click(function(){
				    var currentoldcolor =$("#"+this.id);
					if(currentoldcolor.attr("title")!="历史颜色"){					
						$(".ocolor").removeClass("ocolorsel");						
						currentoldcolor.addClass("ocolorsel");							
						currentcolor= currentoldcolor.attr("title");//将选色板当前颜色改为选择的历史颜色；						
						$(".Active").css('background-color','#'+currentcolor);//改变选色板的选定颜色样式						
						$(".cx-colorvalue").val(currentcolor);//改变选色板文字框值
						
						colorconfirmed=currentoldcolor.attr("title");//将选色板选定颜色改为选择的历史颜色；							
						$(".Current").css('background-color','#'+colorconfirmed);//改变选色板的选定颜色样式
						//voxelPainter.states.pickedColor=jobs.stringToHexConverter(colorconfirmed);//绘制的颜色
						Pixels2D.setcolor(colorconfirmed);
					}
					
				});
		</script>
<script type="text/javascript">
		$(document).ready(
			function(){
				$("#workpush").click(function(e){
					//Pixels2D.makebackground();

 					var oCanvas = document.getElementById("world");
 					var strDataURI = oCanvas.toDataURL('image/png'); 
 					$('#picture').attr("src",strDataURI);
        		});
	
				$("#workpost").click(function(e){
					var oCanvas = document.getElementById("world");
		 			var strDataURI = oCanvas.toDataURL('image/png'); 
		 			strDataURI = strDataURI.replace('data:image/png;base64,','')
		 			var tag1 = $('#tag1').attr('value');
		 			var tag2 = $('#tag2').attr('value');
		 			var tag3 = $('#tag3').attr('value');
		 			var cubejson = Pixels2D.CubeJSON();
 			 		e.preventDefault();
 			 		$('#worksubmit').modal('hide');
                    $.post("/api/v1/work",{img:strDataURI,tag1:tag1,tag2:tag2,tag3:tag3,cubejson:cubejson},function( data ) {
                     window.location.href="/work/"+data['workid'];
                    //history.go(0);
              },"json")
            });	
			}
	);
	</script>        