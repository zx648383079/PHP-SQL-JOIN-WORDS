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
	
	ECHO "<br>";
	
	$filed=array(
			'select'=>array(
				'u.id AS uid, u.openid, u.name AS wename, u.cdate',
				'p.name, p.phone, p.email, p.cdate AS postdate, IFNULL(t.pnumber, 0) AS pnumber, t.jdate'
			),
			'from'=>'user u',
			'left'=>array('profile p'=>'u.id = p.uid'),
			'left`'=>array(
				'(',
				'select'=>array(
					'c.uid, COUNT(c.id) AS pnumber, MIN(c.cdate) AS jdate'
				),
				'from'=>'collect c',
				'where'=>array(
					'c.status = 10',
					'c.cdate BETWEEN \'$startdate\'',
					'\'$enddate\''
				),
				'group'=>'c.uid',
				')'
			),
			't ON u.id = t.uid',
			'where'=>array(
				'u.levels = 20',
				'(u.openid LIKE \'%$keys%\'',
				'or'=>'p.name LIKE \'%$keys%\'',
				'or'=>'p.phone LIKE \'%$keys%\')',
				'(IFNULL(t.pnumber, 0) > 0',
				'or'=>'(IFNULL(t.pnumber, 0) = 0',
				'u.cdate BETWEEN \'$startdate\'',
				'\'$enddate\')'
			)
		);
		
	$filed['where'][]='hahaga = 10';
		
	ECHO $sql->getSQL($filed);
	
	
	//echo "<br/>".$sql->getSQL($where,TRUE);          //先进行排序在获取
	
	//$tem2=microtime();
	
	//echo "<br/>开始时间：".$tem1.'<br/>结束时间：'.$tem2.'<br/>用时：'.($tem2-$tem1);
	
	
	
	
