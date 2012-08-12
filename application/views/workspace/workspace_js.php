 	<script src="http://storage.aliyun.com/pixels/assets/js/canvas2image.js"></script>
	<script src="http://storage.aliyun.com/pixels/assets/js/base64.js"></script>
   	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/buttonjs/jquery.dragndrop.js"></script>
   	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/buttonjs/buttonjs.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/jquery-ui-1.8.20.custom.min.js"></script>
   	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/Underscore.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/backbone.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/Three.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/TrackballControls.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/colorpicker.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/eye.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/utils.js"></script>
	<script type="text/javascript" src="http://storage.aliyun.com/pixels/assets/js/canvasjs/layout.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {


	});

	</script>

	<script type="text/javascript">
		$(document).ready(
			function(){
				/*
				 * 进行DOM的渲染
				 */
				/*
				 * 合并ThreeJS与backbone.js
				 */
				/*
				 * 全局的参数设置，初始化一次以后就不会在改变的变量
				 */
				var voxel={},
					cubes,//保存方块数组
					cubesView,//cubesView类
					cbsView,//实例化cubesView
					commands,
					controller,                             
					TOOLTYPE={
						/*
						 * 工具栏状态类型
						 */
						NONE:-1,
						SINGLECUBE:0,
						LINE:1,
						CLEAN:2,
						RECTANGLE:3,
						CIRCLE:4
					}
					
				;
				voxel.config={
					
					containerID:"container",
					
					canvasID:"voxelCanvas",
								  
					canvasWidth:700,
					
					canvasHeight:450,
					
					voxelSize:50,
					
					planeSize:5000,
					
					maxNum:500//每个维度最多500个像素块      
				};
				/*
				 * cameraController
				 */
				function cameraController(camera,scene,controls){
					
					var _camera=camera,//combinedCamera
						_scene=scene,
						_position=new THREE.Vector3(0,1000,0),
						_controls=controls,
						_rotation
					;
					_camera.position=_position;
				  
					this.to2D=function(){
						_controls.init();
						_camera.position.set(0,1000,0);
						_camera.up.set(0,1,0);
						_camera.toOrthographic();
						/*
						 * 启动control
						 */
						_controls.enabled=true;
						
					};
					
					this.to3D=function(){
						_controls.init();
						_camera.position.set(600,600,600);
						_camera.up.set(0,1,0);
						_camera.toPerspective();
						/*
						 * 启用trackball
						 */
						_controls.enabled=true;
					};
					
					this.Up=function(){
						init();
						_camera.position.set(0,1000,0);
					};
					this.Down=function(){
						init();
						_camera.position.set(0,-1000,0);
					};
					this.Left=function(){
						init();
						_camera.position.set(-1000,0,0);
					};
					this.Right=function(){
						init();
						_camera.position.set(1000,0,0);

					};
					
					function init(){
						
						_camera.toPerspective();
						_controls.enabled=false;
					}
				}
				/*
				 * Cube模型,以供配置,为每一个cube都进行可配置
				 */
				cubeModel=Backbone.Model.extend({
					
					defaults:function(){
						
						return{
							
							cube:null,
							color:0xffffff,
							size:voxel.config.voxelSize,
							opacity:1.0,
							transparent:true,
							order:cubes.nextOrder(),
							simplePosition:new THREE.Vector3()//以1为单位的位置坐标                  
						}
						
					},
					
					initialize:function(){
						
						if(this.get("cube")==null){
							
							this.set({
								cube:new THREE.Mesh(
									
									new THREE.CubeGeometry( this.get("size"), this.get("size"), this.get("size") ),
									
									new THREE.MeshLambertMaterial({
										
										color:this.get("color"),
										
										opacity:this.get("opacity"),
										
										transparent:this.get("transparent")
									})
								)
							});
							
							
							
						}else{
							
							/*
							 * 如果已经指定cube,则设置相应属性
							 */
							
							var cube=this.get('cube');
							
							//console.log(cube.material);
							
							this.set({
								
								color:cube.material.color.getHex()
								
								
							});
							
							//console.log(this.get('color'));
						}
						
						this.on('change:opacity change:transparent change:color',this.propertyChanged,this);
						
						
					},
					
					propertyChanged:function(){
						
						var position;
						/*
						 * 记录变动前的position
						 */
						
						position=new THREE.Vector3();
						
						position.copy(this.get("cube").position);
						
						this.set({
							
						   cube:new THREE.Mesh(
							   
							   new THREE.CubeGeometry( this.get("size"), this.get("size"), this.get("size") ),
							   
							   new THREE.MeshLambertMaterial({
								   
								   color:this.get("color"),
								   opacity:this.get("opacity"),
								   transparent:this.get("transparent")
								   
							   })
						   ) 
						});
						
						this.get("cube").position=position;
						
						
					}
				});
				/*
				 * 定义cube的存取模型
				 */
				cubeSaveModel=Backbone.Model.extend({
					defaults:function(){
						return{
							x:0,
							y:0,
							z:0,
							color:0x000000,
							id:0
						}
					},
					url:''
				});
				
				cubeSaveList=Backbone.Collection.extend({
					model:cubeSaveModel,
					url:''
				});
				/*
				 * 定义commands
				 */
				function Commands(cubeList,cubeView){
					
					var cubes=cubeList,
						cubesStates=[],
						cubeView=cubeView,
						index=0
					;
					
					cubesStates.push(cubes.clone());
					
					this.undo=function(){
					   if(index<=0){
							index=0;
							return;
					   }
					   var cs;
					   index--;   
					   if(index<=0)
					   //chenxuan
						$("#undo").css("background-position","-136px -102px");
					   cs=cubesStates[index];
					   cubeView.refresh(cs);
					   //chenxuan
					   $("#redo").css("background-position","-68px -102px");
					};
					
					this.redo=function(){
						
						if(index>=cubesStates.length-1){
							index=cubesStates.length-1;
							//chenxuan
							$("#redo").css("background-position","-33px -102px");
							return;
						}
						var cs;
						index++;
						cs=cubesStates[index];
						cubeView.refresh(cs);
					};
					
					this.update=function(){
						/*
						 * 刷新操作栈
						 */
						while(cubesStates.length!=index+1){
							cubesStates.pop();
						}
						cubesStates.push(cubes.clone());//备份当前cubes
						index++;
						//chenxuan
						$("#undo").css("background-position","0 -68px");
						//this.show(index);
					}
					
					this.show=function(index){
						
						console.log("index"+index+"目前队列的结构为:"+"\n");
						
						for(var i=0;i<=index;i++){
							
							console.log(i+":  "+cubesStates[i].length+"\n");
						}
					}
					
					
				}    
				/*
				 * cube collection
				 */
				
				cubeList=Backbone.Collection.extend({
					
					model:cubeModel,
					
					nextOrder:function(){
						
						if(!this.length){
							
							return 1;
						}
						
						return this.last().get('order')+1;
					},
					
					/*
					 * 找到位置为x,y,z的cube
					 */
					getSameCubes:function(position){
						
						return this.filter(function(cube){
							
							var cube,
								cx,
								cy,
								cz
							;
							
							cube=cube.get("cube");
							cx=cube.position.x;
							cy=cube.position.y;
							cz=cube.position.z;
							
							return((position.x==cx)&&(position.y==cy)&&(position.z==cz));
								
						});
					},
					
					getCorsorCube:function(cubeMesh){
						
						/*
						 * 获取鼠标划过的cube
						 */
						return this.filter(function(cube){
							
							return (cube.get("cube")==cubeMesh);
						});
					}
				   
					
				});
				/*
				 * 初始化方块群
				 */
				cubes=new cubeList;
				
				/*
				 * cubesView
				 */
				
				cubesView=Backbone.View.extend({
					
					el:null,//关联此view的DOM元素，但在这里使用的是scene
					
					cubes:null,
					
					initialize:function(){
						
						this.cubes=cubes;
						
						this.cubes.on('add',this.onAdd,this);
						
						this.cubes.on("remove",this.removeOne,this);
					},
					
					onAdd:function(cube,cubes,options){
						
						this.el.add(cube.get('cube'));
						
						/*
						 * 更新commands数据
						 */
						//console.log(arguments);
						if(options.where==options.length-1){
							/*
							 * 缓冲完成，可以更新
							 */
							commands.update();
							
						}
					},
					
					removeOne:function(cube,cubes,options){
						
						this.el.remove(cube.get('cube'));
						
						commands.update();
						
					},
					
					removeAll:function(){
						
						var el=this.el;
						
						this.cubes.each(function(cube){
							
							el.remove(cube.get("cube"));
						});
						
						
					},
					
					refresh:function(cs){
						
						var el=this.el;
						
						//console.log("使用了命令：准备刷新 刷新前方块个数为："+this.cubes.length);
						//alert(cubes.length);
						/*
						 * 清空当前cubes
						 */
						
						this.removeAll();
						
						/*
						 * 清空当前cubes
						 */
						cubes.reset();
						cs.each(function(cube){
							cubes.add([cube]);
						});
						
						//console.log("刷新后方块个数为："+this.cubes.length);

						
					}
				});
				
				//cbsView=new cubesView({el:voxelPainter.models.scene});

				/*
				 * 每一个view都有一个元素
				 */
				
				voxelView=Backbone.View.extend({
					/*
					 * 在交互过程中需要改变的局部量
					 * camera
					 * 鼠标位置
					 * cube
					 * 
					 */
					models:{
						
						camera:null,
						controls:null,
						scene:null,
						renderer:null,
					},
					
					/*
					 * 有关交互的变量
					 */
					
					interacts:{
						
						mouse2D:new THREE.Vector3( 0, 10000, 0.5 ),
						
						voxelPosition:new THREE.Vector3(),//放置目标像素块的位置
						
						ray:null,//检测射线
						
						intersector:null,//每次交互检测到的实物
						
						isCtrlDown:false,
						
						isShiftDown:false,
						
						//其实使用Model模型，实际的Mesh需要在进行存取
						rollOverMesh:new cubeModel({
							
							color:0x000000,
							
							opacity: 0.2,
							
							transparent: true
						}),
						
						plane:null,
						
						tmpVec:new THREE.Vector3(),
						
						projector:new THREE.Projector(),
						
						isMouseDown:false,
						
						preCubes:new Array(),
						
						startCubePosition:new THREE.Vector3()
						
						
					},
					/*
					 * 交互过程中的当前状态量
					 */

					states:{
						
						pickedColor:null,
						
						viewPattern:"2D",//或者3D
						
						drawMethod:null,
						
						cPosition:new THREE.Vector3(0,1000,0),
						
						//toolState:TOOLTYPE.SINGLECUBE
						toolState:null
						
					},
					
					global:{
						
						/*
						 * 一些全局变量：位置数组
						 */
						existStates:new Array(Math.pow(voxel.config.maxNum,3)),//记录全局位置坐标的三维数组
						
						preCubeStates:new Array(Math.pow(voxel.config.maxNum,3))//记录预选取位置坐标的三维数组
						
					},

					/*
					 * canvas
					 */
					el:$("#"+voxel.config.canvasID),
					
					initialize:function(){
						
						var
							/*
							 * 在渲染过程中经常变化
							 */
							camera,cameraM,
							plane,
							scnen,scnenM,
							renderer,
							controls,
							mouse2D,//记录鼠标的位置
							rollOverCube,//预选的cube
							/*
							 * 创建后就就不会再变化的变量
							 */
							config=voxel.config,
							container,
							plane,
							cameraTarget,
							ambientLight,
							directionalLight

						;
						/*
						 * 获取已实例化的model数据
						 */
						mouse2D=this.interacts.mouse2D;
						/*
						 * 初始化场景
						 */
						scene = new THREE.Scene();   
										
						this.models.scene=scene;
						/*
						 * camera的初始化和模型绑定
						 */
						//camera=new THREE.PerspectiveCamera(45,voxel.config.canvasWidth/voxel.config.canvasHeight,1,10000);
						
						//camera=new THREE.OrthographicCamera(-voxel.config.canvasWidth,voxel.config.canvasWidth,voxel.config.canvasHeight,-voxel.config.canvasHeight,1,2000);
						/*
						 * 使用联合相机
						 */
						camera = new THREE.CombinedCamera(voxel.config.canvasWidth, voxel.config.canvasHeight, 75, 1, 5000, 1, 20000);
						
						this.models.camera=camera;
						
						//默认使用正投影
						camera.toOrthographic();
						
						/*
						 * 初始化容器
						 */
						
						container=document.getElementById(config.containerID);
						
						/*
						 * 标定camera
						 */
						cameraTarget = new THREE.Vector3( 0, 0, 0 );
	  
						//camera.position=this.states.cPosition;
						
						camera.lookAt(cameraTarget);
						
						scene.add(camera);
						/*
						 * 添加轨迹球效果
						 */
						
						controls = new THREE.TrackballControls(camera,this.el);
						
						controls.staticMoving=true;
						
						this.models.controls=controls;
						
						
						
						/*
						 * 设定光照
						 */
						
						ambientLight=new THREE.AmbientLight( 0x000000 );
						
						scene.add(ambientLight);
						
						
						directionalLight = new THREE.DirectionalLight( 0xffffff );
						
						directionalLight.position.set( 1,0.5,0.5).normalize();
						
						scene.add( directionalLight );
						
						
			
						var directionalLight2=new THREE.DirectionalLight( 0xffffff );
						directionalLight2.position.x = - 1;
						directionalLight2.position.y = 1;
						directionalLight2.position.z = - 0.75;
						directionalLight2.position.normalize();
						scene.add( directionalLight2 );
						
						
						/*
						 * 像素底部平面
						 */
						plane = new THREE.Mesh( new THREE.PlaneGeometry(config.planeSize, config.planeSize, config.planeSize/config.voxelSize, config.planeSize/config.voxelSize), new THREE.MeshBasicMaterial( { color: 0x999999, wireframe: true } ) );
						
						this.interacts.plane=plane;
									 
						scene.add(plane);

						/*
						 * 添加cubeHelper
						 */
						
						scene.add(this.interacts.rollOverMesh.get("cube"));
						/*
						 * 建立坐标轴
						 */
						this.setUpAxis();
						
						/*
						 * 初始化渲染器
						 */
						renderer=new THREE.WebGLRenderer( { antialias: true, preserveDrawingBuffer : true,canvas:this.el} );
						
						//renderer=new THREE.CanvasRenderer({canvas:this.el});
						
						renderer.setSize( config.canvasWidth,config.canvasHeight );
						
						this.models.renderer=renderer;

						/*
						 * 添加到DOM中,已经绑定canvas就不用添加到DOM中了
						 */
										 
						//container.appendChild( renderer.domElement );
						
						/*
						 * 获取el的绝对位置
						 * 将canvas设置为可获取焦点
						 */
						
						this.el.absPosition=jobs.getAbsPosition(this.el);
						
						
					},
					
					events:{
						"mousemove":"onMouseMove",
						"drag":"onDrag",
						"mousedown":"onMouseDown",
						"mouseup":"onMouseUp",
						"keydown":"onKeyDown",
						"keyup":"onKeyUp",
						"mouseover":"onMouseOver",
						"mouseout":"onMouseOut"
					},
					/*
					 * 处理canvas获取焦点的交互，便于使用键盘
					 */
					handlefocus:function(event){
						
						//alert(event.type);
						if(event.type=='mouseover'){
							
							//this.el.focus();
							
							return false;
						}else if(event.type=='mouseout'){
							
							//this.el.blur();
							return false;
						}
						
						return true;
					},
					onKeyDown:function(event){

						//alert(event.keyCode);
						
						switch(event.keyCode){
							
							case 17: this.interacts.isCtrlDown = true; break;
							case 16: this.interacts.isShiftDown = true; break;
						}
					},
					onKeyUp:function(event){
						
						switch(event.keyCode){
							
							case 17: this.interacts.isCtrlDown = false; break;
							case 16: this.interacts.isShiftDown = false; break;
						}
					},
					onMouseMove:function(event){
						
						//console.log(event.clientX+"  "+event.clientY);
						/*
						 * 获取鼠标相对于canvas的相对位置
						 */
						
						
						event.preventDefault();
						
						/*
						 * 使canvas获取焦点
						 */
						/*
						 * 相对于canvas的位置
						 */
						
						var tempX,
						
							tempY
						;
						
						tempX=event.pageX-this.el.absPosition.x;
						
						tempY=event.pageY-this.el.absPosition.y;
						
						this.interacts.mouse2D.x = ( tempX / this.el.clientWidth ) * 2 - 1;
						
						this.interacts.mouse2D.y = - ( tempY / this.el.clientHeight ) * 2 + 1;
						
						$("#x").html("x: "+this.interacts.mouse2D.x +" "+tempX+" ");
						
						$("#y").html("y: "+this.interacts.mouse2D.y+" "+tempY+" ");
						
						$("#config").html("canvas: "+ this.el.clientWidth+ " "+this.el.clientHeight);
						
						$("#zuobiao").html("坐标： "+"x:"+jobs.getSimplePosition(this.interacts.voxelPosition.x)+" y:"+jobs.getSimplePosition(this.interacts.voxelPosition.y)+" z: "+jobs.getSimplePosition(this.interacts.voxelPosition.z));
						
						//console.log(this.interacts.voxelPosition);
						/*
						 * 在不同的tool下Mousemove的方式应该不同
						 */
						/*
						 * 检测相交物体的个数作为log
						 */
						this.intersectsLog();
						
						var STATE=TOOLTYPE;
															  
						switch (this.states.toolState){
							
							case STATE.LINE:this.drawLines();break;
							case STATE.CLEAN:this.clean();break;
							case STATE.SINGLECUBE:this.drawSingleCube();break;
						}
					},
					drawSingleCube:function(){
						/*
						 * 在singlecube的模式下缓冲区只有一个cube
						 */
						if(this.interacts.isMouseDown){
							if(this.interacts.preCubes.length==1){
								var sCube=this.interacts.preCubes[0];
								sCube.get("cube").position.copy(this.interacts.voxelPosition);
								sCube.get("cube").updateMatrix();
							}
						}
					},
					clean:function(){
						//console.log("clean!");
						
						if(this.interacts.isMouseDown){
							/*
							 * 目前做法，鼠标划过就消除
							 */
							var interacts;
							intersects=this.interacts.ray.intersectObjects(this.models.scene.children);
							if(intersects.length>0){
								this.getRealIntersector( intersects );
								if(this.interacts.intersector.object!=this.interacts.plane){
									cubes.remove(cubes.getCorsorCube(this.interacts.intersector.object));
								}
							}
						}
					},
					
					drawLines:function(){
						/*
						 * 在mousemove中drawline
						 */
						//console.log('drawing line');
						var preCubes=this.interacts.preCubes,
							v
						;
						
						if(this.interacts.isMouseDown){
							
							//console.log('drawing!!');
							/*
							 * 清楚缓冲区
							 */
							while(preCubes.length>0){
								
								v=preCubes.pop();
								
								this.models.scene.remove(v.get("cube"));
							}
							
							var intersects;//相交物体群
							
							intersects=this.interacts.ray.intersectObjects(this.models.scene.children);
							
							if(intersects.length>0){
								
								
								var voxel,
								
				 
									/*
									 * Bresenham直线算法
									 */
									x0,//起点
									z0,
									x1,
									z1,
									steep,
									temp,
									dx,
									dz,
									error,
									deltaerr ,
									zstep,
									z,x=0
								;
								
								this.getRealIntersector( intersects );//去最上面的cube
								
								this.setVoxelPosition( this.interacts.intersector );
								
								x0=jobs.getSimplePosition(this.interacts.startCubePosition.x);
								z0=jobs.getSimplePosition(this.interacts.startCubePosition.z);
								x1=jobs.getSimplePosition(this.interacts.voxelPosition.x);
								z1=jobs.getSimplePosition(this.interacts.voxelPosition.z);
								//console.log(x0+" "+z0);
								//console.log(x0+" "+z0);
								steep=Math.abs(z1-z0)>Math.abs(x1-x0);
								//console.log(steep);
								if(steep){
									/*
									 * swap x0,z0  x1,z1
									 */
									x0 = -(z0 = (x0 += z0) - z0) + x0;
									x1 = -(z1 = (x1 += z1) - z1) + x1;
										 
								}
								if(x0>x1){
									/*
									 * swap x0,x1
									 * z0,z1
									 */
									x0 = -(x1 = (x0 += x1) - x1) + x0;
									
									z0 = -(z1 = (z0 += z1) - z1) + z0;
								}
								dx=x1-x0;
								dz=Math.abs(z1-z0);
								error=dx/2;
								deltaerr=dz/dx;
								z=z0;
								
								if(z0<z1){
									zstep=1;
								}
								else{
									zstep=-1;
								}
								
								for(x=x0;x<=x1;x++){
									
									if(steep){
										
										this.createCube({
											x:z,
											z:x,
											y:1
										});
										
									   
									}else{
										
										this.createCube({
											
											x:x,
											z:z,
											y:1
										});
										
									}
									
									error=error-dz;
									if(error<0){
										
										z+=zstep;
										error=error+dx;
									}
								}
								
							}
							
							
							
						}
					},
					createCube:function(p){
						/*
						 * 根据简单坐标来添加cube,只是push到缓存队列里
						 */
						var realx,
							realy,
							realz,
							v,//cubeModel对象
							cube
						;
						realx=jobs.getRealPosition(p.x);
						realy=jobs.getRealPosition(p.y);
						realz=jobs.getRealPosition(p.z);
						/*
						 * 如果队列中已有，则不添加
						 */
						for(var i in this.interacts.preCubes){
							var position=this.interacts.preCubes[i];
							if(position.x==realx&&position.y==realy&&position.z==realz){
								alert('same');
								return ;
							}
						}
						/*
						 * 如果cube已经在缓存队列里，则不添加
						 * 确保添加cube的唯一性
						 */
						v=new cubeModel({
							
							color:this.states.pickedColor,
							
							opacity:0.5,
							
							transparent:true    
						});
						
						this.interacts.preCubes.push(v);
						
						cube=v.get("cube");
						cube.position.x=realx;
						cube.position.y=realy;
						cube.position.z=realz;
						
						this.settleDownCube(v);
						
						this.models.scene.add(v.get("cube"));
						
					},
					onMouseDown:function(event){                
						/*
						 * 普通的mousedown只是预添加一个voxel
						 */
						
						event.preventDefault();
						
						this.interacts.isMouseDown=true;
						
						var intersects;//相交物体群
						
						intersects=this.interacts.ray.intersectObjects(this.models.scene.children);
						
						if(intersects.length>0){
							this.getRealIntersector( intersects );
							
							//delete cube
							
							if(this.states.toolState==TOOLTYPE.CLEAN){
								
								if(this.interacts.intersector.object!=this.interacts.plane){
									/*
									 * 删除cube,应该使用cubes.remove
									 */
									cubes.remove(cubes.getCorsorCube(this.interacts.intersector.object));
								}
								return;
							}
							/*
							 * 创建cube
							 */                    	
							if(this.states.toolState==TOOLTYPE.SINGLECUBE){
								
								this.getRealIntersector( intersects );
								   
								this.setVoxelPosition( this.interacts.intersector );
								
								var voxel=new cubeModel(
									
									{
										color:this.states.pickedColor,
										
										opacity:0.5,
										
										transparent:true
									}
								);
							   
							   voxel.get("cube").position.copy(this.interacts.voxelPosition);
							   
							   this.settleDownCube(voxel);
							   
							   this.models.scene.add(voxel.get('cube'));
								
							   this.interacts.preCubes.push(voxel);//添加到缓冲区       
							   
							   return;             		
							}
							
							
						}
						/*
						 * 记录点击位置
						 */
						this.interacts.startCubePosition.copy(this.interacts.voxelPosition);
						
						$("#clickPoint").html("您点击的方块位置为："+"x: "+this.interacts.startCubePosition.x+" y: "+this.interacts.startCubePosition.y+" z: "+this.interacts.startCubePosition.z);
						
						
					},
					
					onMouseUp:function(event){
						this.interacts.isMouseDown=false;
						var v,//单voxel,可能与全局的voxel重复
							position,
							cubeQueue=[],
							index=0,
							length=this.interacts.preCubes.length
						;
						while(this.interacts.preCubes.length>0){
							
							
							
							v=this.interacts.preCubes.pop();
							/*
							 * 将位置进行记录
							 */
							position=v.get("cube").position;
							
							//console.log(this.global.existStates[(jobs.getSimplePosition(position.x))*voxel.config.maxNum+(jobs.getSimplePosition(position.y))*voxel.config.maxNum+jobs.getSimplePosition(position.z)]);
							
							if(this.global.existStates[(jobs.getSimplePosition(position.x))*voxel.config.maxNum+(jobs.getSimplePosition(position.y))*voxel.config.maxNum+jobs.getSimplePosition(position.z)]==1){
								
								/*
								 * 若该位置已存在cube,则删除
								 */
								var exitsCubes=cubes.getSameCubes(position);
								
								//console.log(exitsCubes.length);
								
								cubes.remove(exitsCubes);
							}
							/*
							 * 将位置记录
							 */
							this.global.existStates[(jobs.getSimplePosition(position.x))*voxel.config.maxNum+(jobs.getSimplePosition(position.y))*voxel.config.maxNum+jobs.getSimplePosition(position.z)]=1;
							
							//console.log(this.global.existStates[(jobs.getSimplePosition(position.x))*voxel.config.maxNum+(jobs.getSimplePosition(position.y)+1)*voxel.config.maxNum+jobs.getSimplePosition(position.z)]);
							
							//console.log(this.global.existStates[position.x/25*voxel.config.maxNum+position.y/25*voxel.config.maxNum+position.z/25]);
							
							this.models.scene.remove(v.get('cube'));
							/*
							 * 修改cubeorder的bug,创建cube是order序列化
							 */
							v.set({
								opacity:1.0,
								transparent:true
							});
							cubes.add(v,{
								
								length:length,
								
								where:index++
							});
						}
					},
					
					onMouseOver:function(event){
						
						this.handlefocus(event);
					},
					
					onMouseOut:function(event){
						
						this.handlefocus(event);
					},
					
					getRealIntersector:function(intersects){
						
						for(var i=0;i<intersects.length;i++){
							
							this.interacts.intersector=intersects[i];
							
							if(this.interacts.intersector.object!=this.interacts.rollOverMesh.get("cube")){
								
								return;
							}
						}
						
						this.interacts.intersector=null;
					},
					
					setVoxelPosition:function(intersector){
						
						this.interacts.tmpVec.copy( intersector.face.normal );
						
						this.interacts.voxelPosition.add( intersector.point, intersector.object.matrixRotationWorld.multiplyVector3( this.interacts.tmpVec ) );
						
						this.interacts.voxelPosition.x = Math.floor( this.interacts.voxelPosition.x / 50 ) * 50 + 25;
						
						this.interacts.voxelPosition.y = Math.floor( this.interacts.voxelPosition.y / 50 ) * 50 + 25;
					
						this.interacts.voxelPosition.z = Math.floor( this.interacts.voxelPosition.z / 50 ) * 50 + 25;

						
					},
					
					voxelRender:function(){
						
						var intersects,
							ray=this.interacts.ray,
							projector=this.interacts.projector,
							mouse2D=this.interacts.mouse2D,
							scene=this.models.scene,
							camera=this.models.camera,
							renderer=this.models.renderer
						;
						
						
						/*
						 * 3D使用射线检测，2D不使用射线检测
						 */
						
						ray=projector.pickingRay(mouse2D.clone(),camera);
						
						intersects =ray.intersectObjects( scene.children );
						
						this.interacts.ray=ray;
						
						if(intersects.length>0){
							
							this.getRealIntersector( intersects );
							
							if(this.interacts.intersector){
								
								this.setVoxelPosition( this.interacts.intersector );
								
								this.interacts.rollOverMesh.get("cube").position=this.interacts.voxelPosition;
							}
						}
						
					   if(this.states.viewPattern=="2D"){
						   
						   
					   }
					   /*
						* 
						*/
					   //camera.updateProjectionMatrix();
						
						renderer.render(scene,camera);
						this.models.controls.update();
						

					},
					
					onDrag:function(){
						
						event.preventDefault();
						
						alert("dragged");
					},
					
					settleDownCube:function(voxel){
						
						//voxel.get('cube').position.copy( this.interacts.voxelPosition );
						voxel.get('cube').matrixAutoUpdate = false;
						voxel.get('cube').updateMatrix();
						
					},
					/*
					 * 存取模型
					 */
					saveJSON:function(){
						var voxels=new cubeSaveList,
							children=this.models.scene.children,
							order=0
						;
						/*
						 * id相同不会被add
						 */
						cubes.each(function(cube){
							var v=new cubeSaveModel({
								x:cube.get('cube').position.x,
								y:cube.get('cube').position.y,
								z:cube.get('cube').position.z,
								color:cube.get('cube').material.color.getHex(),
								id:order++
							})
							voxels.add(v);
							//v.save();
						});	
						// open a window with the json
						var dataUri	= "data:application/json;charset=utf-8,"+JSON.stringify(voxels.toJSON());
						window.open( dataUri, 'mywindow' );
					},

					CubeJSON:function(){

						var voxels=new cubeSaveList,
							children=this.models.scene.children,
							order=0
						;
						/*
						 * id相同不会被add
						 */
						cubes.each(function(cube){
							var v=new cubeSaveModel({
								x:cube.get('cube').position.x,
								y:cube.get('cube').position.y,
								z:cube.get('cube').position.z,
								color:cube.get('cube').material.color.getHex(),
								id:order++
							})
							voxels.add(v);
							//v.save();
						});	
						// open a window with the json
						var data= JSON.stringify(voxels.toJSON());
						return data;
					},
					/*
					 * 对获得的JSON对象进行渲染
					 */
					loadJSON:function(mapJson){
						/*
						 * 删除屏幕上的cube
						 */
						while(cubes.length>0){
							cubes.pop();
						}
						var voxels=JSON.parse(mapJson),
							voxel
						;
						for(var i=0;i<voxels.length;i++){
							
							voxel=voxels[i];
							//console.log(voxel);
							var v=new cubeModel({
								color:voxel.color
							});
							v.get("cube").position.x=voxel.x;
							v.get("cube").position.y=voxel.y;
							v.get("cube").position.z=voxel.z;
							this.settleDownCube(v);
							cubes.add(v);
						}
						
						
					},
					
					/*
					 * 调试用的工具函数
					 */
					
					intersectsLog:function(){
						
						var intersects;//相交物体群
						
						intersects=this.interacts.ray.intersectObjects(this.models.scene.children);
						
						$('#intersects').html('相交物体个数： '+intersects.length);
					},
					
					setUpAxis:function(){
						
						var scene=this.models.scene;
						/*
						// 建立x坐标轴,color: Green
						xAxis = new THREE.Geometry();
						var originPoint = new THREE.Vector3();
						var xAxisEndPoint = new THREE.Vector3(600, 0, 0);
						xAxis.vertices.push(originPoint);
						xAxis.vertices.push(xAxisEndPoint);
						var lineX = new THREE.Line(xAxis, new THREE.LineBasicMaterial({
							color : 0x00ff00,
							opacity : 0,
							linewidth : 5
						}));
						scene.add(lineX);
					
						// 建立y坐标轴,color:Blue
						yAxis = new THREE.Geometry();
						var yAxisEndPoint = new THREE.Vector3(0, 600, 0);
						yAxis.vertices.push(originPoint);
						yAxis.vertices.push(yAxisEndPoint);
						var lineY = new THREE.Line(yAxis, new THREE.LineBasicMaterial({
							color : 0x0000ff,
							opacity : 0,
							linewidth : 5
						}));
						scene.add(lineY);
					
						// 建立z坐标轴,color:Red
						zAxis = new THREE.Geometry();
						var zAxisEndPoint = new THREE.Vector3(0, 0, 600);
						zAxis.vertices.push(originPoint);
						zAxis.vertices.push(zAxisEndPoint);
						var lineZ = new THREE.Line(zAxis, new THREE.LineBasicMaterial({
							color : 0xff0000,
							opacity : 0,
							linewidth : 5
						}));
						scene.add(lineZ); 
						*/                  
					}
				});
				/*
				 * DOM操作工具函数
				 * 一些逻辑上的算法
				 */
				var jobs={
					
					/*
					 * 获取元素的绝对位置
					 */
					getAbsPosition:function(obj){
						
						var x=obj.offsetLeft,
							y=obj.offsetTop
						;
						
						while(obj=obj.offsetParent){
							
							x+=obj.offsetLeft;
							
							y+=obj.offsetTop;
						}
						
						return {
							
							x:x,
							
							y:y
						};
					},
					
					/*
					 * 将十六进制字符串转化为数字
					 */
					getSimplePosition:function(num){
						
						/*
						 * 根据物体的实际坐标返回简单的逻辑坐标（25，25）-》（1，1）。。（75，75）-》（2，2）
						 * 用于逻辑图形学的运算
						 */
						if(num>=0){
							
							return ((num-25)/50)+1;
						}else{
							
							return -(((0-num-25)/50)+1);
						}
						
					},
					
					getRealPosition:function(num){
						/*
						 * 根据序列获取方块的绝对坐标
						 */
						if(num>=0){
							
							return 25+50*(num-1);
						}else{
							return -25-50*(-num-1);
						}
					},
					stringToHexConverter:function(hex){
						
						var hexNum;
						
						hex="0x"+hex;
						
						hexNum=parseInt(hex);
						
						return hexNum;
					},
					rgbToHex:function(r,g,b){
						
					}
				};
				/*
				 * 程序开始
				 */
				var voxelPainter=new voxelView();
				
				function animate()
				{
					requestAnimationFrame(animate);
					
					voxelPainter.voxelRender();
				}
				
				animate();
				
				/*
				 * 建立view
				 */
				
				cbsView=new cubesView({
					el:voxelPainter.models.scene
					});
				/*
				 * 建立commands
				 */
				commands=new Commands(cubes,cbsView);
				controller=new cameraController(voxelPainter.models.camera,voxelPainter.models.scene,voxelPainter.models.controls);
				/*
				 * 进行交互UI的渲染
				 */
				/*
				 * colorPicker
				 */
				 
//chenxuan					
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
				
				var colorconfirmed="FF5656";
				
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
							
							voxelPainter.states.pickedColor=jobs.stringToHexConverter(colorconfirmed);
							
							$("#colorpad").fadeOut(200);
							
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
				
					$("#colorpad").fadeIn(400);
					
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
						
						voxelPainter.states.pickedColor=jobs.stringToHexConverter(colorconfirmed);//绘制的颜色
						
					}
					
				})
