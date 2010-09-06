<?php
$error=invisiblewatermark_checkerrors();
if($error!=False){ echo $error; }
?>
<style>
	hr{ margin-top:20px; margin-bottom:20px; }
	form img { margin:auto 20px; }
	input { margin:5px;}
</style>
<h2>Upload Signature Image (PNG)</h2>
<form enctype="multipart/form-data" method="post">
	<input type="file" name="invisiblewatermark_upload">
	<input type="submit" value="Upload Signature">
	<?php
	if(file_exists(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/signature.png')){
		$H=300; $SIZE=getimagesize(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/signature.png');
		if($H>$SIZE[1]){ $H=$SIZE[1];}
		?><br /><img src="<?php echo WP_PLUGIN_URL.'/'.INVISIBLEWATERMARK_FOLDER.'/signature.png';?>" height="<?php echo $H;?>"><?php
	}
	?>
</form>

<hr />

<h2>Check Image Signature</h2>
<h4>Check an online image</h4>
<form method="post">
	URL: <input type="text" name="invisiblewatermark_url"> 
	<input type="submit" value="Download & Check">
</form>

<h4>Upload an image to check it</h4>
<form method="post" enctype="multipart/form-data">
 	<input type="file" name="invisiblewatermark_uploadandcheck">
 	<input type="submit" value="Upload & Check">
</form>
Check for attachs not signed yet.
