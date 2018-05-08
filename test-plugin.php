<?php
/**
 * @package TestPlugin
 */
/*
Plugin Name: Test Plugin
Plugin URI: http://test-plugin.com/plugin
Description: This is my custom plugin
Version: 1.0
Author: Kire Mitrov
Author URI: http://kiremitrov.com
License: GPLv2 or later
Text Domain: test-plugin
*/


if (!defined('ABSPATH')) {
    die;
}

require_once(plugin_dir_path(__FILE__).'/includes/get-custom-templates.php');
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );
require_once(plugin_dir_path(__FILE__).'/includes/scripts.php');

class TestPlugin
{
    
    function __construct()
    {
        add_action('init', array(
            $this,
            'custom_post_type'
        ));
        add_action('init', array(
            $this,
            'taxonomies'
        ));        
    }
    
    function activate()
    {
        $this->custom_post_type();
        $this->taxonomies();
        flush_rewrite_rules();
    }
    
    function deactivate()
    {
        flush_rewrite_rules();
    }
    
    // register custom post type to work with
    function custom_post_type()
    {
        
        // set up labels
        $labels = array(
            'name' 				 => 'Ads',
            'singular_name' 	 => 'Ads',
            'add_new'	    	 => 'Add New Ad',
            'add_new_item'  	 => 'Add New Ad',
            'edit_item' 		 => 'Edit Ad',
            'new_item' 			 => 'New Ad',
            'all_items' 		 => 'All Ads',
            'view_item' 		 => 'View Ads',
            'search_items'  	 => 'Search Ads',
            'not_found' 		 => 'No Ads Found',
            'not_found_in_trash' => 'No Ads found in Trash',
            'parent_item_colon'  => '',
            'menu_name' 		 => 'Custom Posts'
        );

        //register post type
        register_post_type('addons', array(
            'labels' 	  => $labels,
            'has_archive' => true,
            'public' 	  => true,
            'supports'    => array(
                'title',
                'editor',
                'excerpt',
                'custom-fields',
                'thumbnail',
                'page-attributes'
            ),
            //'taxonomies' => array( 'taxonomy_price', 'taxonomy_location' ),	
            'exclude_from_search' => false,
            'capability_type' 	  => 'post',
            'rewrite' 			  => array(
                'slug' 			  => 'ads'
            )
        ));
    }   
    
    function taxonomies()
    {
        $labels = array(
            'name' 				=> 'Prices',
            'singular_name' 	=> 'Price',
            'search_items' 		=> 'Search Price',
            'all_items' 		=> 'All Prices',
            'parent_item' 		=> 'Parent Type',
            'parent_item_colon' => 'Parent Type:',
            'edit_item' 		=> 'Edit Price',
            'update_item' 		=> 'Update Price',
            'add_new_item' 		=> 'Add New Price',
            'new_item_name' 	=> 'New Price Name',
            'menu_name' 		=> 'Prices'
        );
        
        $args = array(
            'hierarchical' 		=> true,
            'labels' 			=> $labels,
            'show_ui' 			=> true,
            'show_admin_column' => true,
            'query_var' 		=> true,
            'rewrite' 			=> array(
                'slug' 			=> 'prices'
            )
        );
        
        register_taxonomy('price', array(
            'addons'
        ), $args);
        
        
        $labels1 = array(
            'name' 				=> 'Locations',
            'singular_name' 	=> 'Location',
            'search_items' 		=> 'Search Location',
            'all_items' 		=> 'All Locations',
            'parent_item' 		=> 'Parent Type',
            'parent_item_colon' => 'Parent Type:',
            'edit_item' 		=> 'Edit Location',
            'update_item' 		=> 'Update Location',
            'add_new_item' 		=> 'Add New Location',
            'new_item_name' 	=> 'New Location Name',
            'menu_name' 		=> 'Locations'
        );
        
        $args1 = array(
            'hierarchical' 		=> true,
            'labels' 			=> $labels1,
            'show_ui' 			=> true,
            'show_admin_column' => true,
            'query_var' 		=> true,
            'rewrite' 			=> array(
                'slug' 			=> 'locations'
            )
        );
        register_taxonomy('location', array(
            'addons'
        ), $args1);
        
    }    
}

if (class_exists('TestPlugin')) {
    $testPlugin = new TestPlugin();
}

//activate
register_activation_hook(__FILE__, array(
    $testPlugin,
    'activate'
));
//deactivate
register_deactivation_hook(__FILE__, array(
    $testPlugin,
    'deactivate'
));


require_once(plugin_dir_path(__FILE__).'/includes/admin-filter.php');

new Taxonomy_Admin_Filter(array(
     'addons' => array('location', 'price'),
));