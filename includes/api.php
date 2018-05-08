<?php

include("../../../../wp-config.php");
$db_name = constant('DB_NAME');
$db_user = constant('DB_USER');
$db_pass = constant('DB_PASSWORD');
$db_host = constant('DB_HOST');
$db_ch_set = constant('DB_CHARSET');

$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=$db_ch_set", $db_user, $db_pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_GET["id"])){

	
		$query = $pdo->query('SELECT * FROM `wp_term_relationships`  WHERE `term_taxonomy_id` = '.$_GET["id"].';');

	$response = "[";
	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		$post_id = $row["object_id"];

		$statement = $pdo->prepare('SELECT * FROM `wp_posts` WHERE post_type=\'addons\' AND id = :post_id LIMIT 1;');
		$statement->execute(array(':post_id' => $post_id));
		$data = $statement->fetchAll();

		/* post_title, post_content */
		$post_title = $data[0]["post_title"];
		$post_content = $data[0]["post_content"];
		$response .= "{";
		$response .= '"post_title" : ' .json_encode($post_title) . ', ';
		$response .= '"post_content" : ' . json_encode($post_content) . ', ';

		$statement = $pdo->prepare('SELECT meta_value FROM `wp_postmeta` WHERE post_id = :p_id AND meta_key=\'_thumbnail_id\';');
		$statement->execute(array(':p_id' => $post_id));
		$data = $statement->fetchAll();

		/* thumbnail_id */
		$thumbnail_id = $data[0]["meta_value"];

		$statement = $pdo->prepare('SELECT guid FROM `wp_posts` WHERE id = :thumbnail_id AND post_type=\'attachment\';');
		$statement->execute(array(':thumbnail_id' => $thumbnail_id));
		$data = $statement->fetchAll();

		$thumbnail = $data[0]['guid'];
		$response .= '"thumbnail" : "' . htmlspecialchars($thumbnail) . '"';
		$response .= "},";

	}

	$response = substr($response, 0, strlen($response)-1);
	$response .= "]";
	echo $response;


}else{
	http_response_code(403);
}

?>