//chenxuan function end 
				$("#colorPicker").ColorPicker({
					
					color:"#75c2d9",
					
					onShow:function(colpkr){
						
						$(colpkr).fadeIn(500);
						
						return false;
					},
					
					onHide:function(colpkr){
						
						$(colpkr).fadeOut(500);
						
						return false;
					},
					
					onChange:function(hsb, hex, rgb){
						
						$("#colorPicker").css('background-color', '#' + hex);
						
						voxelPainter.states.pickedColor=jobs.stringToHexConverter(hex);
					},
					
					onSubmit:function(hsb, hex, rgb, el){
						
						
						voxelPainter.states.pickedColor=jobs.stringToHexConverter(hex);
						
						$(el).ColorPickerHide()
						
						//console.log(typeof(voxelPainter.states.pickedColor)+" "+voxelPainter.states.pickedColor);
					}
				});
				
				$("#drawLine").click(function(){
					
					voxelPainter.states.toolState=TOOLTYPE.LINE;
				});
				
				$("#clean").click(function(){
					voxelPainter.states.toolState=TOOLTYPE.CLEAN;
				});
				
				$('#defaultsTool').click(function(){
					
					voxelPainter.states.drawMethod=null;
					voxelPainter.states.toolState=null;
				});
				$("#singleCube").click(function(){
					voxelPainter.states.toolState=TOOLTYPE.SINGLECUBE;
				});
				/*
				 * 调使用
				 */
				$("#undo").click(function(){
					commands.undo();
				});
				$("#redo").click(function(){
					commands.redo();
				});
				/*
				 * 视角转换器
				 */
				$("#2D").click(function(){
					controller.to2D();
				});
				$("#3D").click(function(){
					controller.to3D();
				});
				$("#Up").click(function(){
					controller.Up();
					console.log(voxelPainter.models.camera);
				});
				$("#Down").click(function(){
					controller.Down();
					console.log(voxelPainter.models.camera);
				});
				$("#Left").click(function(){
					controller.Left();
					console.log(voxelPainter.models.camera);
				});
				$("#Right").click(function(){
					controller.Right();
					console.log(voxelPainter.models.camera);
				});
				//var a =
				/*
				 * 存取模型
				 */
				$("#save").click(function(){
					voxelPainter.saveJSON();
				});
				$("#load").click(function(){
					voxelPainter.loadJSON(
					'[{"x":-75,"y":25,"z":225,"color":0,"id":0},{"x":-75,"y":25,"z":175,"color":0,"id":1},{"x":-75,"y":25,"z":125,"color":0,"id":2},{"x":-75,"y":25,"z":75,"color":0,"id":3},{"x":-75,"y":25,"z":25,"color":0,"id":4},{"x":-75,"y":25,"z":275,"color":0,"id":5},{"x":-75,"y":25,"z":-75,"color":0,"id":6},{"x":-75,"y":25,"z":-125,"color":0,"id":7},{"x":-75,"y":75,"z":-125,"color":0,"id":8},{"x":-75,"y":75,"z":275,"color":0,"id":9},{"x":-125,"y":25,"z":275,"color":0,"id":10},{"x":-175,"y":25,"z":275,"color":0,"id":11},{"x":-225,"y":25,"z":275,"color":0,"id":12},{"x":-225,"y":75,"z":275,"color":0,"id":13},{"x":-175,"y":75,"z":275,"color":0,"id":14},{"x":-125,"y":75,"z":275,"color":0,"id":15},{"x":-125,"y":125,"z":225,"color":0,"id":16},{"x":-125,"y":175,"z":225,"color":0,"id":17},{"x":-75,"y":175,"z":225,"color":0,"id":18},{"x":-75,"y":225,"z":225,"color":0,"id":19},{"x":-75,"y":225,"z":275,"color":0,"id":20},{"x":-175,"y":175,"z":225,"color":0,"id":21},{"x":-175,"y":125,"z":225,"color":0,"id":22},{"x":-175,"y":225,"z":225,"color":0,"id":23},{"x":-225,"y":225,"z":225,"color":0,"id":24},{"x":-275,"y":225,"z":225,"color":0,"id":25},{"x":-225,"y":175,"z":225,"color":0,"id":26},{"x":-225,"y":125,"z":225,"color":0,"id":27},{"x":-275,"y":175,"z":225,"color":0,"id":28},{"x":-75,"y":75,"z":-75,"color":1976278,"id":29},{"x":-75,"y":75,"z":-25,"color":1976278,"id":30},{"x":-75,"y":75,"z":25,"color":1976278,"id":31},{"x":-75,"y":75,"z":75,"color":1976278,"id":32},{"x":-75,"y":75,"z":125,"color":1976278,"id":33},{"x":-75,"y":75,"z":175,"color":1976278,"id":34},{"x":-75,"y":75,"z":225,"color":1976278,"id":35},{"x":-75,"y":125,"z":125,"color":1976278,"id":36},{"x":-75,"y":125,"z":175,"color":1976278,"id":37},{"x":-75,"y":125,"z":75,"color":1976278,"id":38},{"x":-75,"y":175,"z":175,"color":1976278,"id":39},{"x":-75,"y":175,"z":125,"color":1976278,"id":40},{"x":-75,"y":175,"z":75,"color":1976278,"id":41},{"x":-75,"y":175,"z":25,"color":1976278,"id":42},{"x":-75,"y":225,"z":25,"color":1976278,"id":43},{"x":-75,"y":375,"z":175,"color":1976278,"id":44},{"x":-75,"y":375,"z":225,"color":1976278,"id":45},{"x":-75,"y":375,"z":275,"color":1976278,"id":46},{"x":-75,"y":375,"z":325,"color":1976278,"id":47},{"x":-75,"y":425,"z":275,"color":1976278,"id":48},{"x":-75,"y":425,"z":325,"color":1976278,"id":49},{"x":-75,"y":325,"z":275,"color":1976278,"id":50},{"x":-75,"y":125,"z":225,"color":0,"id":51},{"x":-75,"y":225,"z":125,"color":4974578,"id":52},{"x":-75,"y":225,"z":75,"color":4974578,"id":53},{"x":-75,"y":225,"z":175,"color":4974578,"id":54},{"x":-75,"y":275,"z":175,"color":4974578,"id":55},{"x":-75,"y":275,"z":125,"color":4974578,"id":56},{"x":-75,"y":275,"z":75,"color":4974578,"id":57},{"x":-75,"y":325,"z":175,"color":4974578,"id":58},{"x":-75,"y":325,"z":125,"color":4974578,"id":59},{"x":-75,"y":275,"z":225,"color":4974578,"id":60},{"x":-75,"y":325,"z":225,"color":4974578,"id":61},{"x":-75,"y":125,"z":-75,"color":0,"id":62},{"x":-75,"y":125,"z":-25,"color":0,"id":63},{"x":-75,"y":125,"z":25,"color":0,"id":64},{"x":-75,"y":175,"z":-25,"color":0,"id":65},{"x":-75,"y":225,"z":-25,"color":0,"id":66},{"x":-75,"y":275,"z":25,"color":0,"id":67},{"x":-75,"y":325,"z":75,"color":0,"id":68},{"x":-75,"y":375,"z":425,"color":0,"id":69},{"x":-75,"y":425,"z":425,"color":0,"id":70},{"x":-75,"y":475,"z":425,"color":0,"id":71},{"x":-75,"y":525,"z":425,"color":0,"id":72},{"x":-75,"y":575,"z":425,"color":0,"id":73},{"x":-75,"y":375,"z":375,"color":1976278,"id":74},{"x":-75,"y":425,"z":375,"color":1976278,"id":75},{"x":-75,"y":275,"z":275,"color":1976278,"id":76},{"x":-75,"y":325,"z":325,"color":1976278,"id":77},{"x":-75,"y":475,"z":375,"color":1976278,"id":78},{"x":-75,"y":275,"z":325,"color":197379,"id":79},{"x":-75,"y":325,"z":375,"color":197379,"id":80},{"x":-75,"y":275,"z":375,"color":197379,"id":81},{"x":-75,"y":325,"z":425,"color":197379,"id":82},{"x":-75,"y":425,"z":225,"color":4974578,"id":83},{"x":-75,"y":425,"z":175,"color":4974578,"id":84},{"x":-75,"y":425,"z":125,"color":4974578,"id":85},{"x":-75,"y":425,"z":75,"color":4974578,"id":86},{"x":-75,"y":475,"z":325,"color":4974578,"id":87},{"x":-75,"y":475,"z":275,"color":4974578,"id":88},{"x":-75,"y":475,"z":225,"color":4974578,"id":89},{"x":-75,"y":525,"z":275,"color":4974578,"id":90},{"x":-75,"y":525,"z":375,"color":4974578,"id":91},{"x":-75,"y":525,"z":325,"color":4974578,"id":92},{"x":-75,"y":575,"z":325,"color":4974578,"id":93},{"x":-75,"y":575,"z":375,"color":4974578,"id":94},{"x":-75,"y":625,"z":375,"color":4974578,"id":95},{"x":-75,"y":625,"z":425,"color":4974578,"id":96},{"x":-75,"y":675,"z":425,"color":4974578,"id":97},{"x":-75,"y":675,"z":375,"color":4974578,"id":98},{"x":-75,"y":725,"z":425,"color":4974578,"id":99},{"x":-75,"y":775,"z":425,"color":4974578,"id":100},{"x":-75,"y":775,"z":475,"color":4974578,"id":101},{"x":-75,"y":725,"z":475,"color":4974578,"id":102},{"x":-75,"y":625,"z":475,"color":328965,"id":103},{"x":-75,"y":675,"z":475,"color":328965,"id":104},{"x":-75,"y":725,"z":525,"color":328965,"id":105},{"x":-75,"y":775,"z":525,"color":328965,"id":106},{"x":-75,"y":475,"z":175,"color":328965,"id":107},{"x":-75,"y":475,"z":125,"color":328965,"id":108},{"x":-75,"y":475,"z":75,"color":328965,"id":109},{"x":-75,"y":525,"z":225,"color":328965,"id":110},{"x":-75,"y":575,"z":275,"color":328965,"id":111},{"x":-75,"y":675,"z":325,"color":328965,"id":112},{"x":-75,"y":725,"z":375,"color":328965,"id":113},{"x":-75,"y":775,"z":375,"color":328965,"id":114},{"x":-75,"y":825,"z":425,"color":328965,"id":115},{"x":-75,"y":825,"z":475,"color":328965,"id":116},{"x":-75,"y":825,"z":375,"color":328965,"id":117},{"x":-25,"y":825,"z":375,"color":328965,"id":118},{"x":-25,"y":475,"z":75,"color":328965,"id":119},{"x":-25,"y":475,"z":125,"color":328965,"id":120},{"x":-25,"y":475,"z":175,"color":328965,"id":121},{"x":-25,"y":525,"z":225,"color":328965,"id":122},{"x":-25,"y":575,"z":275,"color":328965,"id":123},{"x":-25,"y":625,"z":325,"color":328965,"id":124},{"x":-25,"y":675,"z":325,"color":328965,"id":125},{"x":-25,"y":725,"z":375,"color":328965,"id":126},{"x":-25,"y":775,"z":375,"color":328965,"id":127},{"x":-75,"y":25,"z":-25,"color":328965,"id":128},{"x":-25,"y":525,"z":175,"color":16115593,"id":129},{"x":-25,"y":525,"z":125,"color":16115593,"id":130},{"x":-25,"y":525,"z":75,"color":16115593,"id":131},{"x":-25,"y":575,"z":175,"color":16115593,"id":132},{"x":-25,"y":625,"z":175,"color":16115593,"id":133},{"x":-25,"y":525,"z":25,"color":16115593,"id":134},{"x":-25,"y":525,"z":-25,"color":16115593,"id":135},{"x":-25,"y":625,"z":225,"color":16115593,"id":136},{"x":-25,"y":675,"z":225,"color":16115593,"id":137},{"x":-25,"y":725,"z":225,"color":16115593,"id":138},{"x":-25,"y":575,"z":125,"color":328965,"id":139},{"x":-25,"y":575,"z":75,"color":328965,"id":140},{"x":-25,"y":575,"z":25,"color":328965,"id":141},{"x":-25,"y":575,"z":-25,"color":328965,"id":142},{"x":-25,"y":675,"z":75,"color":328965,"id":143},{"x":-25,"y":725,"z":75,"color":328965,"id":144},{"x":-25,"y":725,"z":25,"color":328965,"id":145},{"x":-25,"y":675,"z":25,"color":328965,"id":146},{"x":-25,"y":725,"z":-75,"color":328965,"id":147},{"x":-25,"y":575,"z":225,"color":1976278,"id":148},{"x":-25,"y":625,"z":275,"color":1976278,"id":149},{"x":-25,"y":675,"z":275,"color":1976278,"id":150},{"x":-25,"y":725,"z":275,"color":1976278,"id":151},{"x":-25,"y":775,"z":275,"color":1976278,"id":152},{"x":-25,"y":775,"z":225,"color":1976278,"id":153},{"x":-25,"y":825,"z":275,"color":1976278,"id":154},{"x":-25,"y":825,"z":225,"color":1976278,"id":155},{"x":-25,"y":875,"z":275,"color":1976278,"id":156},{"x":-25,"y":925,"z":275,"color":1976278,"id":157},{"x":-25,"y":825,"z":175,"color":1976278,"id":158},{"x":-25,"y":825,"z":125,"color":1976278,"id":159},{"x":-25,"y":825,"z":75,"color":1976278,"id":160},{"x":-25,"y":825,"z":25,"color":1976278,"id":161},{"x":-25,"y":875,"z":75,"color":1976278,"id":162},{"x":-25,"y":925,"z":75,"color":1976278,"id":163},{"x":-25,"y":875,"z":225,"color":1976278,"id":164},{"x":-25,"y":875,"z":175,"color":1976278,"id":165},{"x":-25,"y":875,"z":125,"color":1976278,"id":166},{"x":-25,"y":925,"z":225,"color":1976278,"id":167},{"x":-25,"y":925,"z":175,"color":1976278,"id":168},{"x":-25,"y":925,"z":125,"color":1976278,"id":169},{"x":-25,"y":975,"z":125,"color":1976278,"id":170},{"x":-25,"y":975,"z":175,"color":1976278,"id":171},{"x":-25,"y":975,"z":225,"color":1976278,"id":172},{"x":-25,"y":775,"z":-75,"color":1976278,"id":173},{"x":-25,"y":775,"z":-25,"color":1976278,"id":174},{"x":-25,"y":725,"z":325,"color":4974578,"id":175},{"x":-25,"y":775,"z":325,"color":4974578,"id":176},{"x":-25,"y":825,"z":325,"color":4974578,"id":177},{"x":-25,"y":875,"z":325,"color":526344,"id":178},{"x":-25,"y":925,"z":325,"color":526344,"id":179},{"x":-25,"y":975,"z":275,"color":526344,"id":180},{"x":-25,"y":1025,"z":225,"color":526344,"id":181},{"x":-25,"y":1025,"z":175,"color":526344,"id":182},{"x":-25,"y":1025,"z":125,"color":526344,"id":183},{"x":-25,"y":975,"z":75,"color":526344,"id":184},{"x":-25,"y":875,"z":25,"color":526344,"id":185},{"x":-25,"y":925,"z":25,"color":526344,"id":186},{"x":-25,"y":825,"z":-25,"color":526344,"id":187},{"x":-25,"y":825,"z":-75,"color":526344,"id":188},{"x":-25,"y":1075,"z":125,"color":526344,"id":189},{"x":-25,"y":1075,"z":75,"color":526344,"id":190},{"x":-25,"y":1075,"z":25,"color":526344,"id":191},{"x":-25,"y":475,"z":25,"color":526344,"id":192},{"x":-25,"y":475,"z":-25,"color":526344,"id":193},{"x":-25,"y":525,"z":-75,"color":526344,"id":194},{"x":-25,"y":575,"z":-125,"color":526344,"id":195},{"x":-25,"y":625,"z":-175,"color":526344,"id":196},{"x":-25,"y":675,"z":-175,"color":526344,"id":197},{"x":-25,"y":725,"z":-175,"color":526344,"id":198},{"x":-25,"y":775,"z":-175,"color":526344,"id":199},{"x":-25,"y":825,"z":-175,"color":526344,"id":200},{"x":-25,"y":875,"z":-175,"color":526344,"id":201},{"x":-25,"y":925,"z":-25,"color":526344,"id":202},{"x":-25,"y":925,"z":-75,"color":526344,"id":203},{"x":-25,"y":925,"z":-125,"color":526344,"id":204},{"x":-25,"y":975,"z":-75,"color":526344,"id":205},{"x":-25,"y":875,"z":-25,"color":4974578,"id":206},{"x":-25,"y":875,"z":-75,"color":4974578,"id":207},{"x":-25,"y":975,"z":-25,"color":4974578,"id":208},{"x":-25,"y":975,"z":25,"color":4974578,"id":209},{"x":-25,"y":1025,"z":25,"color":4974578,"id":210},{"x":-25,"y":1025,"z":75,"color":4974578,"id":211},{"x":-25,"y":875,"z":-125,"color":1976278,"id":212},{"x":-25,"y":825,"z":-125,"color":1976278,"id":213},{"x":-25,"y":1025,"z":-25,"color":328965,"id":214},{"x":-25,"y":675,"z":-75,"color":328965,"id":215},{"x":-25,"y":775,"z":175,"color":16115608,"id":216},{"x":-25,"y":675,"z":-25,"color":16115608,"id":217},{"x":-25,"y":725,"z":-25,"color":16115608,"id":218},{"x":-25,"y":625,"z":-25,"color":16115608,"id":219},{"x":-25,"y":625,"z":-125,"color":16115608,"id":220},{"x":-25,"y":725,"z":175,"color":16250349,"id":221},{"x":-25,"y":675,"z":175,"color":16250349,"id":222},{"x":-25,"y":775,"z":125,"color":16250349,"id":223},{"x":-25,"y":775,"z":75,"color":16250349,"id":224},{"x":-25,"y":775,"z":25,"color":16250349,"id":225},{"x":-25,"y":725,"z":125,"color":16250349,"id":226},{"x":-25,"y":675,"z":125,"color":16250349,"id":227},{"x":-25,"y":625,"z":125,"color":16250349,"id":228},{"x":-25,"y":625,"z":75,"color":16250349,"id":229},{"x":-25,"y":625,"z":25,"color":16250349,"id":230},{"x":-25,"y":775,"z":-125,"color":16250349,"id":231},{"x":-25,"y":725,"z":-125,"color":16250349,"id":232},{"x":-25,"y":675,"z":-125,"color":16250349,"id":233},{"x":-25,"y":625,"z":-75,"color":16250349,"id":234},{"x":-25,"y":575,"z":-75,"color":16115608,"id":235},{"x":-125,"y":775,"z":525,"color":328965,"id":236},{"x":-125,"y":725,"z":525,"color":328965,"id":237},{"x":-175,"y":775,"z":525,"color":328965,"id":238},{"x":-175,"y":725,"z":525,"color":328965,"id":239},{"x":-125,"y":825,"z":475,"color":328965,"id":240},{"x":-175,"y":825,"z":475,"color":328965,"id":241},{"x":-125,"y":825,"z":375,"color":328965,"id":242},{"x":-225,"y":825,"z":475,"color":328965,"id":243},{"x":-275,"y":825,"z":475,"color":328965,"id":244},{"x":-225,"y":725,"z":525,"color":328965,"id":245},{"x":-125,"y":675,"z":475,"color":328965,"id":246},{"x":-125,"y":625,"z":475,"color":328965,"id":247},{"x":-175,"y":675,"z":475,"color":328965,"id":248},{"x":-175,"y":625,"z":475,"color":328965,"id":249},{"x":-275,"y":725,"z":525,"color":328965,"id":250},{"x":-175,"y":825,"z":375,"color":328965,"id":251},{"x":-225,"y":825,"z":375,"color":328965,"id":252},{"x":-75,"y":875,"z":325,"color":328965,"id":253},{"x":-125,"y":875,"z":325,"color":328965,"id":254},{"x":-75,"y":975,"z":275,"color":328965,"id":255},{"x":-125,"y":975,"z":275,"color":328965,"id":256},{"x":-175,"y":975,"z":275,"color":328965,"id":257},{"x":-175,"y":875,"z":325,"color":328965,"id":258},{"x":-75,"y":925,"z":325,"color":328965,"id":259},{"x":-125,"y":925,"z":325,"color":328965,"id":260},{"x":-175,"y":925,"z":325,"color":328965,"id":261},{"x":-75,"y":1025,"z":225,"color":328965,"id":262},{"x":-125,"y":1025,"z":225,"color":328965,"id":263},{"x":-175,"y":1025,"z":225,"color":328965,"id":264},{"x":-75,"y":1075,"z":125,"color":328965,"id":265},{"x":-125,"y":1075,"z":125,"color":328965,"id":266},{"x":-75,"y":1075,"z":175,"color":328965,"id":267},{"x":-125,"y":1075,"z":175,"color":328965,"id":268},{"x":-175,"y":1075,"z":175,"color":328965,"id":269},{"x":-225,"y":1075,"z":175,"color":328965,"id":270},{"x":-225,"y":1025,"z":225,"color":328965,"id":271},{"x":-225,"y":975,"z":275,"color":328965,"id":272},{"x":-225,"y":925,"z":325,"color":328965,"id":273},{"x":-225,"y":875,"z":325,"color":328965,"id":274},{"x":-125,"y":825,"z":425,"color":328965,"id":275},{"x":-225,"y":825,"z":425,"color":328965,"id":276},{"x":-275,"y":825,"z":425,"color":328965,"id":277},{"x":-275,"y":825,"z":375,"color":328965,"id":278},{"x":-325,"y":825,"z":475,"color":328965,"id":279},{"x":-325,"y":825,"z":375,"color":328965,"id":280},{"x":-175,"y":825,"z":425,"color":328965,"id":281},{"x":-225,"y":775,"z":525,"color":328965,"id":282},{"x":-275,"y":775,"z":525,"color":328965,"id":283},{"x":-325,"y":775,"z":525,"color":328965,"id":284},{"x":-325,"y":725,"z":525,"color":328965,"id":285},{"x":-125,"y":575,"z":425,"color":328965,"id":286},{"x":-125,"y":525,"z":425,"color":328965,"id":287},{"x":-125,"y":475,"z":425,"color":328965,"id":288},{"x":-125,"y":425,"z":425,"color":328965,"id":289},{"x":-125,"y":375,"z":425,"color":328965,"id":290},{"x":-125,"y":325,"z":425,"color":328965,"id":291},{"x":-125,"y":275,"z":375,"color":328965,"id":292},{"x":-175,"y":275,"z":375,"color":328965,"id":293},{"x":-175,"y":325,"z":425,"color":328965,"id":294},{"x":-175,"y":375,"z":425,"color":328965,"id":295},{"x":-175,"y":425,"z":425,"color":328965,"id":296},{"x":-175,"y":475,"z":425,"color":328965,"id":297},{"x":-175,"y":525,"z":425,"color":328965,"id":298},{"x":-125,"y":575,"z":325,"color":328965,"id":299},{"x":-175,"y":575,"z":425,"color":328965,"id":300},{"x":-225,"y":625,"z":475,"color":328965,"id":301},{"x":-225,"y":675,"z":475,"color":328965,"id":302},{"x":-275,"y":625,"z":475,"color":328965,"id":303},{"x":-275,"y":675,"z":475,"color":328965,"id":304},{"x":-225,"y":525,"z":425,"color":328965,"id":305},{"x":-225,"y":475,"z":425,"color":328965,"id":306},{"x":-225,"y":425,"z":425,"color":328965,"id":307},{"x":-225,"y":375,"z":425,"color":328965,"id":308},{"x":-225,"y":325,"z":425,"color":328965,"id":309},{"x":-225,"y":575,"z":425,"color":328965,"id":310},{"x":-125,"y":625,"z":525,"color":1976278,"id":311},{"x":-125,"y":625,"z":575,"color":1976278,"id":312},{"x":-125,"y":675,"z":525,"color":1976278,"id":313},{"x":-125,"y":675,"z":575,"color":1976278,"id":314},{"x":-125,"y":725,"z":575,"color":1976278,"id":315},{"x":-125,"y":675,"z":625,"color":1976278,"id":316},{"x":-125,"y":625,"z":625,"color":1976278,"id":317},{"x":-125,"y":625,"z":675,"color":1976278,"id":318},{"x":-125,"y":575,"z":575,"color":1976278,"id":319},{"x":-125,"y":575,"z":625,"color":1976278,"id":320},{"x":-125,"y":575,"z":675,"color":1976278,"id":321},{"x":-125,"y":525,"z":625,"color":1976278,"id":322},{"x":-125,"y":525,"z":675,"color":1976278,"id":323},{"x":-125,"y":525,"z":575,"color":1976278,"id":324},{"x":-125,"y":575,"z":525,"color":328965,"id":325},{"x":-125,"y":525,"z":525,"color":328965,"id":326},{"x":-125,"y":475,"z":575,"color":328965,"id":327},{"x":-125,"y":475,"z":625,"color":328965,"id":328},{"x":-125,"y":475,"z":675,"color":328965,"id":329},{"x":-125,"y":525,"z":725,"color":328965,"id":330},{"x":-125,"y":575,"z":725,"color":328965,"id":331},{"x":-125,"y":625,"z":725,"color":328965,"id":332},{"x":-175,"y":625,"z":725,"color":328965,"id":333},{"x":-175,"y":575,"z":725,"color":328965,"id":334},{"x":-175,"y":525,"z":725,"color":328965,"id":335},{"x":-175,"y":475,"z":675,"color":328965,"id":336},{"x":-125,"y":675,"z":675,"color":328965,"id":337},{"x":-175,"y":675,"z":675,"color":328965,"id":338},{"x":-125,"y":725,"z":625,"color":328965,"id":339},{"x":-175,"y":725,"z":625,"color":328965,"id":340},{"x":-125,"y":775,"z":575,"color":328965,"id":341},{"x":-175,"y":775,"z":575,"color":328965,"id":342},{"x":-175,"y":325,"z":475,"color":1976278,"id":343},{"x":-175,"y":325,"z":525,"color":1976278,"id":344},{"x":-175,"y":325,"z":575,"color":1976278,"id":345},{"x":-175,"y":325,"z":625,"color":1976278,"id":346},{"x":-175,"y":375,"z":575,"color":1976278,"id":347},{"x":-175,"y":375,"z":525,"color":1976278,"id":348},{"x":-175,"y":275,"z":475,"color":1976278,"id":349},{"x":-175,"y":225,"z":475,"color":1976278,"id":350},{"x":-175,"y":175,"z":475,"color":1976278,"id":351},{"x":-175,"y":225,"z":525,"color":1976278,"id":352},{"x":-175,"y":275,"z":525,"color":1976278,"id":353},{"x":-175,"y":275,"z":575,"color":1976278,"id":354},{"x":-175,"y":275,"z":625,"color":1976278,"id":355},{"x":-175,"y":275,"z":675,"color":1976278,"id":356},{"x":-175,"y":225,"z":675,"color":1976278,"id":357},{"x":-175,"y":225,"z":625,"color":1976278,"id":358},{"x":925,"y":25,"z":1175,"color":1976278,"id":359},{"x":-175,"y":375,"z":475,"color":0,"id":360},{"x":-175,"y":425,"z":525,"color":0,"id":361},{"x":-175,"y":425,"z":575,"color":0,"id":362},{"x":-175,"y":375,"z":625,"color":0,"id":363},{"x":-175,"y":325,"z":675,"color":0,"id":364},{"x":-175,"y":275,"z":725,"color":0,"id":365},{"x":-175,"y":225,"z":725,"color":0,"id":366},{"x":-175,"y":225,"z":575,"color":0,"id":367},{"x":-175,"y":175,"z":525,"color":0,"id":368},{"x":-175,"y":175,"z":575,"color":0,"id":369},{"x":-175,"y":175,"z":625,"color":0,"id":370},{"x":-175,"y":175,"z":675,"color":0,"id":371},{"x":-175,"y":125,"z":475,"color":0,"id":372},{"x":1225,"y":25,"z":1125,"color":0,"id":373},{"x":-175,"y":175,"z":425,"color":4974578,"id":374},{"x":-175,"y":225,"z":425,"color":4974578,"id":375},{"x":-175,"y":275,"z":425,"color":4974578,"id":376},{"x":-175,"y":175,"z":375,"color":4974578,"id":377},{"x":-175,"y":225,"z":375,"color":4974578,"id":378},{"x":-175,"y":225,"z":325,"color":0,"id":379},{"x":-175,"y":175,"z":325,"color":0,"id":380},{"x":-175,"y":125,"z":425,"color":0,"id":381},{"x":-175,"y":125,"z":375,"color":0,"id":382},{"x":-75,"y":375,"z":25,"color":0,"id":383},{"x":-75,"y":425,"z":25,"color":4974578,"id":384},{"x":-75,"y":425,"z":-25,"color":4974578,"id":385},{"x":-75,"y":475,"z":-75,"color":4974578,"id":386},{"x":-75,"y":525,"z":-125,"color":4974578,"id":387},{"x":-75,"y":575,"z":-175,"color":4974578,"id":388},{"x":-75,"y":625,"z":-225,"color":4974578,"id":389},{"x":-75,"y":675,"z":-225,"color":4974578,"id":390},{"x":-75,"y":725,"z":-225,"color":4974578,"id":391},{"x":-75,"y":675,"z":-275,"color":4974578,"id":392},{"x":-25,"y":775,"z":-225,"color":328965,"id":393},{"x":-75,"y":725,"z":-275,"color":328965,"id":394},{"x":-75,"y":425,"z":-75,"color":328965,"id":395},{"x":-75,"y":475,"z":-125,"color":328965,"id":396},{"x":-75,"y":525,"z":-175,"color":328965,"id":397},{"x":-75,"y":575,"z":-225,"color":328965,"id":398},{"x":-75,"y":625,"z":-275,"color":328965,"id":399},{"x":-75,"y":775,"z":-275,"color":328965,"id":400},{"x":-75,"y":775,"z":-325,"color":328965,"id":401},{"x":-75,"y":775,"z":-375,"color":328965,"id":402},{"x":-75,"y":775,"z":-425,"color":328965,"id":403},{"x":-75,"y":775,"z":-475,"color":328965,"id":404},{"x":-75,"y":725,"z":-325,"color":1976278,"id":405},{"x":-75,"y":725,"z":-375,"color":1976278,"id":406},{"x":-75,"y":725,"z":-425,"color":1976278,"id":407},{"x":-75,"y":675,"z":-325,"color":1976278,"id":408},{"x":-75,"y":625,"z":-325,"color":1976278,"id":409},{"x":-75,"y":625,"z":-375,"color":1976278,"id":410},{"x":-75,"y":625,"z":-425,"color":1976278,"id":411},{"x":-75,"y":625,"z":-475,"color":1976278,"id":412},{"x":-75,"y":575,"z":-325,"color":1976278,"id":413},{"x":-75,"y":575,"z":-375,"color":1976278,"id":414},{"x":-75,"y":575,"z":-425,"color":1976278,"id":415},{"x":-75,"y":575,"z":-275,"color":328965,"id":416},{"x":825,"y":25,"z":-575,"color":328965,"id":417},{"x":-75,"y":525,"z":-325,"color":328965,"id":418},{"x":-75,"y":525,"z":-375,"color":328965,"id":419},{"x":-75,"y":525,"z":-425,"color":328965,"id":420},{"x":-75,"y":525,"z":-475,"color":328965,"id":421},{"x":-75,"y":575,"z":-475,"color":328965,"id":422},{"x":-75,"y":575,"z":-525,"color":328965,"id":423},{"x":-75,"y":575,"z":-575,"color":328965,"id":424},{"x":825,"y":25,"z":-625,"color":328965,"id":425},{"x":-75,"y":625,"z":-525,"color":328965,"id":426},{"x":-75,"y":675,"z":-525,"color":328965,"id":427},{"x":-75,"y":725,"z":-525,"color":328965,"id":428},{"x":-75,"y":725,"z":-475,"color":328965,"id":429},{"x":-75,"y":725,"z":-575,"color":328965,"id":430},{"x":-75,"y":625,"z":-575,"color":1976278,"id":431},{"x":-75,"y":675,"z":-375,"color":4974578,"id":432},{"x":-75,"y":675,"z":-425,"color":4974578,"id":433},{"x":-75,"y":675,"z":-475,"color":4974578,"id":434},{"x":-75,"y":675,"z":-575,"color":4974578,"id":435},{"x":-75,"y":675,"z":-625,"color":328965,"id":436},{"x":-75,"y":625,"z":-625,"color":328965,"id":437},{"x":-75,"y":375,"z":75,"color":4974578,"id":438},{"x":-75,"y":375,"z":125,"color":1976278,"id":439}]'
					);
				});
				
				$("#workpush").click(function(e){
 					var oCanvas = document.getElementById("voxelCanvas");
 					var strDataURI = oCanvas.toDataURL(); 
 					$('#picture').attr("src",strDataURI);
        		});
				
				$("#workpost").click(function(e){
					var oCanvas = document.getElementById("voxelCanvas");
		 			var strDataURI = oCanvas.toDataURL(); 
		 			strDataURI = strDataURI.replace('data:image/png;base64,','')
		 			var tag1 = $('#tag1').attr('value');
		 			var tag2 = $('#tag2').attr('value');
		 			var tag3 = $('#tag3').attr('value');
		 			var cubejson = voxelPainter.CubeJSON();
 			 		e.preventDefault();
 			 		$('#worksubmit').modal('hide');
                	$.ajax({
                	url : 'api/v1/work',
                	type : 'POST',
                	data　: {img:strDataURI,tag1:tag1,tag2:tag2,tag3:tag3,cubejson:cubejson},
                	complete:function(x,t){ 
                	alert("发布成功");
                	history.go(0);
                }
            });	
        });
		
		}
		
		);
	
	</script>