<?php



// Check request came from valid source here

$results = "Wrong Referer";

if ( strpos  ( $_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'] ) > 0 ) {

	if (!function_exists('add_action')) {
		require_once("../../../wp-config.php");
	}
	
	if(function_exists('load_plugin_textdomain'))
		load_plugin_textdomain('blogintroduction-wordpress-plugin', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)), dirname(plugin_basename(__FILE__)));

	$options = (array) get_option('plugin_blogintroduction');
	
	// Default-values, only be used, if running in totally unconfigured mode. Should never be happen, I think
	if ($options['title']=="") {
		$options['title'] = __('Blogintroduction', 'blogintroduction-wordpress-plugin');
	}
	if ($options['category']=="") {
		$options['category'] = "56";
	}
	if ($options['width']=="") {
		$options['width'] = "170";
	}
	if ($options['height']=="") {
			$options['use4to3ratio'] = "1";
	}
	$height = 152;
	if ($options['use4to3ratio'] == "1") {
			$height = $options['width'] * 3/4;
	} else {
			$height = $options['height'];
	}
	if ($options['imagesource']=="") {
			$options['imagesource'] = "websnapr";
	}
	if ($options['target']=="") {
		$options['target'] = "_top";
	}
	$cat_id = $options['category'];
	if ( 'all' == $cat_id )
		$cat_id = '';
	
	$hide_invisible = 1;
	
	if ($options['showinvisible']!="") {
		$hide_invisible = 0;
	}
	
	$args = array('category' => $cat_id, 'hide_invisible' => $hide_invisible, 'orderby' => 'id', 'hide_empty' => 1);
	$links = get_bookmarks( $args );
	
	// find the link to show by random
	srand(microtime()*1000000);
	$linkelement = rand(0,sizeof($links)-1);
	$link = $links[$linkelement];
	$link = sanitize_bookmark($link);
	$link->link_name = attribute_escape($link->link_name);
	$enc_url = urlencode($link->link_url);
	
	// building the link
	$src = "http://images.websnapr.com/?size=s";
	if ($options['websnaprapikey']!="") {
			$src .= "&amp;key=".$options['websnaprapikey'];
    }
	$src .= '&amp;url='.$enc_url;
	
	if ($options['imagesource']=="local") {
			if ( $link->link_image != "" ) {
					$src = $link->link_image;
			}
	}
	$results = '<a class="wp-blogintroduction" href="'.$link->link_url.'" title="'.$link->link_name.'" target="'.$options['target'].'"><img class="wp-blogintroduction" src="'.$src.'" alt="'.$link->link_name.'" width="'.$options['width'].'" height="'.$height.'"/></a>';
	
	if ($options['showdescription']!="" && $link->link_description != "") {
		$results .='<br/><b class="wp-blogintroduction" >'. __('Description', 'blogintroduction-wordpress-plugin').':</b><p class="wp-blogintroduction" >'.$link->link_description.'</p>';
	}

}

if( $error ) {
   die( "alert('$error')" );
}

// quick & dirty way to set unset post var to default value
if ($link_div_id = '') {
	$link_div_id = "wp-blogintroduction-link";
}

// Compose JavaScript for return

die( "document.getElementById('wp-blogintroduction-link').innerHTML = '$results'" );

?>