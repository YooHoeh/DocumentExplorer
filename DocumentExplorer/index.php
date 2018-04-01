<?php
/*
 *
 * ================================================
 * Author: ZhangJi
 * Email: 281056769@qq.com
 * Date: 2017年10月24日
 */
header('Content-type: text/html;charset=utf-8');
require_once 'include/dir.func.php';
require_once 'include/file.func.php';
require_once 'include/common.func.php';
if (isset($_GET['path'])) {
	$path = $_GET['path'];
}else {
	$path ="d:";
}
$info=readDirectory($path);
$act=$_REQUEST['act'];
$filename=$_REQUEST['filename'];
$dirname=$_REQUEST['dirname'];
$info=readDirectory($path);
if(!$info){
	echo "<script>alert('没有文件或目录！！！');history.back();</script>";
}
$redirect="index.php?path={$path}";
if($act=="showContent"){
	//查看文件内容
		$content=file_get_contents($filename);
		//echo "<textarea readonly='readonly' cols='100' rows='10'>{$content}</textarea>";
		//高亮显示PHP代码
		//高亮显示字符串中的PHP代码
		if(strlen($content)){
		$newContent=highlight_string($content,true);
		//高亮显示文件中的PHP代码
		$str=<<<EOF
		<table width='100%' bgcolor='pink' cellpadding='5' cellspacing="0" >
			<tr>
				<td>{$newContent}</td>
			</tr>
		</table>
EOF;
		echo $str;
	}else{
		alertMes("文件没有内容，请编辑再查看！",$redirect);
	}
}elseif($act=="editContent"){
	//修改文件内容
	$content=file_get_contents($filename);
	$str=<<<EOF
	<form action='index.php?act=doEdit' method='post'> 
		<textarea name='content' cols='190' rows='10'>{$content}</textarea><br/>
		<input type='hidden' name='filename' value='{$filename}'/>
		<input type="hidden" name="path" value="{$path}" />
		<input type="submit" value="修改文件内容"/>
	</form>
EOF;
	echo $str;
}elseif($act=="doEdit"){
	//修改文件内容的操作
	$content=$_REQUEST['content'];
	//echo $content;
	if(file_put_contents($filename,$content)){
		$mes="文件修改成功";
	}else{
		$mes="文件修改失败";
	}
	alertMes($mes,$redirect);
}elseif($act=="renameFile"){
	//完成重命名
	$str=<<<EOF
	<form action="index.php?act=doRename" method="post"> 
	请填写新文件名:<input type="text" name="newname" placeholder="重命名"/>
	<input type='hidden' name='filename' value='{$filename}' />
	<input type="submit" value="重命名"/>
	</form>
EOF;
echo $str;
}elseif($act=="doRename"){
	//实现重命名的操做
	$newname=$_REQUEST['newname'];
	$mes=renameFile($filename,$newname);
	alertMes($mes,$redirect);
}elseif($act=="delFile"){
	$mes=delFile($filename);
	alertMes($mes,$redirect);
}elseif($act=="downFile"){
	//完成下载的操作
	$mes=downFile($filename);
}elseif($act=="创建文件夹"){
	$mes=createFolder($path."/".$dirname);
	alertMes($mes,$redirect);
}elseif($act=="renameFolder"){
	$str=<<<EOF
			<form action="index.php?act=doRenameFolder" method="post"> 
	请填写新文件夹名称:<input type="text" name="newname" placeholder="重命名"/>
	<input type="hidden" name="path" value="{$path}" />
	<input type='hidden' name='dirname' value='{$dirname}' />
	<input type="submit" value="重命名"/>
	</form>
EOF;
echo $str;
}elseif($act=="doRenameFolder"){
	$newname=$_REQUEST['newname'];
	//echo $newname,"-",$dirname,"-",$path;
	$mes=renameFolder($dirname,$path."/".$newname);
	alertMes($mes,$redirect);
}elseif($act=="copyFolder"){
		$str=<<<EOF
	<form action="index.php?act=doCopyFolder" method="post"> 
	将文件夹复制到：<input type="text" name="dstname" placeholder="将文件夹复制到"/>
	<input type="hidden" name="path" value="{$path}" />
	<input type='hidden' name='dirname' value='{$dirname}' />
	<input type="submit" value="复制文件夹"/>
	</form>
EOF;
echo $str;
}elseif($act=="doCopyFolder"){
	$dstname=$_REQUEST['dstname'];
	$mes=copyFolder($dirname,$path."/".$dstname."/".basename($dirname));
	alertMes($mes,$redirect);
}elseif($act=="cutFolder"){
			$str=<<<EOF
	<form action="index.php?act=doCutFolder" method="post"> 
	将文件夹剪切到：<input type="text" name="dstname" placeholder="将文件剪切到"/>
	<input type="hidden" name="path" value="{$path}" />
	<input type='hidden' name='dirname' value='{$dirname}' />
	<input type="submit" value="剪切文件夹"/>
	</form>
EOF;
echo $str;
}elseif($act=="doCutFolder"){
	//echo "文件夹被剪切了";
	$dstname=$_REQUEST['dstname'];
	$mes=cutFolder($dirname,$path."/".$dstname);
	alertMes($mes,$redirect);
}elseif($act=="delFolder"){
	//完成删除文件夹的操作
	//echo "文件夹被删除了";
	$mes=delFolder($dirname);
	alertMes($mes,$redirect);
}elseif($act=="copyFile"){
				$str=<<<EOF
	<form action="index.php?act=doCopyFile" method="post"> 
	将文件复制到：<input type="text" name="dstname" placeholder="将文件复制到"/>
	<input type="hidden" name="path" value="{$path}" />
	<input type='hidden' name='filename' value='{$filename}' />
	<input type="submit" value="复制文件"/>
	</form>
EOF;
echo $str;
}elseif($act=="doCopyFile"){
	$dstname=$_REQUEST['dstname'];
	$mes=copyFile($filename,$path."/".$dstname);
	alertMes($mes,$redirect);
}elseif($act=="cutFile"){
				$str=<<<EOF
	<form action="index.php?act=doCutFile" method="post"> 
	将文件剪切到：<input type="text" name="dstname" placeholder="将文件剪切到"/>
	<input type="hidden" name="path" value="{$path}" />
	<input type='hidden' name='filename' value='{$filename}' />
	<input type="submit" value="剪切文件"/>
	</form>
EOF;
echo $str;
}elseif($act=="doCutFile"){
	$dstname=$_REQUEST['dstname'];
	$mes=cutFile($filename,$path."/".$dstname);
	alertMes($mes,$redirect);
}
//elseif($act=="上传文件"){
//	//print_r($_FILES);
//	$fileInfo=$_FILES['myFile'];
//	$mes=uploadFile($fileInfo,$path);
//	alertMes($mes, $redirect);
//}
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html" charset="utf-8" />
<link rel="stylesheet" type="text/css" href="style/index.css">
<script src="jquery-ui/js/jquery-1.10.2.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="js/index.js"></script>
<title>DocumentManagerSystem</title>
</head>
<body>
<!--右键菜单 -->
<div class="contexmenu">
	<ul>
		<li><a href="index.php?act=renameFile&path=<?php echo $path;?>&filename=<?php echo $p;?>">重命名</a></li>
		<li><a href="index.php?act=cutFile&path=<?php echo $path;?>&filename=<?php echo $p;?>">剪切</a></li>
		<li><a href="index.php?act=copyFile&path=<?php echo $path;?>&filename=<?php echo $p;?>"><a>复制</a></li>
		<li><a href="#"  onclick="delFile('<?php echo $p;?>','<?php echo $path;?>')">删除</a></li>
		<li><a href="index.php?act=downFile&path=<?php echo $path;?>&filename=<?php echo $p;?>">下载</a></li>
	</ul>
