<?php

if (Users::$Session == true) {

    if ($_POST['action'] == 'enterRoom' && isset($_POST['id'])) {
        if ($Room = CMS::$MySql->single('SELECT id FROM rooms WHERE id = :id', array('id' => $_POST['id'])))
        {
            CMS::$MySql->query('UPDATE users SET home_room=:room WHERE id=:userid', array('room' => $Room, 'userid' => Users::$Session->ID));
            $Data['online'] = Users::$Session->Data['online'];

            if ($Data['online'] == 1) {
                Site::RCON('forwarduser', array('user_id' => Users::$Session->ID, 'room_id' => $Room));
                $Data['message'] = CMS::$Lang['roomenteronline'];
            }
            exit (json_encode($Data));
        }
    }
	
	if ($_POST['action'] == 'getOnline') {
		exit (json_encode(Array('valid' => true, 'online' => Site::GetOnline())));
    } else {
		exit (json_encode(Array('valid' => false, 'online' => < 1)));
		echo 'Er is niemand online in het hotel.';
	}
}
