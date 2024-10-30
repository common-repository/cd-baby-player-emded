<?php
/*
Plugin Name: CD Baby Music Player Shortcode
Plugin URI: http://wordpress.org/extend/plugins/cdbaby-shortcode/
Description: Embeds the CD Baby HTML5 Player into Wordpress using the cdbaby shortcode. Example: [cdbaby]DA770544-7C91-45D9-8372-104BCD7FB47F[/cdbaby]
Version: 1.0.3
Author: CD Baby
Author URI: http://cdbaby.com
License: GPLv2

*/


/* Register shortcode*/
add_shortcode('cdbaby', 'cdbaby_shortcode');

/**
* CD Baby (cdbaby) shortcode.
*
* This implements the functionality of the playlist shortcode for displaying
* a collection of WordPress audio or video files in a post.

*
* @param {string|array} $attr CD Baby shortcode attributes.
* @param string $content Content (typically the playerId) passed within [cdbaby] tags.
* @return string Music Player HTML. 
*/
function cdbaby_shortcode($atts, $content = null) {

  // Custom shortcode options
  $shortcode_options = is_array($atts) ? $atts : array();

  if ($content != NULL){
      $shortcode_options = array_merge(array('playerid' => trim($content)), $shortcode_options);
  }

  // Default options
  $default_options = array(
    'width'  => '100%',
    'height' => '100px',
    'type' => 'mini', // mini|album|square|full
    'theme' => 'light', // light|dark
    'transparent' => 'false' // true|false
  );

  // shortcode options
  $options = array_merge( $default_options, $shortcode_options);


  // The "playerId" option is required
  if (!isset($options['playerid'])) {
    return '<p>CD Baby Player ID not found</p>';
  } else {
    $options['playerId'] = trim($options['playerid']);
  }
  return create_player($options);
}

/**
* Create Player Emded code.
*
* Output Player HTML 
*
* @param {array} $options Player options
* @return string Music Player HTML. 
*/
function create_player($options) {
  
  
  // Base URL without last slash
  $base = '//widget.cdbaby.com';
  // Build URL
  $url = sprintf('%s/%s/%s/%s/%s', $base, $options['playerId'], $options['type'], $options['theme'], $options['transparent']);
  // Set default width if not defined
  $width = isset($options['width']) && $options['width'] !== 0 ? $options['width'] : '100%';
  // Set default height if not defined
  $height = isset($options['height']) && $options['height'] !== 0  ? $options['height'] : '100px';
   
   switch ($options['type']) {
    case 'mini':
        return sprintf('<iframe name="mini" style="border:0px;width:%s;height:%s;" src="%s"></iframe>', $width, $height, $url);
    case 'album':
        return sprintf('<div style="max-width:600px;max-height:760px;"><div style="position: relative;height: 0;overflow: hidden;padding-bottom:calc(100%% + 200px);"><iframe name="album" style="position:absolute;top:0px;left:0px;width:100%%;height:100%%;border:0px;" src="%s"></iframe></div></div>', $url);
    case 'square':
        return sprintf('<div style="max-width:600px;max-height:625px;"><div style="position: relative;height: 0;overflow: hidden;padding-bottom:calc(100%% + 30px);"><iframe name="square" style="position:absolute;top:0px;left:0px;width:100%%;height:100%%;border:0px;" src="%s"></iframe></div></div>', $url);
    case 'full':
        return sprintf('<iframe name="full" style="width:100%%;height:520px;border:0px;" src="%s"/>', $url);
   }
}

function is_valid_guid($id){
    return preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', trim($id)) > 0;
}
?>