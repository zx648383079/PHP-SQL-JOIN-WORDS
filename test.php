<?php 
	include_once('sql_join.php');
	
	$where=array(
			'select'=>array('zx','z','x'),
			'from'=>'aa',
			'where'=>array(
				'and'=>array('4','=',"'select'"),
				array('5','like','%3%'),
				'or'=>array('6','>','7')
				),
			'order'=>array('id','DESC'),
			'limit'=>'1,2'
	);
		
	$re =sqlCheck($where);     //获取SQL语句
	echo $re;