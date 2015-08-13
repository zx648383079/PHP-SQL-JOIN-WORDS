<?php 
	include_once('sql_join.php');
	
	$where=array(
			'select'=>array('id','name','phone','udate'),
			'from'=>'havana_profile',
			'where'=>array(
				array('name','like','\'%m%\''),
				'or'=>array('id','>','76')
				),
			'order'=>array('id','DESC')
	);
	$tem1=microtime();
		
	$re =sqlCheck($where);     //获取SQL语句
	
	$tem2=microtime();
	
	
	
	echo $re;
	echo "<br/>用时：".$tem2.'he'.$tem1;