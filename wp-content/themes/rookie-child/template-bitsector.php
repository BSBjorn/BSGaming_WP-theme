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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css">
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<div id="primary" class="content-area content-area-<?php echo rookie_get_sidebar_setting(); ?>-sidebar">
		<main id="main" class="site-main" role="main">
		
		<div id="app">
            <div class="columns">
				<div class="column">
					<h1 class="mb-4 is-size-3">SCRIM 5V5</h1>
					
					<button class="button is-primary my-4 mr-2" v-if="lockTeams == false" @click="shuffle(players)">Shuffle</button>
					<button v-if="lockTeams == false" class="button my-4 mr-2 is-success" @click="lockTeams = !lockTeams">Lock Teams</button>
					<button v-else class="button is-warning my-4 mr-2" @click="lockTeams = !lockTeams">Unlock Teams</button>

				</div>
				<div class="column">
					<h2 class="is-size-4">Velkommen <?php echo $nick ?></h2>
					<?php if($steamid) { ?>
					<img src="<?php echo $avatar ?>" alt="">
					<p>Din steamID er: <?php echo convertSteamID($steamid) ?></p>
					<?php } ?>
				</div>
			</div>
		
		<div class="columns">
			<div class="column">
				<h4 class="is-size-5 mb-2">Team 1: {{config.team1.name}}</h4>
				<ul v-for="player in team1">
					<li :class="{captain: player.nickname == captain1}">{{player.nickname}}</li>
				</ul>
			</div>

			<div class="column">
				<h4 class="is-size-5 mb-2">Team 2: {{config.team2.name}}</h4>
				<ul v-for="player in team2">
					<li :class="{captain: player.nickname == captain2}">{{player.nickname}}</li>
				</ul>
			</div>	
		</div>
		
		<div class="columns">
			<div v-if="lockTeams == true" class="column">
				<p for="mapPick">Please choose a map:</p>
				<div class="field">
					<div class="controll">
						<div class="select is-primary">
						<select name="mapPick" id="mapPick" v-model="mapPick"> 
							<option value="">....</option>
							<option v-for="map in maps" v-bind:value="map">{{map}}</option>
						</select>
						</div>
					</div>
				</div>
			</div>
			<div class="column" v-if="mapPick">
				<h5>Map will be <span class="has-text-primary">{{mapPick}}</span></h5>
				<button class="my-2 button is-info" @click="startServer()">Start Server</button>
			</div>
		</div>
		<div class="columns" v-if="serverStarted">
			<div class="column">
				<button class="button is-large is-success">CONNECT TO SERVER</button>
			</div>
		</div>
		
		</div><!-- #app -->
		</main><!-- #main -->
	</div><!-- #primary -->


<pre>
	<?php include('config/chess.json') ?> 
</pre>



<?php get_sidebar(); ?>
<?php get_footer(); ?>	

<script>

//import from config/chess.json
		var app = new Vue({
			el: '#app',
			data: {
				message: 'Hello Vue!',
				config: <?php include('config/chess.json') ?>,
				users: <?php echo json_encode($onlineUsers); ?>,
				players: [
					{nickname: "B-King", steamid: 123456},
					{nickname: "chessome", steamid: 123454},
					{nickname: "Turtlefly_", steamid: 123426},
					{nickname: "Niso", steamid: 644456},
					{nickname: "Mayhem", steamid: 773532},
					{nickname: "Regn", steamid: 073532},
					{nickname: "Nilsen", steamid: 573532},
					{nickname: "Molle2k", steamid: 732532},
					{nickname: "TheBait", steamid: 986441},
					{nickname: "DMK", steamid: 1735320},
				],
				lockTeams: false,
				team1: [],
				team2: [],
				captain1: '',
				captain2: '',
				mapPick: null,
				maps: null,
				serverStarted: false,
			 },
			mounted: function() {
				this.maps = this.config.maplist;
				this.placeInTeams();
			},
			methods: {
				startServer: function() {
					setTimeout(() => this.serverStarted = true, 5000);
				},

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
					this.captain1 = this.players[0].nickname;
					this.captain2 = this.players[5].nickname;
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
