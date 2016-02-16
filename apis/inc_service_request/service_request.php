<?php

	$input = json_decode(file_get_contents ("php://input"));
	//var_dump($input);

	//$user_id = $_SESSION['user_id'];
	$user_id = 6;
	$route = explode("/",$_SERVER[REQUEST_URI]);
	$status = $route['2'];
	
	$condition = "";

	switch ($status) {
		case 'picked':
			$condition = " sr.cem_id = " .$user_id. " AND sr.status = 'open'";
			break;
		case 'match':
			 $condition = " sr.cem_id = 0 AND sr.me_id != 0 AND status = 'open' AND (sr.match_id != 0 OR sr.match2_id != 0)" ;
			 break;
		case 'meeting':
			$condition = " sr.status = 'meeting' AND sr.cem_id = " .$user_id ;
			break;
		case 'demo':
			 $condition = " sr.status='demo' AND sr.cem_id = " .$user_id ;
			break;
		case 'done':
			$condition = " sr.status='done' AND sr.cem_id = " .$user_id ;
			break;
		case '24':
			 $condition = " sr.cem_id = 0 AND sr.match_id = 0 AND sr.match2_id = 0 AND sr.status = 'open' AND sr.work_time = 24 " ;
			break;
		
		default:
			$condition = " sr.cem_id = 0 AND sr.match_id = 0 AND sr.match2_id = 0 AND sr.status = 'open' AND sr.work_time != 24 " ;
			break;
	}

   /* if ($status == "picked") $condition = " sr.cem_id = " .$user_id. " AND sr.status = 'open'";
	elseif ($status == "match") $condition = " sr.cem_id = 0 AND sr.me_id != 0 AND status = 'open' AND (sr.match_id != 0 OR sr.match2_id != 0)" ;
	elseif ($status == "meeting") $condition = " sr.status = 'meeting' AND sr.cem_id = " .$user_id ;
	elseif ($status == "demo") $condition = " sr.status='demo' AND sr.cem_id = " .$user_id ;
	elseif ($status == "done") $condition = " sr.status='done' AND sr.cem_id = " .$user_id ;
	elseif ($status == "24") $condition = " sr.cem_id = 0 AND sr.match_id = 0 AND sr.match2_id = 0 AND sr.status = 'open' AND sr.work_time = 24 " ;
	else $condition = " sr.cem_id = 0 AND sr.match_id = 0 AND sr.match2_id = 0 AND sr.status = 'open' AND sr.work_time != 24 " ;*/
	
	$service_requests = mysqli_query($db_handle, "SELECT sr.* FROM service_request as sr WHERE ".$condition." ; ") ;

	$rows = array();

	while ($service_requestsRow = mysqli_fetch_assoc($service_requests)) {
		
		$sr_id = $service_requestsRow['id'];
		$match1_id = $service_requestsRow['match_id'];
		$match2_id = $service_requestsRow['match2_id'];
		$me_id = $service_requestsRow['me_id'];
		$cem_id = $service_requestsRow['cem_id'];

		if ( $match1_id !=0 OR $match2_id !=0 ) {
			if ($match1_id != 0) {
				$match_1 = mysqli_query ($db_handle, "SELECT * FROM workers WHERE id = $match1_id ;");
				$match1Row = mysqli_fetch_assoc($match_1) ;
			}
			if ($match2_id !=0) {
				$match_2 = mysqli_query ($db_handle, "SELECT * FROM workers WHERE id = $match2_id ;");
				$match2Row = mysqli_fetch_assoc($match_2) ;
			}
		}

		if ($me_id != 0) {
			$picked_by_me = mysqli_query ($db_handle, "SELECT * FROM user WHERE id = $me_id ;");
			$picked_by_meRow = mysqli_fetch_assoc($picked_by_me) ;
		}

		if ($cem_id !=0) {
			$picked_by_cem = mysqli_query ($db_handle, "SELECT * FROM user WHERE id = $cem_id ;");
			$picked_by_cemRow = mysqli_fetch_assoc($picked_by_cem) ;
		}
		
		$notes = mysqli_query ($db_handle, "SELECT * FROM notes WHERE sr_id = $sr_id ;");

		for($notesArr = array(); $note = mysqli_fetch_assoc($notes); $notesArr[] = $note);
			
		$notes = mysqli_fetch_assoc($notes);

		$rows[] = array_merge( $service_requestsRow , array("notes" => $notesArr ), 
								array("picked_by_me" => $picked_by_meRow ), array("picked_by_cem" => $picked_by_cemRow ),
								array("match1" => $match1Row ), array("match2" => $match2Row )) ;
		$match1Row = "";
		$match2Row = "";
		$picked_by_meRow = "";
		$picked_by_cemRow = "";

	}
 	
	echo "{\"root\":";
	print json_encode($rows);
	echo "}";


?>