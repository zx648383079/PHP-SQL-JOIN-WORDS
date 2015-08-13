<?php 
	/**
	 * 根据SQL关键字拼接语句
	 *
	 * @access public
	 *
	 * @param string $key 关键字.
	 * @param string|array $value 值.
	 * @return 返回拼接后的SQL语句,
	 */
	function sqlJoin($key,$value)
	{
		$result=' ';
		switch($key)
		{
			/*case 'create':
				$result.='CREATE TABLE '.sqlCheck($value,',');
				break;
			case 'alter':
				$result.='ALTER TABLE '.sqlCheck($value,',');
				break;
			case 'drop':
				$result.='DROP TABLE '.sqlCheck($value,',');
				break;*/
			case 'exec':
				$result.='EXEC '.sqlCheck($value);
				break;
			case 'select':
				$result.='SELECT '.sqlCheck($value,',');
				break;
			case 'from':
				$result.='FROM '.sqlCheck($value,',');
				break;
			case 'update':
				$result.='UPDATE '.sqlCheck($value,',');
				break;
			case 'set':
				$result.='SET '.sqlCheck($value,',');
				break;
			case 'delete':
				$result.='DELETE FROM '.sqlCheck($value,',');
				break;
			case 'insert':
				$result.='INSERT INTO '.sqlCheck($value);
				break;
			case 'values':
				$result.='VALUES '.sqlCheck($value,',');
				break;
			case 'limit':
				$result.='LIMIT '.sqlCheck($value);
				break;
			case 'order':
				$result.='ORDER BY '.sqlCheck($value);
				break;
			case 'group':
				$result.='GROUP BY '.sqlCheck($value);
				break;
			case 'having':
				$result.='HAVING '.sqlCheck($value);
				break;
			case 'where':
				$result.='WHERE '.sqlCheck($value,' AND ');
				break;
			case 'or':
				$result.='OR '.sqlCheck($value);
				break;
			case 'and':
				$result.='AND '.sqlCheck($value);
				break;
			default:
				$result.=strtoupper($value).' JOIN '.sqlCheck($value,' ON ');
				break;
		}
		
		return $result;
	}
	
	/**
	 * SQL关键字检测
	 *
	 * @access public
	 *
	 * @param string|array $value 要检查的语句或数组.
	 * @param string $link 数组之间的连接符.
	 * @return 返回拼接的语句,
	 */
	function sqlCheck($value,$link=' ')
	{
		$sqlkey=array('select','from','update','set','delete','insert','values','limit','order','group','having','where','or','and','left','right','inner','exec'/*,'alter','drop','create'*/);
		$result=' ';
		
		if(is_array($value))
		{
			foreach($value as $key=>$v)
			{
				//把关键字转换成小写进行检测
				$low=strtolower($key);
				if(in_array($low,$sqlkey,true))
				{
					$result.=sqlJoin($low,$v);
				}else{
					if(is_numeric($key))
					{
						if($result==' ')
						{
							$result.=sqlCheck($v);
						}else{
							$result.=$link.sqlCheck($v);
						}
					}else{
						$result.=$key.$link.sqlCheck($v);
					}
				}
			}
			
		}else{
			$unsafe=$sqlkey;
			array_push($unsafe,';');                        //替换SQL关键字和其他非法字符，
			$safe=safeCheck($value,'\'',$unsafe,' ');
			$safe=safeCheck($value,'"',$unsafe,' ');
			$result.=$safe;
		}
		
		$result=preg_replace('/\s+/', ' ', $result);
		$result =str_replace("WHERE AND","WHERE",$result);
		$result =str_replace("WHERE OR","WHERE",$result);
		
		return $result;
	}
	
	 /**
	 * 检查是否是字符串语句
	 *
	 * @access public
	 *
	 * @param string $unsafe 要检查的语句.
	 * @param string $scope 排除语句的标志.
	 * @param string|array $find 要查找的关键字.
	 * @param string|array $enresplace 替换的字符或数组.
	 * @return 返回完成检查的语句,
	 */
	function safeCheck($unsafe,$scope,$find,$enresplace)
	{
		$safe='';
		$arr=explode($scope,$unsafe);
		$len=count($arr);
		if($len==1)
		{
			$safe=$unsafe;
		}else{
			foreach($arr as $key=>$val)
			{
				if($key%2==0)
				{
					$low=strtolower($val);                      //转化为小写
					$safe.=str_replace($find,$enresplace,$low);
				}else{
					//如果排除标志不是成对出现，默认在最后加上
					$safe.=$scope.$val.$scope;
				}
			}
		}
		
		return $safe;
	}