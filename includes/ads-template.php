<?php

get_header(); 

?>

<div class="wrap">

	<div id="primary" class="content-area">
		<h1 style="text-align: center;color: blue ">Custom Page Template - Custom Posts Ordered By Date </h1> <hr>
		<main id="main" class="site-main" role="main" style="text-align: center;">

						<?php
						global $wpdb;
						//Get the id of my Custom Page Template (single-post)
						$post_template_id = $wpdb->get_row('SELECT post_id FROM wp_postmeta WHERE meta_value="ads-template1.php" LIMIT 1');
						//Get the permalink of the Custom Page Template (single-post)
						$post_template_name = $wpdb->get_row('SELECT * FROM wp_posts WHERE ID='.$post_template_id->post_id.' AND post_status=\'publish\'  LIMIT 1')
						?>

			<?php
			//Select all custom posts and order them by date
				global $wpdb;
				$posts_descending = $wpdb->get_results('SELECT * FROM wp_posts where post_type=\'addons\' AND post_status=\'publish\' order by post_date DESC;');

			//Display all custom posts on custom page template
			foreach ($posts_descending as $post_descending) {
					echo "<h1><a href=\"", $post_template_name->post_name, "/?id=",$post_descending->ID, "\">", get_post_field('post_title', $post_descending->ID), "</a></h1><br/>";
					echo get_the_post_thumbnail($post_descending->ID), "<br/>";
					//echo get_post_field('post_content', $post_descending->ID), "<br/><hr>";
					echo "<button type=\"button\" onclick=\"document.location = '", $post_template_name->post_name, "/?id=",$post_descending->ID,"';\">Details</button>", "<hr/>";
			}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php// get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();