</div>


<h1>在线文档管理系统</h1>
<div class="mainwindow">
<!--导航栏 -->
<div class="navBar">
	<div class="prev Arrow" onclick="history.back()"></div>
	<div class="next Arrow"onclick="history.go(1)"></div>
	<a class="home" href="index.php" ><img title="主页" src="images/home.jpg"></a>
	<form class="localBar" method="get" target="index.php">
		<input type="text" class="address" name="path" value="<?php echo $path;?>"/> 
		<input type="submit" class="enter" value="→"/> 
		<div class="refresh"><img title="刷新" src="images/refresh.jpg"/></div>
		<input type="text" class="serach" />
		<div class="serachfile"><img title="搜索" src="images/serach.jpg"></div>
	</form>
</div>
<!-- 文件窗口 -->
<div class="documentView">
<?php 
if ($info['dir']) {
	$i = 1;
	foreach ($info['dir'] as $val) {
?>
	<div class="dir type" id="<?php echo $val?>"><?php echo $val?></div>	
<?php 
$i++;
	}
}
if ($info['file']) {
	$i = 1;
	foreach ($info['file'] as $val) {
?>
	<div class="file type" id="<?php echo $val?>" name="<?php echo getExt($path.$val)?>"><?php echo $val?></div>	
<?php 
$i++;
	}
		
	}
?>
</div>
<!-- 详细信息 -->
	
<div class="fileDetail">
<?php 
	require 'detail.php';	
?>
</div>
</div>
</body>
</html>