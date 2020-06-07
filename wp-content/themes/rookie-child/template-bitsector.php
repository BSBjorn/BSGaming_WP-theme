<?php
/**
 * The template for displaying the homepage.
 *
 * Template Name: Bitsector
 *
 * @package Rookie
 */
wp_enqueue_script("jquery");

$steamid = null;
$nick = null;
$avatar = null;

$user = wp_get_current_user();
	if ( $user->exists() ) { // is_user_logged_in() is a wrapper for this line
		$userdata = get_user_meta( $user->data->ID );
		if ( isset($userdata["steam_steamid"]) ) {
			$steamid = $userdata["steam_steamid"][0];
			$nick = $userdata["nickname"][0];
			$avatar = $userdata["steam_wp_avatar"][0];
		}
	}

	$aUsers = get_users([
		'meta_key' => 'session_tokens',
		'meta_compare' => 'EXISTS'
	]);

get_header(); ?>

	<div id="primary" class="content-area content-area-<?php echo rookie_get_sidebar_setting(); ?>-sidebar">
		<main id="main" class="site-main" role="main">

            <h1>Velkommen <?php echo $nick ?></h1>
			<?php if($steamid) { ?>
			<img src="<?php echo $avatar ?>" alt="">
			<p>Din steamID er: <?php echo $steamid ?></p>
			
			<h2>andre brukere som er online</h2>
			<ul>
			<?php foreach($aUsers as $aUser) {
				$userMeta = get_user_meta($aUser->ID);
				if(isset($userMeta["steam_steamid"])) {
					echo '<li>' . $userMeta["nickname"][0] . '</li>';
				}
			} ?>

		</ul>

		<?php } ?>
		</main><!-- #main -->
	</div><!-- #primary -->
<script>

</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>