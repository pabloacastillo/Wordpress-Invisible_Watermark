<?php
/*
Plugin Name: Wordpress Invisible Watermark
Plugin URI: http://pablo.lnxsoluciones.com/
Description: This plugin alow you to give your attached images an invisible watermark using ImageMagik
Version: 1.0
Author: Pablo Castillo
Author URI: http://pablo.lnxsoluciones.com/
*/

define('INVISIBLEWATERMARK_VERSION', '1.0');
define('INVISIBLEWATERMARK_FOLDER', 'Wordpress-Invisible_Watermark');


function invisiblewatermark_init() {
	add_action('admin_menu', 'invisiblewatermark_config_page');
}
add_action('init', 'invisiblewatermark_init'); // LET'S START THE PLUGIN

function invisiblewatermark_checkerrors(){

	$error=False;

	if(!extension_loaded('imagick')){
		$error= '<h4>You need the Imagik Module to use this plugin.</h4>';
	}

	if( (!is_dir(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER)) || (!is_dir(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/tmp')) ){
		$error.= '<h4>Folder doesnt exist. Creating...</h4>';
		@mkdir(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER);
		@mkdir(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/tmp');
	}
	
	if( (!is_writable(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER)) || (!is_writable(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/tmp')) ){
		$error.= '<h4>Folder isnt writable. Changing permisions...</h4>';
		@chmod(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER, 0755);
		@chmod(WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/tmp', 0755);
	}
	
	return $error;
}

function invisiblewatermark_config_page() { // ADD A NEW ITEM TO THE PLUGINS LIST ON THE LEFT. FROM HERE WE WILL ACCCESS THE CONFIGURATION AND OPTIONS
	if ( function_exists('add_submenu_page') ){
		add_submenu_page('plugins.php', __('Invisible Watermark'), __('Invisible Watermark'), 'manage_options', 'invisiblewatermark-config', 'invisiblewatermark_conf');
	}
}

function invisiblewatermark_conf() {

	if($_FILES['invisiblewatermark_upload']['type']=='image/png'){
		move_uploaded_file($_FILES['invisiblewatermark_upload']['tmp_name'], WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/signature.png');
	}
	
	if(strstr($_FILES['invisiblewatermark_uploadandcheck']['type'],'image')){
		move_uploaded_file($_FILES['invisiblewatermark_upload']['tmp_name'], WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/tmp/'.(date('YmdHi')).$_FILES['invisiblewatermark_upload']['name']);
	}
		

	include_once('invisiblewatermark_wpconfig.php');

}

function invisiblewatermark_add($attach_ID){ // AGREGAR MARCA DE AGUA
	// http://codex.wordpress.org/Plugin_API/Action_Reference#Post.2C_Page.2C_Attachment.2C_and_Category_Actions
	// http://codex.wordpress.org/Plugin_API
	$signature=WP_PLUGIN_DIR.'/'.INVISIBLEWATERMARK_FOLDER.'/signature.png';
	$img=get_attached_file($attach_ID);
	// CONVERTIR IMAGEN A PNG AQUI
	$png=substr($img,-3).'.png';
	$cmd="convert $img $png";
	exec($cmd);
	$cmd="mv $png $img";
	
	$cmd = "$signature $img -stegano +1+1 ";
	exec("composite $cmd $img"); // EXECUTE THE STEG OF THE IMAGE AND SAVES IT AS A NEW IMAGE
}


add_action('add_attachment','invisiblewatermark_add');
?>
