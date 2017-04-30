<?php
	$info = array('num'=>'1','username'=>'hello','name'=>'world','contact'=>'23333');
	$info_ = array('num'=>'2','username'=>'good','name'=>'bye','contact'=>'66666');
	$data = array();
	for($i =0;$i < 20;$i ++){
		if ($i % 2 ==0){
			$info['num'] = $i;
			$data[$i] = $info;
		}else{
			$info_['num'] = $i;
			$data[$i] = $info_;
		}

	}
	echo json_encode($data)

?>