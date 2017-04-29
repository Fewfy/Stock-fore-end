<?php
	$info = array('num'=>'1','firstname'=>'hello','lastname'=>'world','username'=>'a');
	$info_ = array('num'=>'2','firstname'=>'good','lastname'=>'bye','username'=>'b');
	$data = array();
	for($i =0;$i < 10;$i ++){
		if ($i % 2 ==0){
			$data[$i] = $info;
		}else{
			$data[$i] = $info_;
		}

	}
	echo json_encode($data)

?>