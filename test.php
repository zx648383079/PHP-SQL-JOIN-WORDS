<?php 
	include_once('SqlHelper.php');
	
	$where=array(
			'select'=>array(
				"id","a","user"
			),
			'from'=>"message",
			'left'=>array("table"=>array('id','=','userid'))
			
	);
	$tem1=microtime();
	
	
	$sql =new SqlHelper('zx_');    
    echo $sql->getSQL($where);            //获取SQL语句
	
	//echo "<br/>".$sql->getSQL($where,TRUE);          //先进行排序在获取
	
	//$tem2=microtime();
	
	//echo "<br/>开始时间：".$tem1.'<br/>结束时间：'.$tem2.'<br/>用时：'.($tem2-$tem1);
	
	
	
	
