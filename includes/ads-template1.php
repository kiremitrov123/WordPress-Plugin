<?php

get_header();

$available_locations = array();
$price = 0;

?>

<div class="wrap">
	<h1 style="text-align: center;color: blue ">Custom Page Template - Taxonomies On Single Custom Post </h1> <hr>
	<div id="primary" class="content-area" style="text-align: center;">
		<main id="main" class="site-main" role="main">
			<?php
			global $wpdb;

			$posts = $wpdb->get_row('SELECT * FROM wp_posts WHERE post_type=\'addons\' AND ID='.$_GET['id'].' AND  post_status=\'publish\' LIMIT 1; ');

				$taxonomy_id = $wpdb->get_results('SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id='.$_GET['id'].';');

				$total_taxonomy = count($taxonomy_id);

				for ($i=0; $i < $total_taxonomy; $i++) { 
		
					$taxonomy_type = $wpdb->get_row('SELECT taxonomy FROM wp_term_taxonomy WHERE term_taxonomy_id='.$taxonomy_id[$i]->term_taxonomy_id.';');


					if($taxonomy_type->taxonomy=="price"){
						$taxonomy_name = $wpdb->get_row('SELECT name FROM wp_terms WHERE term_id='.$taxonomy_id[$i]->term_taxonomy_id.';');
						$price = $taxonomy_name->name;
					}

					if($taxonomy_type->taxonomy=="location"){
						$taxonomy_name = $wpdb->get_row('SELECT name FROM wp_terms WHERE term_id='.$taxonomy_id[$i]->term_taxonomy_id.';');
						array_push($available_locations, $taxonomy_name->name);

					}
				}

					//Display taxonomies of every single post in my custom template page (single-post)
					echo "<h5 style='float:left'> Post date: ", date('m/d/Y H:i:s', strtotime(get_post_field('post_date', $posts->ID))), "</h5> <br/>";
					echo "<h1>", get_post_field('post_title', $posts->ID), "</h1> <br/>";
					echo get_the_post_thumbnail($posts->ID), "<br/>";
					 
					echo get_post_field('post_content', $posts->ID);
					echo "<br/>";

					echo "<h5>", "Price: $", $price, "<br/>";
					$total_locations = count($available_locations);

					echo "Locations: ";
					for ($i=0; $i < $total_locations-1; $i++) { 
						echo $available_locations[$i], ", ";
					}
					echo $available_locations[$total_locations-1], "</h5>";
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php// get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();

