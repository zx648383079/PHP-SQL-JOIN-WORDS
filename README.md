说明
=====

实现SQL拼接和过滤


使用方法
-------

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
		
		$sql =new SQL_Join();    
    	echo $sql->getSQL($where);   
		
已实现
------
1.关键字识别

1.简单排序
		
		$sql->getSQL($where,TRUE)
	
已知问题
--------

如果字符串中出现多个 `'` 或 `"` 那么会把外面的字符都变成小写，

未实现排序深度控制


更新时间
------
2015/8/13