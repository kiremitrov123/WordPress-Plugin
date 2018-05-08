<?php

get_header(); ?>

<script>

$( document ).ready(function() {
    $( "#btn_filter" ).click(function() {
    	var location_id = $('#all_locations').find(":selected").val();
    	if(location_id != "any"){
  			$.ajax({
                        type: "GET",
                        url: "/wp-project/wp-content/plugins/test-plugin/includes/api.php?id="+location_id,
                        processData: false,
                        contentType: "application/json",
                        success: function(r) {

                        	var posts = JSON.parse(r)
                        	$('.posts_cont').empty();

                        	$.each(posts, function(id) {
                        		$('.posts_cont').html(
                        			$('.posts_cont').html() + '<h1> ' + posts[id].post_title + '</h1>\n<img src="' + posts[id].thumbnail + '">\n' + '<p>' + posts[id].post_content + '</p> <hr>'
                        		)
                        	})
                        },    	

        });
  	}
	});
});

</script>

<div class="wrap">

	<div id="primary" class="content-area">
		<h1 style="text-align: center;color: blue ">Custom Page Template - Filter Custom Posts </h1> <hr>
		<main id="main" class="site-main" role="main" style="text-align: center;">
			<?php
			$args = array( 'post_type' => 'addons' );
			$terms = get_terms('location', array('hide_empty' => false,));

				global $wpdb;
				//Get the id of my Custom Page Template (single-post)
			$post_template_id = $wpdb->get_row('SELECT post_id FROM wp_postmeta WHERE meta_value="ads-template1.php" LIMIT 1');
				//Get the permalink of the Custom Page Template (single-post)
			$post_template_name = $wpdb->get_row('SELECT * FROM wp_posts WHERE ID='.$post_template_id->post_id.' AND post_status=\'publish\'  LIMIT 1');

			?>
			<form action="" method="GET" style="padding-bottom: 50px">

			<?php
			echo "<select id=\"all_locations\" name=\"id\">\n"; 
			echo "\t<option selected hidden value=\"any\">Choose a location</option>\n";

			foreach ($terms as $term) {
				
				if(isset($_GET["id"])){
					if($term->term_id==$_GET["id"]){
						echo  "\t<option selected value=\"", $term->term_id, "\">", $term->name,"</option>\n";
						continue;
					}
				} 
				echo "\t<option value=\"", $term->term_id, "\">", $term->name, "</option>\n";
			}
			echo "</select>\n";

			?>
			<input id="btn_filter" type="button" value="Filter">
			</form>
			<div class="posts_cont"></div>
		</main><!-- #main -->
	</div><!-- #primary -->
	<?php// get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();

