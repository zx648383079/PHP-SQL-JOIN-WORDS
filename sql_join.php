<?php 
/**
* SQL语句的拼接和过滤
*
*
*/
class SQL_Join{
	
	/**
	 * 公有构造函数
	 *
	 * @access public
	 *
	 */
	public function __construct()
	{
		
	}
	/**
	 * 根据SQL关键字拼接语句
	 *
	 * @access private
	 *
	 * @param string $key 关键字.
	 * @param string|array $value 值.
	 * @return 返回拼接后的SQL语句,
	 */
	private function sqlJoin($key,$value)
	{
		$result=' ';
		switch($key)
		{
			/*case 'create':
				$result.='CREATE TABLE '.$this->sqlCheck($value,',');
				break;
			case 'alter':
				$result.='ALTER TABLE '.sqlCheck($value,',');
				break;
			case 'drop':
				$result.='DROP TABLE '.sqlCheck($value,',');
				break;*/
			case 'exec':
				$result.='EXEC '.$this->sqlCheck($value);
				break;
			case 'select':
				$result.='SELECT '.$this->sqlCheck($value,',');
				break;
			case 'from':
				$result.='FROM '.$this->sqlCheck($value,',');
				break;
			case 'update':
				$result.='UPDATE '.$this->sqlCheck($value,',');
				break;
			case 'set':
				$result.='SET '.$this->sqlCheck($value,',');
				break;
			case 'delete':
				$result.='DELETE FROM '.$this->sqlCheck($value,',');
				break;
			case 'insert':
				$result.='INSERT INTO '.$this->sqlCheck($value);
				break;
			case 'values':
				$result.='VALUES '.$this->sqlCheck($value,',');
				break;
			case 'limit':
				$result.='LIMIT '.$this->sqlCheck($value);
				break;
			case 'order':
				$result.='ORDER BY '.$this->sqlCheck($value);
				break;
			case 'group':
				$result.='GROUP BY '.$this->sqlCheck($value);
				break;
			case 'having':
				$result.='HAVING '.$this->sqlCheck($value);
				break;
			case 'where':
				$result.='WHERE '.$this->sqlCheck($value,' AND ');
				break;
			case 'or':
				$result.='OR '.$this->sqlCheck($value);
				break;
			case 'and':
				$result.='AND '.$this->sqlCheck($value);
				break;
			default:															//默认为是这些关键词 'left','right','inner'
				$result.=strtoupper($value).' JOIN '.$this->sqlCheck($value,' ON ');
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
	public function sqlCheck($value,$link=' ')
	{
		$sqlkey=array('select','from','update','set','delete','insert','values','limit','order','group','having','where','or','and','left','right','inner','exec'/*,'alter','drop','create'*/);
		$result='';
		
		if(is_array($value))
		{
			foreach($value as $key=>$v)
			{
				$space=' ';
				
				//把关键字转换成小写进行检测
				$low=strtolower($key);
				if(in_array($low,$sqlkey,true))
				{
					$space.=$this->sqlJoin($low,$v);
				}else{
					if(is_numeric($key))
					{
						if(empty($result))
						{
							$space.=$this->sqlCheck($v);
						}else{
							$space.=$link.$this->sqlCheck($v);
						}
					}else{
						$space.=$key.$link.$this->sqlCheck($v);
					}
				}
				
				$result.=$space;
			}
			
		}else{
			$unsafe=$sqlkey;
			array_push($unsafe,';');                        //替换SQL关键字和其他非法字符，
			$safe=$this->safeCheck($value,'\'',$unsafe,' ');
			$safe=$this->safeCheck($value,'"',$unsafe,' ');
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
	 * @access private
	 *
	 * @param string $unsafe 要检查的语句.
	 * @param string $scope 排除语句的标志.
	 * @param string|array $find 要查找的关键字.
	 * @param string|array $enresplace 替换的字符或数组.
	 * @return 返回完成检查的语句,
	 */
	private function safeCheck($unsafe,$scope,$find,$enresplace)
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
}