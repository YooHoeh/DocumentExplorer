<?php
/*
*
* ================================================
* Author: ZhangJi
* Email: 281056769@qq.com
* Date: 2017年10月28日
*/

if (isset($_GET['path'])) {
    $file = $_GET['path']; ?>
		<dl>
			<dt>	文件名：</dt>
			<dd><?php echo $path; ?></dd>
			<dt>	文件大小：</dt>
			<dd> <?php echo transByte(filesize($file)); ?></dd>
			<dt>	可读：</dt>
			<dd> <?php $src = is_readable($file) ? 'correct.png' : 'error.png'; ?><img class="small" src="images/<?php echo $src; ?>" alt=""/></dd>
			<dt>	可写：</dt>
			<dd> <?php $src = is_writable($file) ? 'correct.png' : 'error.png'; ?><img class="small" src="images/<?php echo $src; ?>" alt=""/></dd>
			<dt>	可执行：</dt>
			<dd> <?php $src = is_executable($file) ? 'correct.png' : 'error.png'; ?><img class="small" src="images/<?php echo $src; ?>" alt=""/></dd>
			<dt>	创建时间：</dt>
			<dd> <?php echo date('Y-m-d H:i:s', filectime($file)); ?></dd>
			<dt>	修改时间：</dt>
			<dd> <?php echo date('Y-m-d H:i:s', filemtime($file)); ?></dd>
			<dt>	访问时间：</dt>
			<dd> <?php echo date('Y-m-d H:i:s', fileatime($file)); ?></dd>
		</dl>
<?php
} else {
        ?>
		<dl>
			
			<dt>当前目录下</dt>
			<dd>文件夹数：<?php echo count($info['dir']); ?></dd>
			<dd>文件数：<?php echo count($info['file']); ?></dd>
		</dl>
<?php
    }
?>
