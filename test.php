<?php 
	include_once('sql_join.php');
	
	$where=array(
			'limit'=>array('1,2','DESC'),
			'from'=>'havana_profile',
			'where'=>array(
				array('name','like','\'%m%\''),
				'or'=>array('id','>','76')
				),
			'order'=>array('id','DESC'),
			'select'=>array('id','name','phone','udate'),
			'left'=>array('aa',array('id','=','uid'))
	);
	$tem1=microtime();
	
	
	$sql =new SQL_Join();    
    echo $sql->getSQL($where);            //获取SQL语句
	echo "<br/>".$sql->getSQL($where,TRUE);
	
	$tem2=microtime();
	
	echo "<br/>开始时间：".$tem1.'<br/>结束时间：'.$tem2.'<br/>用时：'.($tem2-$tem1);
	
	
	
	
