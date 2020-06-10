<?php
/**
 * The template for displaying the homepage.
 *
 * Template Name: Bitsector
 *
 * @package Rookie
 */


$steamid = null;
$nick = null;
$avatar = null;
$onlineUsers = [];

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

foreach($aUsers as $aUser) {
	$aUid = $aUser->ID;
	$userMeta = get_user_meta($aUid);
	$lastOnline = date( 'd/m/y H:i:s', gearside_user_last_online($aUid));
	if(isset($userMeta["steam_steamid"]) and gearside_is_user_online($aUid)) {
		array_push($onlineUsers, $userMeta);
	}
}


get_header(); ?>


<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

<!-- <pre>
<?php var_dump($onlineUsers); ?>
</pre> -->

	<div id="primary" class="content-area content-area-<?php echo rookie_get_sidebar_setting(); ?>-sidebar">
		<main id="main" class="site-main" role="main">
            <h1>Velkommen <?php echo $nick ?></h1>
			<?php if($steamid) { ?>
			<img src="<?php echo $avatar ?>" alt="">
			<p>Din steamID er: <?php echo convertSteamID($steamid) ?></p>
			<?php } ?>
			<h2>andre brukere som er online</h2>


		<div id="app">
			{{ message }}	
			<ul v-for="user,i in users">
				<li v-if="">{{user.nickname}}</li>
			</ul>
		</div>
		</main><!-- #main -->
	</div><!-- #primary -->

	<script>
		var app = new Vue({
			el: '#app',
			data: {
				message: 'Hello Vue!',
				users: <?php echo json_encode($onlineUsers); ?>

 			}
		})
	</script>


<?php get_sidebar(); ?>
<?php get_footer(); ?>