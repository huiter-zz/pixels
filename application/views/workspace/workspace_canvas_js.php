 	<script src="http://storage.aliyun.com/pixels/assets/js/canvas2image.js"></script>
	<script src="http://storage.aliyun.com/pixels/assets/js/base64.js"></script>
   	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/buttonjs/buttonjs.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/jquery-ui-1.8.20.custom.min.js"></script>

			
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
				
				var cubewidth=[10,15,20,30];//格子大小
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
					paintgrid();
					var tempjson=$("#cubejson").text();//'[{"a":"a","c":"#cccccc","x":210,"y":330,"w":29},{"a":"a","c":"#cccccc","x":330,"y":300,"w":29},{"a":"a","c":"#cccccc","x":270,"y":210,"w":29},{"a":"a","c":"#cccccc","x":210,"y":240,"w":29},{"a":"a","c":"#cccccc","x":450,"y":270,"w":29},{"a":"a","c":"#cccccc","x":360,"y":330,"w":29},{"a":"a","c":"#cccccc","x":360,"y":390,"w":29},{"a":"a","c":"#cccccc","x":420,"y":360,"w":29},{"a":"a","c":"#cccccc","x":30,"y":330,"w":29},{"a":"a","c":"#cccccc","x":60,"y":360,"w":29},{"a":"a","c":"#cccccc","x":90,"y":390,"w":29}]';
					loadJSON(tempjson);		
				}
				
				API.PaintOneCube = function(event){
				
					event.preventDefault();
					mousex = getOffset(event).offsetX;
					mousey = getOffset(event).offsetY

					x = (Math.floor(mousex/cubewidth[cwlevel]))*cubewidth[cwlevel];
					y = (Math.floor(mousey/cubewidth[cwlevel]))*cubewidth[cwlevel];
					if(status == 'add')
					{
						worldcontext.fillStyle = pickedColor;
						worldcontext.fillRect(x,y,cubewidth[cwlevel],cubewidth[cwlevel]);
						action.push({a:'a',c:pickedColor,x:x,y:y,w:cubewidth[cwlevel]});
						reAction.length=0;			
					}
					else
					{
						worldcontext.clearRect(x,y,cubewidth[cwlevel],cubewidth[cwlevel]);
						action.push({a:'d',c:pickedColor,x:x,y:y,w:cubewidth[cwlevel]});
						reAction.length=0;
					}
					
				}
				
				API.UnDo = function(){
					var actionlen=action.length;
					if(actionlen!=0){
						var lastaction = action[actionlen-1];
						reAction.push(lastaction);
						action.pop();
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
				function loadJSON(mapJson){
						action.length=0;//clear action
						action=JSON.parse(mapJson);
						$.each(cubewidth,function(index){
							if(action[0].width==cubewidth[index]){
								cwlevel=index;
							}
						});
						repaint();
						$("#undo").css("background-position","0 -68px");
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
					worldcontext.clearRect(0,0,worldwidth,worldheight);
				}//清除画布
				
				function cleargrid(){
					gridcontext.clearRect(0,0,bgwidth,bgheight);
				}//清除网格	
				
				return API;
			   })();
			Pixels2D.Initialize();
		</script>
		<script type="text/javascript">
		
			$("#workplace").click(function(event){
				Pixels2D.PaintOneCube(event);
				$("#redo").css("background-position","-33px -102px");//重做图标失效
				$("#undo").css("background-position","0 -68px");//撤销图标生效
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
			
			$( "#colorpad-window" ).draggable({ containment: 'parent',scroll:false });
			$( "#tool-window" ).draggable({ containment: 'parent',scroll:false });
			$( "#color-window" ).draggable({ containment: 'parent',scroll:false });
			
			$(".cx-changebtn").click(function(event){
				var tempchoice=$(this).attr("id");
				Pixels2D.ChangeGridWidth(tempchoice);		
			});
			
			$("#changegrid").click(function(){
				Pixels2D.RepaintGrid();
			});
			
			$("#dustbin").click(function(){
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
			var model='[{"a":"a","c":"#cccccc","x":210,"y":330,"w":29},{"a":"a","c":"#cccccc","x":330,"y":300,"w":29}]';
			$("#open").click(function(){
				Pixels2D.Load(model);
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
				});
				
				var colorconfirmed="#999";
				
				var oldcolor=0;
				
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
								
								oldcolor=(oldcolor+1)%10;
								
							}	
							
							colorconfirmed=$(".cx-colorvalue").attr("value");	
							
							$(".Current").css('background-color','#'+colorconfirmed);
							Pixels2D.setcolor(colorconfirmed);
							$("#colorpad").hide();
							
						}
						
					}
					else{
						alert("亲~透明色是没法画的哟~");
					}
					
				}); 
				$(".Current").click(function(){
					if(colorconfirmed!="透明色"){
						$(".Active").css('background-color','#'+colorconfirmed);
						$(".cx-colorvalue").val(colorconfirmed);
					}
					else{
						$(".Active").css({'background-color': "transparent" });
						$(".cx-colorvalue").val("输入值");
						
					}
					
				});
				$("#cx-colorpad-close").click(function(){
				
				    if(colorconfirmed!="透明色"){
						$("#colorpad").fadeOut(100);
						
					}

				});
				$("#colorp").click(function(){
				
					//$("#colorpad").fadeIn(400);
					$("#colorpad").show();
				});
				
				$(".cx-colorvalue").click(function(){
					$(".cx-colorvalue").select();
					$(".cx-colorvalue").focus();
					
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
                    alert(data['message']);
                    history.go(0);
              },"json")
            });	
			}
	);
	</script>