var main = {
	
	init : function(){
		//音乐播放
		var music = document.getElementById('globalAudioPlayer');
		music.play();
	},

	event: function(){
		/*提交*/
		$("#submit").click(function(){
			var new_name = $("input[name='new_name']").val();
			if(new_name == null || new_name == "null" || new_name ==""){
				alert("姓名不能为空");
				return ;
			}
			var data = {
				"new_name" : new_name,
				"task_id" : $("html").attr("data-task-id"),
				"share_uid" : $("html").attr("data-share-uid")
			};
			var data_editname_api_action = $("html").attr("data-editname-api-action");
			var forward =$.post(data_editname_api_action, data,function(result){
				var json = eval('('+result+')');
				if(json.status == 200){
					location.href = json.data;
				}
			});
		});

		/*音乐*/
		$("#globalAudio").on("click",function(){
			var music = document.getElementById('globalAudioPlayer');
			var music_img = document.getElementById('globalAudio');
			var status = music.paused;
			if (status) {
				music_img.className = "ga-active";
				music.play();
			} else {
				music_img.className = "";
				music.pause();
			}
		});
	},
	
	funs : {
		
	},
};
$(function(){
	main.init();
	main.event();
});