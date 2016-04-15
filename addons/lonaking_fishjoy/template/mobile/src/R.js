/**
 * Resource Manager.
 */

(function(){

var ns = Q.use("fish");

var R = ns.R = {};

R.sources = 
[
	{id:"mainbg", size:456, src:"../addons/lonaking_fishjoy/template/mobile/images/game_bg_2_hd.jpg?"+Math.random()},
	{id:"bottom", size:50, src:"../addons/lonaking_fishjoy/template/mobile/images/bottom.png?"+Math.random()},
	{id:"fish1", size:100, src:"../addons/lonaking_fishjoy/template/mobile/images/fish1.png?"+Math.random()},
	{id:"fish2", size:16, src:"../addons/lonaking_fishjoy/template/mobile/images/fish2.png?"+Math.random()},
	{id:"cannon1", size:11, src:"../addons/lonaking_fishjoy/template/mobile/images/cannon1.png?"+Math.random()},
	{id:"cannon2", size:11, src:"../addons/lonaking_fishjoy/template/mobile/images/cannon2.png?"+Math.random()},
	{id:"bullet", size:8, src:"../addons/lonaking_fishjoy/template/mobile/images/bullet.png?"+Math.random()},
	{id:"web", size:93, src:"../addons/lonaking_fishjoy/template/mobile/images/web.png?"+Math.random()},
	{id:"numBlack", size:1, src:"../addons/lonaking_fishjoy/template/mobile/images/number_black.png?"+Math.random()},
	{id:"coinAni1", size:19, src:"../addons/lonaking_fishjoy/template/mobile/images/coinAni1.png?"+Math.random()},
	{id:"coinAni2", size:22, src:"../addons/lonaking_fishjoy/template/mobile/images/coinAni2.png?"+Math.random()},
	{id:"coinText", size:16, src:"../addons/lonaking_fishjoy/template/mobile/images/coinText.png?"+Math.random()}
];

R.init = function(images)
{
	this.images = images;
	//this.convertPlistImage();	
	this.initResources();
};

R.initResources = function()
{
	this.mainbg = this.getImage("mainbg");
	this.bottom = this.getImage("bottom");
	
	this.bottombar = {image:this.bottom, rect:[0,0,765,72]};
	this.cannonMinus = {image:this.bottom, up:{rect:[132,72,44,31]}, down:{rect:[88,72,44,31]}, width:44, height:31};
	this.cannonPlus = {image:this.bottom, up:{rect:[44,72,44,31]}, down:{rect:[0,72,44,31]}, width:44, height:31};
	
	this.numBlack = 
	{
		image: this.getImage("numBlack"),
		9: [0, 0, 20, 24],
		8: [0, 24, 20, 24],
		7: [0, 48, 20, 24],
		6: [0, 72, 20, 24],
		5: [0, 96, 20, 24],
		4: [0, 120, 20, 24],
		3: [0, 144, 20, 24],
		2: [0, 168, 20, 24],
		1: [0, 192, 20, 24],
		0: [0, 216, 20, 24]
	};
	
	this.coinText = 
	{
		image: this.getImage("coinText"),
		0: [0, 0, 36, 49],
		1: [36, 0, 36, 49],
		2: [72, 0, 36, 49],
		3: [108, 0, 36, 49],
		4: [144, 0, 36, 49],
		5: [180, 0, 36, 49],
		6: [216, 0, 36, 49],
		7: [252, 0, 36, 49],
		8: [288, 0, 36, 49],
		9: [324, 0, 36, 49],
		"+": [360, 0, 36, 49]
	};
	
	this.coinAni1 = {image:this.getImage("coinAni1"), 
	frames:[
	{rect:[0,0,60,60]},
	{rect:[0,60,60,60]},
	{rect:[0,120,60,60]},
	{rect:[0,180,60,60]},
	{rect:[0,240,60,60]},
	{rect:[0,300,60,60]},
	{rect:[0,360,60,60]},
	{rect:[0,420,60,60]},
	{rect:[0,480,60,60]},
	{rect:[0,540,60,60]}
	], regX:30, regY:30, scaleX:0.8, scaleY:0.8, useFrames:true, interval:2};
	
	this.coinAni2 = {image:this.getImage("coinAni2"), 
	frames:[
	{rect:[0,0,60,60]},
	{rect:[0,60,60,60]},
	{rect:[0,120,60,60]},
	{rect:[0,180,60,60]},
	{rect:[0,240,60,60]},
	{rect:[0,300,60,60]},
	{rect:[0,360,60,60]},
	{rect:[0,420,60,60]},
	{rect:[0,480,60,60]},
	{rect:[0,540,60,60]}
	], regX:30, regY:30, scaleX:0.8, scaleY:0.8, useFrames:true, interval:2};
	
	var fish1 = {image:this.getImage("fish1"), 
	frames:[
	{rect:[0,0,55,37], label:"swim"},
	{rect:[0,37,55,37]},
	{rect:[0,74,55,37]},
	{rect:[0,111,55,37], jump:"swim"},
	{rect:[0,148,55,37], label:"capture"},
	{rect:[0,185,55,37]},
	{rect:[0,222,55,37]},
	{rect:[0,259,55,37], jump:"capture"}
	], polyArea:[{x:10, y:5}, {x:55, y:5}, {x:55, y:22}, {x:10, y:22}],
	mixin:{coin:1, captureRate:0.55, maxNumGroup:8, minSpeed:0.5, maxSpeed:1.2, regX:35, regY:12, useFrames:true, interval:10}};
	
	var fish2 = {image:this.getImage("fish2"), 
	frames:[
	{rect:[0,0,78,64], label:"swim"},
	{rect:[0,64,78,64]},
	{rect:[0,128,78,64]},
	{rect:[0,192,78,64], jump:"swim"},
	{rect:[0,256,78,64], label:"capture"},
	{rect:[0,320,78,64]},
	{rect:[0,384,78,64]},
	{rect:[0,448,78,64], jump:"capture"}
	], polyArea:[{x:15, y:10}, {x:78, y:10}, {x:78, y:32}, {x:15, y:32}],
	mixin:{coin:3, captureRate:0.50, maxNumGroup:6, minSpeed:0.5, maxSpeed:1.2, regX:58, regY:20, useFrames:true, interval:10}};
	
	
	var cannon1 = {image:this.getImage("cannon1"),
	frames:[
	{rect:[0,0,74,74]},
	{rect:[0,74,74,74]},
	{rect:[0,148,74,74]},
	{rect:[0,222,74,74]},
	{rect:[0,296,74,74], stop:1}
	], mixin:{regX:37, regY:45, useFrames:true, interval:3, power:1}};
	
	var cannon2 = {image:this.getImage("cannon2"), 
	frames:[
	{rect:[0,0,74,76]},
	{rect:[0,76,74,76]},
	{rect:[0,152,74,76]},
	{rect:[0,228,74,76]},
	{rect:[0,304,74,76], stop:1}
	], mixin:{regX:37, regY:46, useFrames:true, interval:3, power:2}};
	
	this.fishTypes = [null, fish1, fish2];
	this.cannonTypes = [null, cannon1, cannon2];
		
	var bullet = this.getImage("bullet");
	this.bullets = [
	{image:bullet, rect:[86,0,24,26], regX:12, regY:13},
	{image:bullet, rect:[61,0,25,29], regX:12, regY:14},
	{image:bullet, rect:[32,35,27,31], regX:13, regY:15},
	{image:bullet, rect:[30,82,29,33], regX:14, regY:16},
	{image:bullet, rect:[0,82,30,34], regX:15, regY:17},
	{image:bullet, rect:[30,0,31,35], regX:15, regY:17},
	{image:bullet, rect:[0,44,32,38], regX:16, regY:19}
	];
	
	var web = this.getImage("web");
	this.webs = [
	{image:web, rect:[319,355,116,118], regX:58, regY:59, polyArea:[{x:20, y:20}, {x:100, y:20}, {x:100, y:100}, {x:20, y:100}]},
	{image:web, rect:[0,399,137,142], regX:68, regY:71, polyArea:[{x:20, y:20}, {x:120, y:20}, {x:120, y:120}, {x:20, y:120}]},
	{image:web, rect:[163,355,156,162], regX:78, regY:81, polyArea:[{x:20, y:20}, {x:140, y:20}, {x:140, y:140}, {x:20, y:140}]},
	{image:web, rect:[242,181,180,174], regX:90, regY:87, polyArea:[{x:20, y:20}, {x:160, y:20}, {x:160, y:160}, {x:20, y:160}]},
	{image:web, rect:[0,244,163,155], regX:81, regY:77, polyArea:[{x:10, y:10}, {x:150, y:10}, {x:150, y:150}, {x:10, y:150}]},
	{image:web, rect:[242,0,191,181], regX:95, regY:90, polyArea:[{x:10, y:10}, {x:180, y:10}, {x:180, y:180}, {x:10, y:180}]},
	{image:web, rect:[0,0,242,244], regX:121, regY:122, polyArea:[{x:30, y:30}, {x:210, y:30}, {x:210, y:210}, {x:30, y:210}]}
	];
};

R.convertPlistImage = function()
{
	this.fish = this.getImage("fish");
	this.shark = this.getImage("shark");
	this.cannon = this.getImage("cannon");
	
	var dict = [
	{frame:[1438,419,60,60], offset:[0,0], rotated:false, sourceRect:[0,0,60,60], sourceSize:[60,60]},
	{frame:[1396,507,60,60], offset:[0,0], rotated:false, sourceRect:[0,0,60,60], sourceSize:[60,60]},
	{frame:[1377,419,60,60], offset:[0,0], rotated:false, sourceRect:[0,0,60,60], sourceSize:[60,60]},
	{frame:[1051,1295,54,60], offset:[0,0], rotated:false, sourceRect:[3,0,54,60], sourceSize:[60,60]},
	{frame:[644,1254,42,60], offset:[0,0], rotated:true, sourceRect:[9,0,42,60], sourceSize:[60,60]},
	{frame:[133,2018,28,60], offset:[-1,0], rotated:true, sourceRect:[15,0,28,60], sourceSize:[60,60]},
	{frame:[0,2025,14,60], offset:[0,0], rotated:true, sourceRect:[23,0,14,60], sourceSize:[60,60]},
	{frame:[934,685,26,60], offset:[0,0], rotated:true, sourceRect:[17,0,26,60], sourceSize:[60,60]},
	{frame:[583,1254,42,60], offset:[0,0], rotated:true, sourceRect:[9,0,42,60], sourceSize:[60,60]},
	{frame:[1034,1372,54,60], offset:[-1,0], rotated:false, sourceRect:[2,0,54,60], sourceSize:[60,60]}
	];
	var props = {maxNumGroup:8, minSpeed:0.5, maxSpeed:1.2, regX:35, regY:12, useFrames:true, interval:10};
	var coinAni1 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
		
	var dict = [
	{frame:[1522,78,60,60], offset:[0,0], rotated:false, sourceRect:[0,0,60,60], sourceSize:[60,60]},
	{frame:[1470,248,60,60], offset:[0,0], rotated:false, sourceRect:[0,0,60,60], sourceSize:[60,60]},
	{frame:[1439,329,60,60], offset:[0,0], rotated:false, sourceRect:[0,0,60,60], sourceSize:[60,60]},
	{frame:[1134,1163,54,60], offset:[0,0], rotated:false, sourceRect:[3,0,54,60], sourceSize:[60,60]},
	{frame:[1337,670,42,60], offset:[0,0], rotated:true, sourceRect:[9,0,42,60], sourceSize:[60,60]},
	{frame:[194,2018,28,60], offset:[-1,0], rotated:true, sourceRect:[15,0,28,60], sourceSize:[60,60]},
	{frame:[61,2025,14,60], offset:[0,0], rotated:true, sourceRect:[23,0,14,60], sourceSize:[60,60]},
	{frame:[1356,570,26,60], offset:[0,0], rotated:true, sourceRect:[17,0,26,60], sourceSize:[60,60]},
	{frame:[705,1254,42,60], offset:[0,0], rotated:true, sourceRect:[9,0,42,60], sourceSize:[60,60]},
	{frame:[1105,1233,54,60], offset:[-1,0], rotated:false, sourceRect:[2,0,54,60], sourceSize:[60,60]}
	];
	var props = {maxNumGroup:8, minSpeed:0.5, maxSpeed:1.2, regX:35, regY:12, useFrames:true, interval:10};
	var coinAni2 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[572,1062,55,35], offset:[0,0], rotated:false, sourceRect:[0,1,55,35], sourceSize:[55,37]},
	{frame:[628,1062,55,33], offset:[0,0], rotated:false, sourceRect:[0,2,55,33], sourceSize:[55,37]},
	{frame:[684,1062,55,31], offset:[0,1], rotated:false, sourceRect:[0,2,55,31], sourceSize:[55,37]},
	{frame:[828,685,55,33], offset:[0,1], rotated:false, sourceRect:[0,1,55,33], sourceSize:[55,37]},
	{frame:[934,985,51,25], offset:[0,-4], rotated:false, sourceRect:[2,10,51,25], sourceSize:[55,37]},
	{frame:[884,685,49,27], offset:[1,-4], rotated:false, sourceRect:[4,9,49,27], sourceSize:[55,37]},
	{frame:[846,1042,51,27], offset:[0,-3], rotated:true, sourceRect:[2,8,51,27], sourceSize:[55,37]},
	{frame:[818,1042,51,27], offset:[1,-4], rotated:true, sourceRect:[3,9,51,27], sourceSize:[55,37]}
	];
	var props = {maxNumGroup:8, minSpeed:0.5, maxSpeed:1.2, regX:35, regY:12, useFrames:true, interval:10};
	var fish1 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[1194,450,74,58], offset:[1,-1], rotated:false, sourceRect:[3,4,74,58], sourceSize:[78,64]},
	{frame:[1234,832,70,54], offset:[3,1], rotated:true, sourceRect:[7,4,70,54], sourceSize:[78,64]},
	{frame:[1078,1092,74,44], offset:[1,2], rotated:true, sourceRect:[3,8,74,44], sourceSize:[78,64]},
	{frame:[314,1998,74,50], offset:[2,0], rotated:false, sourceRect:[4,7,74,50], sourceSize:[78,64]},
	{frame:[1080,965,72,58], offset:[0,3], rotated:true, sourceRect:[3,0,72,58], sourceSize:[78,64]},
	{frame:[995,646,66,58], offset:[4,3], rotated:true, sourceRect:[10,0,66,58], sourceSize:[78,64]},
	{frame:[992,1297,74,58], offset:[1,3], rotated:true, sourceRect:[3,0,74,58], sourceSize:[78,64]},
	{frame:[1054,646,66,56], offset:[4,4], rotated:true, sourceRect:[10,0,66,56], sourceSize:[78,64]}
	];
	var props = {maxNumGroup:6, minSpeed:0.5, maxSpeed:1.2, regX:58, regY:20, useFrames:true, interval:10};
	
	
	

	var fish2 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[1358,258,70,56], offset:[0,0], rotated:true, sourceRect:[1,0,70,56], sourceSize:[72,56]},
	{frame:[1415,258,70,54], offset:[0,1], rotated:true, sourceRect:[1,0,70,54], sourceSize:[72,56]},
	{frame:[389,1998,70,50], offset:[0,2], rotated:false, sourceRect:[1,1,70,50], sourceSize:[72,56]},
	{frame:[894,1244,70,52], offset:[0,2], rotated:false, sourceRect:[1,0,70,52], sourceSize:[72,56]},
	{frame:[1125,460,68,48], offset:[1,3], rotated:false, sourceRect:[3,1,68,48], sourceSize:[72,56]},
	{frame:[1157,284,66,48], offset:[2,3], rotated:false, sourceRect:[5,1,66,48], sourceSize:[72,56]},
	{frame:[1090,284,66,48], offset:[2,3], rotated:false, sourceRect:[5,1,66,48], sourceSize:[72,56]},
	{frame:[1056,460,68,48], offset:[2,3], rotated:false, sourceRect:[4,1,68,48], sourceSize:[72,56]}
	];
	var props = {maxNumGroup:6, minSpeed:0.5, maxSpeed:1.2, regX:52, regY:18, useFrames:true, interval:10};
	var fish3 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
		
	var dict = [
	{frame:[911,1519,77,53], offset:[0,3], rotated:true, sourceRect:[0,0,77,53], sourceSize:[77,59]},
	{frame:[740,1042,77,51], offset:[0,4], rotated:false, sourceRect:[0,0,77,51], sourceSize:[77,59]},
	{frame:[1224,283,77,45], offset:[0,5], rotated:false, sourceRect:[0,2,77,45], sourceSize:[77,59]},
	{frame:[460,1998,77,49], offset:[0,4], rotated:false, sourceRect:[0,1,77,49], sourceSize:[77,59]},
	{frame:[1123,1091,71,57], offset:[-1,0], rotated:true, sourceRect:[2,1,71,57], sourceSize:[77,59]},
	{frame:[918,1373,67,57], offset:[1,1], rotated:true, sourceRect:[6,0,67,57], sourceSize:[77,59]},
	{frame:[1178,832,71,55], offset:[0,1], rotated:true, sourceRect:[3,1,71,55], sourceSize:[77,59]},
	{frame:[1139,965,71,57], offset:[0,0], rotated:true, sourceRect:[3,1,71,57], sourceSize:[77,59]}
	];
	var props = {maxNumGroup:6, minSpeed:0.5, maxSpeed:1.2, regX:57, regY:18, useFrames:true, interval:10};
	var fish4 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[1353,166,91,90], offset:[3,-1], rotated:true, sourceRect:[11,17,91,90], sourceSize:[107,122]},
	{frame:[782,1960,87,88], offset:[5,-1], rotated:false, sourceRect:[15,18,87,88], sourceSize:[107,122]},
	{frame:[1430,0,89,86], offset:[4,-1], rotated:true, sourceRect:[13,19,89,86], sourceSize:[107,122]},
	{frame:[688,1960,93,88], offset:[2,-1], rotated:false, sourceRect:[9,18,93,88], sourceSize:[107,122]},
	{frame:[1169,333,91,116], offset:[2,0], rotated:false, sourceRect:[10,3,91,116], sourceSize:[107,122]},
	{frame:[1178,509,97,118], offset:[-1,1], rotated:false, sourceRect:[4,1,97,118], sourceSize:[107,122]},
	{frame:[1176,713,89,118], offset:[4,-1], rotated:false, sourceRect:[13,3,89,118], sourceSize:[107,122]},
	{frame:[1078,509,99,118], offset:[-2,0], rotated:false, sourceRect:[2,2,99,118], sourceSize:[107,122]}
	];
	var props = {maxNumGroup:5, minSpeed:0.5, maxSpeed:1.2, regX:67, regY:50, useFrames:true, interval:10};
	var fish5 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[1352,0,91,77], offset:[4,0], rotated:true, sourceRect:[11,1,91,77], sourceSize:[105,79]},
	{frame:[1444,166,81,77], offset:[2,0], rotated:true, sourceRect:[14,1,81,77], sourceSize:[105,79]},
	{frame:[1370,92,73,77], offset:[1,0], rotated:true, sourceRect:[17,1,73,77], sourceSize:[105,79]},
	{frame:[1361,329,89,77], offset:[-4,0], rotated:true, sourceRect:[4,1,89,77], sourceSize:[105,79]},
	{frame:[1273,166,99,79], offset:[2,0], rotated:true, sourceRect:[5,0,99,79], sourceSize:[105,79]},
	{frame:[1272,0,99,79], offset:[3,0], rotated:true, sourceRect:[6,0,99,79], sourceSize:[105,79]},
	{frame:[1192,0,99,79], offset:[2,0], rotated:true, sourceRect:[5,0,99,79], sourceSize:[105,79]},
	{frame:[1276,508,99,79], offset:[1,0], rotated:true, sourceRect:[4,0,99,79], sourceSize:[105,79]},
	{frame:[918,1297,73,75], offset:[2,2], rotated:false, sourceRect:[18,0,73,75], sourceSize:[105,79]},
	{frame:[911,1441,71,77], offset:[3,0], rotated:false, sourceRect:[20,1,71,77], sourceSize:[105,79]},
	{frame:[1448,90,73,75], offset:[2,2], rotated:false, sourceRect:[18,0,73,75], sourceSize:[105,79]},
	{frame:[1517,0,71,77], offset:[3,0], rotated:false, sourceRect:[20,1,71,77], sourceSize:[105,79]}
	];
	var props = {maxNumGroup:3, minSpeed:0.5, maxSpeed:1.2, regX:65, regY:25, useFrames:true, interval:10};
	var fish6 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[833,1297,84,143], offset:[-1,1], rotated:false, sourceRect:[3,3,84,143], sourceSize:[92,151]},
	{frame:[853,867,80,143], offset:[0,-1], rotated:false, sourceRect:[6,5,80,143], sourceSize:[92,151]},
	{frame:[832,1450,78,147], offset:[2,-1], rotated:false, sourceRect:[9,3,78,147], sourceSize:[92,151]},
	{frame:[538,1963,84,149], offset:[3,0], rotated:true, sourceRect:[7,1,84,149], sourceSize:[92,151]},
	{frame:[867,1094,86,149], offset:[3,1], rotated:false, sourceRect:[6,0,86,149], sourceSize:[92,151]},
	{frame:[829,719,86,147], offset:[1,2], rotated:false, sourceRect:[4,0,86,147], sourceSize:[92,151]},
	{frame:[1090,166,86,117], offset:[-2,16], rotated:false, sourceRect:[1,1,86,117], sourceSize:[92,151]},
	{frame:[874,1011,82,113], offset:[-1,17], rotated:true, sourceRect:[4,2,82,113], sourceSize:[92,151]},
	{frame:[1111,628,84,113], offset:[-2,16], rotated:true, sourceRect:[2,3,84,113], sourceSize:[92,151]},
	{frame:[1225,628,84,111], offset:[-2,17], rotated:true, sourceRect:[2,3,84,111], sourceSize:[92,151]}
	];
	var props = {maxNumGroup:5, minSpeed:0.5, maxSpeed:0.8, regX:40, regY:50, useFrames:true, interval:10};
	var fish7 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[284,1825,172,124], offset:[1,1], rotated:true, sourceRect:[2,0,172,124], sourceSize:[174,126]},
	{frame:[264,1652,172,124], offset:[1,1], rotated:true, sourceRect:[2,0,172,124], sourceSize:[174,126]},
	{frame:[409,1825,172,122], offset:[1,0], rotated:true, sourceRect:[2,2,172,122], sourceSize:[174,126]},
	{frame:[415,1297,172,120], offset:[1,-1], rotated:true, sourceRect:[2,4,172,120], sourceSize:[174,126]},
	{frame:[389,1652,172,122], offset:[1,-1], rotated:true, sourceRect:[2,3,172,122], sourceSize:[174,126]},
	{frame:[264,1479,172,124], offset:[1,0], rotated:true, sourceRect:[2,1,172,124], sourceSize:[174,126]},
	{frame:[789,0,172,124], offset:[1,0], rotated:true, sourceRect:[2,1,172,124], sourceSize:[174,126]},
	{frame:[389,1479,172,122], offset:[1,1], rotated:true, sourceRect:[2,1,172,122], sourceSize:[174,126]},
	{frame:[449,915,170,122], offset:[2,0], rotated:true, sourceRect:[4,2,170,122], sourceSize:[174,126]},
	{frame:[933,342,168,122], offset:[3,2], rotated:true, sourceRect:[6,0,168,122], sourceSize:[174,126]},
	{frame:[828,516,168,122], offset:[3,1], rotated:true, sourceRect:[6,1,168,122], sourceSize:[174,126]},
	{frame:[914,0,168,122], offset:[3,2], rotated:true, sourceRect:[6,0,168,122], sourceSize:[174,126]}
	];
	var props = {maxNumGroup:3, minSpeed:0.5, maxSpeed:0.8, regX:90, regY:50, useFrames:true, interval:10};
	var fish8 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[640,0,148,173], offset:[6,1], rotated:false, sourceRect:[15,4,148,173], sourceSize:[166,183]},
	{frame:[572,892,146,169], offset:[6,-1], rotated:false, sourceRect:[16,8,146,169], sourceSize:[166,183]},
	{frame:[583,1098,148,155], offset:[4,0], rotated:false, sourceRect:[13,14,148,155], sourceSize:[166,183]},
	{frame:[719,891,150,133], offset:[2,0], rotated:true, sourceRect:[10,25,150,133], sourceSize:[166,183]},
	{frame:[697,1297,152,135], offset:[2,0], rotated:true, sourceRect:[9,24,152,135], sourceSize:[166,183]},
	{frame:[841,1784,158,155], offset:[0,0], rotated:true, sourceRect:[4,14,158,155], sourceSize:[166,183]},
	{frame:[505,722,156,169], offset:[2,1], rotated:false, sourceRect:[7,6,156,169], sourceSize:[166,183]},
	{frame:[133,1840,150,177], offset:[6,0], rotated:false, sourceRect:[14,3,150,177], sourceSize:[166,183]},
	{frame:[290,925,158,175], offset:[2,2], rotated:false, sourceRect:[6,2,158,175], sourceSize:[166,183]},
	{frame:[686,1798,154,161], offset:[5,-4], rotated:false, sourceRect:[11,15,154,161], sourceSize:[166,183]},
	{frame:[264,1301,150,177], offset:[8,3], rotated:false, sourceRect:[16,0,150,177], sourceSize:[166,183]},
	{frame:[1037,0,154,165], offset:[5,-6], rotated:false, sourceRect:[11,15,154,165], sourceSize:[166,183]}
	];
	var props = {maxNumGroup:2, minSpeed:0.5, maxSpeed:0.8, regX:120, regY:70, useFrames:true, interval:10};
	var fish9 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[668,533,168,159], offset:[3,3], rotated:true, sourceRect:[8,11,168,159], sourceSize:[178,187]},
	{frame:[0,380,166,187], offset:[2,0], rotated:false, sourceRect:[8,0,166,187], sourceSize:[178,187]},
	{frame:[512,1470,164,163], offset:[1,7], rotated:true, sourceRect:[8,5,164,163], sourceSize:[178,187]},
	{frame:[0,568,166,185], offset:[2,1], rotated:false, sourceRect:[8,0,166,185], sourceSize:[178,187]},
	{frame:[807,173,168,135], offset:[4,5], rotated:true, sourceRect:[9,21,168,135], sourceSize:[178,187]},
	{frame:[441,1101,170,141], offset:[4,5], rotated:true, sourceRect:[8,18,170,141], sourceSize:[178,187]},
	{frame:[512,1635,164,153], offset:[-1,-4], rotated:true, sourceRect:[6,21,164,153], sourceSize:[178,187]},
	{frame:[809,1623,160,149], offset:[-4,-1], rotated:true, sourceRect:[5,20,160,149], sourceSize:[178,187]},
	{frame:[532,1800,162,153], offset:[-4,0], rotated:true, sourceRect:[4,17,162,153], sourceSize:[178,187]},
	{frame:[676,1462,160,155], offset:[-1,7], rotated:true, sourceRect:[8,9,160,155], sourceSize:[178,187]}
	];
	var props = {maxNumGroup:2, minSpeed:0.5, maxSpeed:0.8, regX:100, regY:80, useFrames:true, interval:10};
	var fish10 = this.translateImage(this.fish, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
		
		
	var dict = [
	{frame:[504,648,501,206], offset:[2,-3], rotated:false, sourceRect:[6,35,501,206], sourceSize:[509,270]},
	{frame:[1551,0,497,208], offset:[4,-4], rotated:false, sourceRect:[10,35,497,208], sourceSize:[509,270]},
	{frame:[983,873,477,210], offset:[14,-4], rotated:false, sourceRect:[30,34,477,210], sourceSize:[509,270]},
	{frame:[499,855,483,212], offset:[11,-4], rotated:false, sourceRect:[24,33,483,212], sourceSize:[509,270]},
	{frame:[1551,416,495,212], offset:[5,-4], rotated:false, sourceRect:[12,33,495,212], sourceSize:[509,270]},
	{frame:[0,444,503,208], offset:[1,-4], rotated:false, sourceRect:[4,35,503,208], sourceSize:[509,270]},
	{frame:[1006,666,495,206], offset:[5,-3], rotated:false, sourceRect:[12,35,495,206], sourceSize:[509,270]},
	{frame:[1551,209,497,206], offset:[4,-3], rotated:false, sourceRect:[10,35,497,206], sourceSize:[509,270]},
	{frame:[0,883,483,192], offset:[9,-16], rotated:false, sourceRect:[22,55,483,192], sourceSize:[509,270]},
	{frame:[933,1084,423,264], offset:[38,3], rotated:false, sourceRect:[81,0,423,264], sourceSize:[509,270]},
	{frame:[1502,853,481,216], offset:[14,-16], rotated:false, sourceRect:[28,43,481,216], sourceSize:[509,270]},
	{frame:[1461,1070,429,258], offset:[34,-1], rotated:false, sourceRect:[74,7,429,258], sourceSize:[509,270]}
	];
	var props = {maxNumGroup:1, minSpeed:0.5, maxSpeed:0.6, regX:350, regY:130, useFrames:true, interval:10};
	var shark = this.translateImage(this.shark, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [
	{frame:[1034,222,516,219], offset:[0,-1], rotated:false, sourceRect:[0,28,516,219], sourceSize:[516,273]},
	{frame:[1034,0,516,221], offset:[0,-2], rotated:false, sourceRect:[0,28,516,221], sourceSize:[516,273]},
	{frame:[1525,629,498,223], offset:[9,-2], rotated:false, sourceRect:[18,27,498,223], sourceSize:[516,273]},
	{frame:[1022,442,502,223], offset:[7,-3], rotated:false, sourceRect:[14,28,502,223], sourceSize:[516,273]},
	{frame:[0,0,516,223], offset:[0,-3], rotated:false, sourceRect:[0,28,516,223], sourceSize:[516,273]},
	{frame:[517,0,516,221], offset:[0,-2], rotated:false, sourceRect:[0,28,516,221], sourceSize:[516,273]},
	{frame:[0,224,516,219], offset:[0,-1], rotated:false, sourceRect:[0,28,516,219], sourceSize:[516,273]},
	{frame:[517,222,516,219], offset:[0,-1], rotated:false, sourceRect:[0,28,516,219], sourceSize:[516,273]},
	{frame:[517,442,504,205], offset:[3,-14], rotated:false, sourceRect:[9,48,504,205], sourceSize:[516,273]},
	{frame:[0,1076,444,269], offset:[32,2], rotated:false, sourceRect:[68,0,444,269], sourceSize:[516,273]},
	{frame:[0,653,498,229], offset:[9,-14], rotated:false, sourceRect:[18,36,498,229], sourceSize:[516,273]},
	{frame:[484,1068,448,269], offset:[29,0], rotated:false, sourceRect:[63,2,448,269], sourceSize:[516,273]}
	];
	var props = {maxNumGroup:1, minSpeed:0.5, maxSpeed:0.6, regX:350, regY:130, useFrames:true, interval:10};
	var shark2 = this.translateImage(this.shark, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
		
	var dict = [
	{frame:[122,949,60,74], offset:[0,0], rotated:false, sourceRect:[7,0,60,74], sourceSize:[74,74]},
	{frame:[61,915,60,74], offset:[0,0], rotated:false, sourceRect:[7,0,60,74], sourceSize:[74,74]},
	{frame:[244,1096,60,72], offset:[0,-1], rotated:false, sourceRect:[7,2,60,72], sourceSize:[74,74]},
	{frame:[0,915,60,74], offset:[0,0], rotated:false, sourceRect:[7,0,60,74], sourceSize:[74,74]},
	{frame:[122,949,60,74], offset:[0,0], rotated:false, sourceRect:[7,0,60,74], sourceSize:[74,74]}
	];
	var cannon1 = this.translateImage(this.cannon, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
		
	var dict = [	
	{frame:[917,1156,60,76], offset:[0,0], rotated:false, sourceRect:[7,0,60,76], sourceSize:[74,76]},
	{frame:[490,1237,60,76], offset:[0,0], rotated:false, sourceRect:[7,0,60,76], sourceSize:[74,76]},
	{frame:[551,1283,60,74], offset:[0,-1], rotated:false, sourceRect:[7,2,60,74], sourceSize:[74,76]},
	{frame:[565,1206,60,76], offset:[0,0], rotated:false, sourceRect:[7,0,60,76], sourceSize:[74,76]},
	{frame:[917,1156,60,76], offset:[0,0], rotated:false, sourceRect:[7,0,60,76], sourceSize:[74,76]}
	];
	var cannon2 = this.translateImage(this.cannon, dict, dict[0].sourceSize[0], dict[0].sourceSize[1], true, props);
	
	var dict = [	
	{frame:[584,1129,62,76], offset:[-1,0], rotated:false, sourceRect:[5,0,62,76], sourceSize:[74,76]},
	{frame:[653,1127,62,76], offset:[-1,0], rotated:false, sourceRect:[5,0,62,76], sourceSize:[74,76]},
	{frame:[716,1140,62,72], offset:[-1,-2], rotated:false, sourceRect:[5,4,62,72], sourceSize:[74,76]},
	{frame:[726,1063,62,76], offset:[-1,0], rotated:false, sourceRect:[5,0,62,76], sourceSize:[74,76]},
	{frame:[584,1129,62,76], offset:[-1,0], rotated:false, sourceRect:[5,0,62,76], sourceSize:[74,76]}
	];
	
	for(var i = 0; i < dict.length; i++)
	{
		var d = dict[i], props = {regX:d.sourceSize[0]>>1, regY:d.sourceSize[1]>>1};
		var bullet = this.translateImage(this.getImage("cannon"), [d], d.sourceSize[0], d.sourceSize[1], true, props);
	}
	
	var dict = [
	{frame:[830,891,116,118], offset:[0,0], rotated:false, sourceRect:[0,0,116,118], sourceSize:[116,118]},
	{frame:[619,736,137,142], offset:[0,0], rotated:false, sourceRect:[0,0,137,142], sourceSize:[137,142]},
	{frame:[462,587,156,162], offset:[0,0], rotated:false, sourceRect:[0,0,156,162], sourceSize:[156,162]},
	{frame:[0,425,180,174], offset:[0,0], rotated:false, sourceRect:[0,0,180,174], sourceSize:[180,174]},
	{frame:[242,436,163,155], offset:[0,0], rotated:false, sourceRect:[0,0,163,155], sourceSize:[163,155]},
	{frame:[462,223,191,181], offset:[0,0], rotated:false, sourceRect:[0,0,191,181], sourceSize:[191,181]},
	{frame:[0,0,242,244], offset:[0,0], rotated:false, sourceRect:[0,0,242,244], sourceSize:[242,244]},
	{frame:[701,0,226,220], offset:[2,-1], rotated:false, sourceRect:[14,16,226,220], sourceSize:[250,250]}
	];
	for(var i = 0; i < dict.length; i++)
	{
		var d = dict[i], props = {regX:d.sourceSize[0]>>1, regY:d.sourceSize[1]>>1};
		var web = this.translateImage(this.getImage("cannon"), [d], d.sourceSize[0], d.sourceSize[1], true, props);
	}
};

R.translateImage = function(srcImage, dict, rectWidth, rectHeight, toImage, props)
{
	var cache = this.cacheImage(srcImage, dict, rectWidth, rectHeight, toImage);
	Q.merge(cache, props);
	return cache;
};

R.cacheImage = function(img, frames, rectWidth, rectHeight, toImage, rotation)
{
	var canvas = Q.createDOM("canvas");
	var context = canvas.getContext("2d");
	
	canvas.width = rectWidth;
	canvas.height = rectHeight*frames.length;
	
	var x = 0, y = 0, data = [];
	for(var i = 0; i < frames.length; i++)
	{
		var f = frames[i], frame = f.frame, offset = f.offset, rotated = f.rotated, srcRect = f.sourceRect || [0, 0];
		context.save();
		y = i * rectHeight;
		if(rotated)
		{
			var temp = frame[3];
			frame[3] = frame[2];
			frame[2] = temp;		
		}
		
		var tx = x + srcRect[0];
		var ty = rotated ? y + frame[2] + srcRect[1] : y + srcRect[1];
		context.translate(tx, ty);
		if(rotated) context.rotate(-90%360 * Math.PI / 180);		
		context.drawImage(img, frame[0], frame[1], frame[2], frame[3], 0, 0, frame[2], frame[3]);
		context.restore();
		
		var obj = {rect:[x, y, rectWidth, rectHeight]};
		data[i] = obj;
	}
	trace(JSON.stringify(data));
	
	if(toImage)
	{
		var cache = new Image();
		cache.src = canvas.toDataURL("image/png");
		cache.width = canvas.width;
		cache.height = canvas.height;
		document.body.appendChild(cache);	
		return {image:cache, frames:data};
	}	
	
	return {image:canvas, frames:data};
};

R.getImage = function(id)
{
	return this.images[id].image;
};

})();