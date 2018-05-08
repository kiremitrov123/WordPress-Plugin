<?php

function post_scripts() {

  
  //wp_enqueue_style('yts-main-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');

  //wp_register_script('afp_script', plugins_url( '/js/ajax.js', __FILE__ ) );
  wp_register_script('jscript', "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js");
  wp_enqueue_script('afp_script');
  wp_enqueue_script('jscript');
 

}
add_action('wp_enqueue_scripts', 'post_scripts');