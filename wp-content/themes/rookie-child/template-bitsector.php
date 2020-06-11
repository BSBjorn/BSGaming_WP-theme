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
			<h3>Team 1</h3>
			<ul v-for="player in team1">
				<li>{{player.nickname}}</li>
			</ul>
			
			<h3>Team 2</h3>
			<ul v-for="player in team2">
				<li>{{player.nickname}}</li>
			</ul>

			<button @click="shuffle(players)">Shuffle</button>
		</div>
		</main><!-- #main -->
	</div><!-- #primary -->

	<script>
		var app = new Vue({
			el: '#app',
			data: {
				message: 'Hello Vue!',
				users: <?php echo json_encode($onlineUsers); ?>,
				players: [
					{nickname: "B-King", steamid: 123456, number: 1},
					{nickname: "chessome", steamid: 123454, number: 2},
					{nickname: "Turtlefly_", steamid: 123426, number: 3},
					{nickname: "Niso", steamid: 644456, number: 4},
					{nickname: "Mayhem", steamid: 773532, number: 5},
					{nickname: "Regn", steamid: 073532, number: 6},
					{nickname: "Nilsen", steamid: 573532, number: 7},
					{nickname: "Molle2k", steamid: 732532, number: 8},
					{nickname: "TheBait", steamid: 986441, number: 9},
					{nickname: "DMK", steamid: 173532, number: 10},
					{nickname: "Halio", steamid: 986441, number: 11},
				],
				team1: [],
				team2: []
			 },
			mounted: function() {
				this.placeInTeams();
			},
			methods: {
				placeInTeams: function() {
					this.team1 = [];
					this.team2 = [];
					this.players.forEach((value, index) => {
					if (index <= 4 ) {
						this.team1.push(value);
					} else if ( index > 4 && index <= 9 ) {
						this.team2.push(value);
					}
				});
				},

				shuffle: function(array) {
					var currentIndex = array.length, temporaryValue, randomIndex;

					// While there remain elements to shuffle...
					while (0 !== currentIndex) {

						// Pick a remaining element...
						randomIndex = Math.floor(Math.random() * currentIndex);
						currentIndex -= 1;

						// And swap it with the current element.
						temporaryValue = array[currentIndex];
						array[currentIndex] = array[randomIndex];
						array[randomIndex] = temporaryValue;
					}
					this.placeInTeams();
					return array;

				}
			 }
		})
	</script>


<?php get_sidebar(); ?>
<?php get_footer(); ?>