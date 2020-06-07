<?php
echo "logout users";
// get all sessions for user with ID $user_id
$sessions = WP_Session_Tokens::get_instance($user_id);

// we have got the sessions, destroy them all!
$sessions->destroy_all();

?>