<?php
    function db_query($query) {
        $connection = mysqli_connect("localhost","root",'',"php_login_database");
        $result = mysqli_query($connection,$query);
        return $result;
}

function delete($tblname,$field_id,$id){

	$sql = "delete from ".$tblname." where ".$field_id."=".$id."";
	return db_query($sql);

}

function edit($tblname,$field_id,$id){

	$sql = "select * from $tblname u where $id=id";
	$result = db_query($sql);
	$row = mysqli_fetch_object($result);

	if($row->rol_id == '1'){	

		$sql = "UPDATE `$tblname` SET `rol_id` = '2' WHERE $field_id = $id";
		return db_query($sql);

	} else{

		$sql = "UPDATE `$tblname` SET `rol_id` = '1' WHERE $field_id = $id";
		return db_query($sql);

	}

}

function visibilidadCuenta($tblname, $field_id, $id){
	$sql = "select * from $tblname u where $id=id";
	$result = db_query($sql);
	$row = mysqli_fetch_object($result);

	if($row->recibeSaldo == '1'){	

		$sql = "UPDATE `$tblname` SET `recibeSaldo` = '0' WHERE $field_id = $id";
		return db_query($sql);

	} else{

		$sql = "UPDATE `$tblname` SET `recibeSaldo` = '1' WHERE $field_id = $id";
		return db_query($sql);

	}

}

function visibilidadPuesto($tblname, $field_id, $id){
	$sql = "select * from $tblname u where $id=id";
	$result = db_query($sql);
	$row = mysqli_fetch_object($result);

	if($row->visibilidad == 'Si'){	

		$sql = "UPDATE `$tblname` SET `visibilidad` = 'No' WHERE $field_id = $id";
		return db_query($sql);

	} else{

		$sql = "UPDATE `$tblname` SET `visibilidad` = 'Si' WHERE $field_id = $id";
		return db_query($sql);

	}

}

function select_id($tblname,$field_name,$field_id){
	$sql = "Select * from ".$tblname." where ".$field_name." = ".$field_id."";
	$db=db_query($sql);
	$GLOBALS['row'] = mysqli_fetch_object($db);

	return $sql;
}
?>
