<?php

	$input = json_decode(file_get_contents ("php://input"));
	var_dump($input);

	$sql = "INSERT INTO bluenet_v0.service_request (`id`, `name`, `mobile`, `requirements`, `gender`, `timings`, 
													`min_salary`, `max_salary`, `address`, `area`, `remarks`, `worker_area`, 
													`work_time`, `created_time`, `date`, `me_id`, `cem_id`, `status`, 
													`match_id`, `match2_id`, `last_updated`, `done_worker_id`, 
													`fixed_salary`, `user_id`, `priority`) 
				VALUES (NULL, 
					'".	$input->root->mobile."', 
					'". $input->root->requirements."', 
					'". $input->root->gender."', 
					'". $input->root->timings."', 
					'". $input->root->min_salary."', 
					'". $input->root->max_salary."', 
					'". $input->root->address."', 
					'". $input->root->area."', 
					'". $input->root->remarks."', 
					'". $input->root->worker_area."', 
					'". $input->root->work_time."', 
					'". $input->root->created_time."', 
					'". $input->root->date."', 
					'". $input->root->me_id."', 
					'". $input->root->cem_id."', 
					'". $input->root->status."', 
					'". $input->root->match_id."', 
					'". $input->root->match2_id."', 
					'". $input->root->last_updated."', 
					'". $input->root->done_worker_id."', 
					'". $input->root->fixed_salary."', 
					'". $input->root->user_id."', 
					'". $input->root->priority"');";

	$service_request = mysqli_query ($db_handle, $sql);
	if(mysqli_connect_errno()){
		// send 500 html header
	}
	$new_sr_id = mysql_insert_id($db_handle);

	$sql = "SELECT * FROM `area`;";
	$area_array = mysqli_query ($db_handle, $sql);
	
	
		if (mysqli_num_rows($area) !=0 ) {
			//$area->name == $input->root->area
			foreach ($area_array as $key => $area) {
				$area_id = $area -> id;
				$sql = "INSERT INTO bluenet_v0.sr_area (`id`, `sr_id`)
					VALUES ('".$area_id."',
						'". $new_sr_id"');";

				//id: area_id, sr_id: service_request_id

				$sr_area = mysqli_query ($db_handle, $sql);
				if(mysqli_connect_errno()){
					// send 500 html header
				}
			}
		}
		else {
			$sql = "INSERT INTO bluenet_v0.area (`id`, `name`)
			VALUES (NULL, 
				'". $input->root->area"');";

			$area = mysqli_query ($db_handle, $sql);
			if(mysqli_connect_errno()){
				// send 500 html header
			}


			$sql = "INSERT INTO bluenet_v0.sr_area (`id`, `sr_id`)
				VALUES (NULL, 
					'". $new_sr_id"');";

			//id: area_id, sr_id: service_request_id

			$sr_area = mysqli_query ($db_handle, $sql);
			if(mysqli_connect_errno()){
				// send 500 html header
			}
		}
	

	
	


	$sql = "INSERT INTO bluenet_v0.skill_name (`id`, `name`)
				VALUES (NULL, 
					'". $input->root->name"');";

	//id: skill_id, name: skill_name

	$skill_name = mysqli_query ($db_handle, $sql);
	if(mysqli_connect_errno()){
		// send 500 html header
	}


	$sql = "INSERT INTO bluenet_v0.skills (`id`, `user_id`, `skill_id`, `creation_date`, `status`, `type`, `employee_id`)
				VALUES (NULL, 
					'". $input->root->user_id."', 
					'". $input->root->skill_id."', 
					'". $input->root->created_date."', 
					'". $input->root->status."', 
					'". $input->root->type."', 
					'". $input->root->employee_id"');";

	//

	$skills = mysqli_query ($db_handle, $sql);
	if(mysqli_connect_errno()){
		// send 500 html header
	}


?>