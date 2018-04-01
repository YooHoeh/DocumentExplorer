/**
 * 
 */

$(document).ready(function() {
	//按下鼠标
	$(".type").mousedown(function(e) {
		var key = e.which;
		if(key == 3) {
			//获取右击坐标		
			var x = e.clientX;
			var y = e.clientY;

			$(".contexmenu").slideDown(200).css({
				left: x,
				top: y
			});
		}
		if(key == 1) {
			var addr = $(".address").attr("value");
			var path = addr + "/"+$(this).attr("id");
//			document.getElementsByClassName("fileDetail")[0].innerHTML=path;
			$("fileDetail").load("detail.php");
		}
	});

	$(".dir").dblclick(function() {
		var addr = $(".address").attr("value");
		var path = addr + "/"+$(this).attr("id");
		ajax({
			method: 'get',
			url: 'index.php',
			data: {
				'fileName': name,
				'path': addr
			},
			success: function(text) {
				self.location = 'index.php?path=' + path;
			},
			async: true
		});

	});
	$(".file").dblclick(function() {
		var addr = $(".address").attr("value");
		var path = addr + "/"+$(this).text;
		alert(this.getAttribute('name')+"类型文件");
//		ajax({
//			method: 'get',
//			url: 'index.php',
//			data: {
//				'fileName': name,
//				'path': addr
//			},
//			success: function(text) {
//				self.location = 'index.php?path=' + path;
//			},
//			async: true
//		});

	});

});

//屏蔽浏览器右键
document.oncontextmenu = function() {
	return false;
}

//点击任意位置后隐藏右键菜单
$(document).click(function() {
	$(".contexmenu").slideUp(200);
});

function createXHR() {
	if(typeof XMLHttpRequest != 'undefined') {
		return new XMLHttpRequest();
	} else if(typeof ActiveXObject != 'undefined') {
		var version = [
			'MSXML2.XMLHttp.6.0',
			'MSXML2.XMLHttp.3.0',
			'MSXML2.XMLHttp'
		];
		for(var i = 0; version.length; i++) {
			try {
				return new ActiveXObject(version[i]);
			} catch(e) {
				//跳过
			}
		}
	} else {
		throw new Error('您的系统或浏览器不支持XHR对象！');
	}
}

//名值对转换为字符串
function params(data) {
	var arr = [];
	for(var i in data) {
		arr.push(encodeURIComponent(i) + '=' + encodeURIComponent(data[i]));
	}
	return arr.join('&');
}

//封装ajax
function ajax(obj) {
	var xhr = createXHR();
	obj.url = obj.url + '?rand=' + Math.random();
	obj.data = params(obj.data);
	if(obj.method === 'get') obj.url += obj.url.indexOf('?') == -1 ? '?' + obj.data : '&' + obj.data;
	if(obj.async === true) {
		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4) {
				callback();
			}
		};
	}
	xhr.open(obj.method, obj.url, obj.async);
	if(obj.method === 'post') {
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send(obj.data);
	} else {
		xhr.send(null);
	}
	if(obj.async === false) {
		callback();
	}

	function callback() {
		if(xhr.status == 200) {
			obj.success(xhr.responseText); //回调传递参数
		} else {
			alert('获取数据错误！错误代号：' + xhr.status + '，错误信息：' + xhr.statusText);
		}
	}
}