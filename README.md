说明
=====

实现SQL拼接和过滤


使用方法
-------

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
		
		
已知问题
--------

如果字符串中出现多个 `'` 或 `"` 那么会把外面的字符都变成小写，

未实现自动排序拼接


更新时间
------
2015/8/13