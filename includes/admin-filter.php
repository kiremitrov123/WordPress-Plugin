<?php


	class Taxonomy_Admin_Filter
	{

		function __construct($cpt = array()){
			$this->cpt = $cpt;
			// Adding a Taxonomy Filter to Admin List for a Custom Post Type
			add_action( 'restrict_manage_posts', array($this,'my_restrict_manage_posts' ));
			// if you do not want to remove default "by month filter", remove/comment this line
			add_filter( 'months_dropdown_results', '__return_empty_array' );
	 
			// include CSS/JS, in our case jQuery UI datepicker
			add_action( 'admin_enqueue_scripts', array( $this, 'jqueryui' ) );
	 
			// HTML of the filter
			add_action( 'restrict_manage_posts', array( $this, 'form' ) );
	 
			// the function that filters posts
			add_action( 'pre_get_posts', array( $this, 'filterquery' ) );
			
		}

		public function my_restrict_manage_posts() {
		    // only display these taxonomy filters on desired custom post_type listings

		    global $typenow;
		    $types = array_keys($this->cpt);
		    if (in_array($typenow, $types)) {
		        // create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
		        $filters = $this->cpt[$typenow];
		        foreach ($filters as $tax_slug) {

		            // retrieve the taxonomy object
		            $tax_obj = get_taxonomy($tax_slug);
		            $tax_name = $tax_obj->labels->name;
		            // output html for taxonomy dropdown filter
		            echo "<select name='".strtolower($tax_slug)."' id='".strtolower($tax_slug)."' class='postform'>";
		            echo "<option value=''>Show All $tax_name</option>";
		            $this->generate_taxonomy_options($tax_slug,0,0,(isset($_GET[strtolower($tax_slug)])? $_GET[strtolower($tax_slug)] : null));
		            echo "</select>";

		        }
		    }
		}

		public function generate_taxonomy_options($tax_slug, $parent = '', $level = 0,$selected = null) {

		    $args = array('show_empty' => 1);
		    if(!is_null($parent)) {
		        $args = array('parent' => $parent);
		    }
		    $terms = get_terms($tax_slug,$args);
		    $tab='';
		    for($i=0;$i<$level;$i++){
		        $tab.='--';
		    }
		    foreach ($terms as $term) {
		        // output each select option line, check against the last $_GET to show the current option selected
		        echo '<option value='. $term->slug, $selected == $term->slug ? ' selected="selected"' : '','>' .$tab. $term->name .' (' . $term->count .')</option>';
		        $this->generate_taxonomy_options($tax_slug, $term->term_id, $level+1,$selected);
		    }
		}

			function jqueryui(){
		wp_enqueue_style( 'jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
	}

			function form(){
 
		$from = ( isset( $_GET['mishaDateFrom'] ) && $_GET['mishaDateFrom'] ) ? $_GET['mishaDateFrom'] : '';
		$to = ( isset( $_GET['mishaDateTo'] ) && $_GET['mishaDateTo'] ) ? $_GET['mishaDateTo'] : '';
 
		echo '<style>
		input[name="mishaDateFrom"], input[name="mishaDateTo"]{
			line-height: 28px;
			height: 28px;
			margin: 0;
			width:125px;
		}
		</style>
 
		<input type="text" name="mishaDateFrom" placeholder="Date From" value="' . $from . '" />
		<input type="text" name="mishaDateTo" placeholder="Date To" value="' . $to . '" />
 
		<script>
		jQuery( function($) {
			var from = $(\'input[name="mishaDateFrom"]\'),
			    to = $(\'input[name="mishaDateTo"]\');
 
			$( \'input[name="mishaDateFrom"], input[name="mishaDateTo"]\' ).datepicker();
			// by default, the dates look like this "April 3, 2017" but you can use any strtotime()-acceptable date format
    			// to make it 2017-04-03, add this - datepicker({dateFormat : "yy-mm-dd"});
 
 
    			// the rest part of the script prevents from choosing incorrect date interval
    			from.on( \'change\', function() {
				to.datepicker( \'option\', \'minDate\', from.val() );
			});
 
			to.on( \'change\', function() {
				from.datepicker( \'option\', \'maxDate\', to.val() );
			});
 
		});
		</script>';
 
	}

		function filterquery( $admin_query ){
		global $pagenow;
 
		if (
			is_admin()
			&& $admin_query->is_main_query()
			// by default filter will be added to all post types, you can operate with $_GET['post_type'] to restrict it for some types
			&& in_array( $pagenow, array( 'edit.php', 'upload.php' ) )
			&& ( ! empty( $_GET['mishaDateFrom'] ) || ! empty( $_GET['mishaDateTo'] ) )
		) {
 
			$admin_query->set(
				'date_query', // I love date_query appeared in WordPress 3.7!
				array(
					'after' => $_GET['mishaDateFrom'], // any strtotime()-acceptable format!
					'before' => $_GET['mishaDateTo'],
					'inclusive' => true, // include the selected days as well
					'column'    => 'post_date' // 'post_modified', 'post_date_gmt', 'post_modified_gmt'
				)
			);
 
		}
 
		return $admin_query;
 
	}


 
	}//end class
