<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 8/14/16
 * Time: 8:46 PM
 */

function createCustomerWorker($db_handle,$name,$mobile,$address,$photo,$refId,$localId,
                              $service,
                              $pv,$adhar_card,$voter_card,$driving_license,$pan_card,
                              $emergency_no, $native_add){


    //1. adding user worker
    $sql = "INSERT INTO `users` ( `id` , `name` , `mobile` , `email` , `password` , `type` , `address` , `area` ," .
        " `creation` , society_id, photo )
			VALUES (NULL ,
			'" . $name . "',
			'" . $mobile . "',
			'',
			'" . $mobile . "',
			'worker',
			'".$address."',
			'',
			'" . date("Y-m-d H:i:s") . "',
			'2',
            '".$photo."'
			);";

    mysqli_query($db_handle, $sql);

    $uwId = mysqli_insert_id($db_handle);


    //2. updating md5 id
    $result = mysqli_query($db_handle, "Update users set md5_id = MD5(".$uwId .") where id = ".$uwId );

    //3. adding worker docs
    $sql = "INSERT INTO `user_documents` (`id`,  `user_id`, `pv`, `adhar_card`, `voter_id`, `driving_license`, `pan_card`)
				VALUES (NULL,
					'" . $uwId . "',
					'" . $pv . "',
					 '" . $adhar_card . "',
					  '" . $voter_card . "',
					   '" . $driving_license . "',
						'" . $pan_card . "');";

    $result = mysqli_query($db_handle, $sql);


    //4. adding worker
    /*INSERT INTO `bluenet_v3`.`workers` (`id`, `ref_id`, `user_id`, `status`, `emergency_no`, `native_place`, `native_add`, `dob`, `education`, `experience`, `gender`, `remark`, `salary`, `bonus`) VALUES
    (NULL, '1', '3', 'new', '9090909090', 'delhi', 'asdf', '2016-04-05', '10', '5', 'M', 'afsdv', '1000', '2');*/
    $sql = "INSERT INTO `workers` (`id`, `ref_id`, `user_id`, `status`, `emergency_no`, `native_place`,
								`native_add`, `dob`, `education`, `experience`, `gender`, `remark`, `salary`, `local_id`)
				VALUES (NULL,
					'" . $refId . "',
					'" . $uwId . "',
					 'recruited',
					 '" . $emergency_no . "',
					  '',
					   '" . $native_add . "',
						'',
						 '',
						 '',
						  '',
						   '',
							'',
							 '".$localId."');";

    mysqli_query($db_handle, $sql);

    $wId = mysqli_insert_id($db_handle);

    $sId = array('maid'=> 1,'cook' => 2, 'car cleaner' => 15 );

    //5. adding worker service
    $sql = "INSERT INTO `service_worker_mappings`
				(`id`, `worker_id`, `service_id`)
					VALUES ('',
					'" . $wId . "',
					 '" . $sId[$service] . "'
					 );";

    mysqli_query($db_handle, $sql);

    //6. adding worker society mapping
    $sql = "INSERT INTO `society_worker_mapping`
				(`worker_id`, `society_id`)
					VALUES (
					'" . $wId . "',
					 '2'
					 );";

    mysqli_query($db_handle, $sql);

    return $uwId;
}