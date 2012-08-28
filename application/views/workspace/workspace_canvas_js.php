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
			   var canvas;
			   var context;
			   var status = 'add';
			   var rx =new Array(10);
			   var ry=new Array(10);
			   var a;
			   var rw=new Array(10);
			   var rc=new Array(10);
//cx-begin			   
			   var cubewidth=[10,15,20,30];
			   var cwlevel=3;			   
			   var worldwidth=$("#world").width();
			   var worldheight=$("#world").height();
			   var bgwidth=$("#worldbackground").width();
			   var bgheight=$("#worldbackground").height();
			   function getOffset(e)
				{
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
				
				function getPageCoord(element)    
				{
				  var coord = {x: 0, y: 0};
				  while (element)
				  {
					coord.x += element.offsetLeft;
					coord.y += element.offsetTop;
					element = element.offsetParent;
				  }
				  return coord;
				}
				
				function clearworld(){
					    var context = document.getElementById('world').getContext('2d');
						context.clearRect(0,0,worldwidth,worldheight);
					}//清除画布
					
				function clearbg(){
					    var context = document.getElementById('worldbackground').getContext('2d');
						console.log(bgwidth,bgheight);
						context.clearRect(0,0,bgwidth,bgheight);
					}//清除网格
				
				function paintbackground(){
					var background = document.getElementById('worldbackground');
					var bgcontext=background.getContext('2d');
					var cw=cubewidth[cwlevel];
					
					bgcontext.strokeStyle = "#DDDDDD";
					bgcontext.lineWidth = 1;
					
					var widthlen = Math.ceil(worldwidth/cw);
					var heightlen = Math.ceil(worldheight/cw);
					var tempcount=0;
					
					bgcontext.beginPath();
					bgcontext.moveTo(0,0);
					bgcontext.lineTo(0,worldheight);
					bgcontext.moveTo(worldwidth,0);
					bgcontext.lineTo(0,0);
					bgcontext.stroke();
					
					for(tempcount=1;tempcount<=widthlen;tempcount++){
						bgcontext.beginPath();
						bgcontext.moveTo(tempcount*cw-0.5,0);//减0.5是为了去掉canvas直线半渲染的效果
						bgcontext.lineTo(tempcount*cw-0.5,worldheight);
						bgcontext.stroke();
					}
					for(tempcount=1;tempcount<=heightlen;tempcount++){
						bgcontext.beginPath();
						bgcontext.moveTo(0,tempcount*cw-0.5);
						bgcontext.lineTo(worldwidth,tempcount*cw-0.5);
						bgcontext.stroke();
					}
				}
				
				function resetworld(){
					action.length=0;
					reAction.length=0;
					clearworld();
					$("#undo").css("background-position","-136px -102px");
                    $("#redo").css("background-position","-33px -102px");
				}
//cx-end			   
				 API.initialize = function(){
				    
					paintbackground();
					
			   		canvas = document.getElementById('world');
			   		context = canvas.getContext('2d');
			   		context.fillStyle = pickedColor;
					
					$( "#colorpad-window" ).draggable({ containment: [255,177,888,434],scroll:false });
					$( "#tool-window" ).draggable({ containment: [213,177,1098,503],scroll:false });
					$( "#color-window" ).draggable({ containment: [255,177,670,715],scroll:false });
										
					if (canvas && canvas.getContext) 
			   			{
							context = canvas.getContext('2d');

							$("#workplace").click(function(event) 
							{
									console.log(event.pageX);
									console.log(event.pageY);
							      	event.preventDefault();
							    
									mousex = getOffset(event).offsetX;//event.offsetX;
									mousey = getOffset(event).offsetY//event.offsetY;
									
							      	//console.log(mousex);
									//mousex = event.offsetX;
							      	//mousey = event.offsetY;
								
							      	x = (Math.floor(mousex/cubewidth[cwlevel]))*cubewidth[cwlevel];
							      	y = (Math.floor(mousey/cubewidth[cwlevel]))*cubewidth[cwlevel];
								    if(status == 'add')
									{
										context.fillStyle = pickedColor;
								       	context.fillRect(x,y,cubewidth[cwlevel]-1,cubewidth[cwlevel]-1);
								      	action.push({a:'a',c:pickedColor,x:x,y:y,w:cubewidth[cwlevel]-1});
										reAction.length=0;
										$("#redo").css("background-position","-33px -102px");
							        }
							        else
							        {
								       	context.clearRect(x,y,cubewidth[cwlevel]-1,cubewidth[cwlevel]-1);
								       	action.push({a:'d',c:pickedColor,x:x,y:y,w:cubewidth[cwlevel]-1});
										reAction.length=0;
										$("#redo").css("background-position","-33px -102px");
							        }
							        $("#undo").css("background-position","0 -68px");
						   
				           });

				           $("#clean").click(function(e){
				           	status = 'delete';
				           });	
				           $("#singleCube").click(function(e){
				           	status = 'add';
				           });
						} 
		//cx-begin
					function saveJSON(){	
						var dataUri	=JSON.stringify(action);
						window.open( dataUri, '模型文件' );					
					};
					
					function loadJSON(mapJson){
						action.length=0;//clear action
						action=JSON.parse(mapJson);
						//console.log(action);
						clearworld();
						replay(500);
					};
					
					$("#undo").click(function(){
						var actionlen=action.length;
						if(actionlen!=0){
							var lastaction = action[actionlen-1];							
							action.pop();
							replay(1);
							if(actionlen==1)
								$("#undo").css("background-position","-136px -102px");
							reAction.push(lastaction);
							$("#redo").css("background-position","-68px -102px");
						}
						 
					});
					
					$("#redo").click(function(){
						var actionlen=reAction.length;
						if(actionlen!=0){
							var lastaction= reAction[actionlen-1];
							reAction.pop();
							if(actionlen==1){
								$("#redo").css("background-position","-33px -102px");
							}
							action.push(lastaction);
							replay(0);
							 $("#undo").css("background-position","0 -68px");
							//console.log(reAction);
						}
						
					});
					
					var gridflag=1;
					$("#grid").click(function(){
						if(gridflag){
							clearbg();
							gridflag=0;
						}
						else{
							paintbackground();
							gridflag=1;
						}
					});
										
					var choice = 1;
					$(".cx-changebtn").click(function(event){
						var tempchoice=$(this).attr("id");
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
					});
					
					$("#changegrid").click(function(){
						cwlevel=choice;
						resetworld();
						clearbg();
						paintbackground();
					});
				}

				API.setcolor = function(color){
					pickedColor = "#"+color;
					return 'ok';
				}
				API.CubeJSON = function (){
						var dataUri	=JSON.stringify(action);
						return dataUri;
					};
			   function replay(speed){
					clearworld();
					$.each(action,function(index){
						a = $(this).attr('a');
						rx[index+1] = $(this).attr('x');
						ry[index+1] = $(this).attr('y');
						rc[index+1] = $(this).attr('c');
						rw[index+1] = $(this).attr('w');
						if(a == 'a')
						{							
							setTimeout( function() {
							context.fillStyle = rc[index+1];
							context.fillRect(rx[index+1],ry[index+1],rw[index+1],rw[index+1]);
							}, (speed*index));
							
						}
						else
						{
							setTimeout( function() {
							context.clearRect(rx[index+1],ry[index+1],rw[index+1],rw[index+1]);
							}, speed *index);
						}

					});
			   }
				return API;	
			})();
			Pixels2D.initialize();
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
							
							//voxelPainter.states.pickedColor=jobs.stringToHexConverter(colorconfirmed);
							//Pixels2D.pickedColor = jobs.stringToHexConverter(colorconfirmed);
							Pixels2D.setcolor(colorconfirmed);
							//$("#colorpad").fadeOut(200);
							$("#colorpad").hide();
							
						}
						
					}
					else{
						//$(".Current").css({'background-color': "transparent" });
						//colorconfirmed=color;
						//voxelPainter.states.pickedColor=jobs.stringToHexConverter("FF5656");
						alert("特特别闹了，透明的你看得见么");
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
						alert("要六位十六进制数字呢");	
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
		//cx-end
</script>
	<script type="text/javascript">
		$(document).ready(
			function(){
				$("#workpush").click(function(e){
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