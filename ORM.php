<?php
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/8
 * Time: 19:26
 */
class ORM implements ArrayAccess{

    protected $operators = array(
        '=', '<', '>', '<=', '>=', '<>', '!=',
        'like', 'like binary', 'not like', 'between', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to'
    );

    protected $select = array();

    protected $table = array();

    protected $join = array();

    protected $order = array();

    protected $group = array();

    protected $having = array();

    protected $limit = null;

    protected $offset = null;

    protected $data = array();

    public function select($field = '*') {
        if (!is_array($field)) {
            $this->select = array_merge($this->select, func_get_args());
            return $this;
        }
        foreach ($field as $key => $item) {
            if (is_integer($key)) {
                $this->select[] = $item;
            } else {
                $this->selectAs($item, $key);
            }
        }
        return $this;
    }

    public function selectAs($field, $as) {
        $this->select[] = $field.' AS '.$as;
        return $this;
    }

    public function count($column = '*') {
        return $this->_selectFunction(__FUNCTION__, $column);
    }

    public function max($column)  {
        return $this->_selectFunction(__FUNCTION__, $column);
    }

    public function min($column)  {
        return $this->_selectFunction(__FUNCTION__, $column);
    }

    public function avg($column)  {
        return $this->_selectFunction(__FUNCTION__, $column);
    }

    public function sum($column)  {
        return $this->_selectFunction(__FUNCTION__, $column);
    }

    private function _selectFunction($name, $column) {
        return $this->selectAs("{$name}({$column})", $name);
    }

    public function from($table) {
        if (!is_array($table)) {
            $table = func_get_args();
        }
        foreach ($table as $key => $value) {
            if (is_int($key)) {
                $this->table[] = $value;
            } else {
                $this->table[] = $value. ' '.$key;
            }
        }
        return $this;
    }

    public function where($first, $operator, $second, $boolean = 'AND') {

    }


    public function join($table, $condition, $join = 'inner') {

    }

    public function left($table, $condition) {

    }

    public function right($table, $condition) {

    }

    public function inner($table, $condition) {

    }

    public function on($condition) {

    }

    public function group($column) {
        if (is_string($column)) {
            $column = func_get_args();
        }
        if (is_array($column)) {
            $this->group = array_merge($this->group, $column);
        }
        return $this;
    }

    /**
     * 用条件筛选已分组的组
     */
    public function having() {

    }

    public function order($name, $sort = 'ASC') {
        if (is_string($name)) {
            $this->order[] = $name.' '.strtoupper($sort);
        } elseif (is_array($name)) {
            foreach ($name as $key => $value) {
                if (is_int($key)) {
                    $this->order[] = $value;
                } else {
                    $this->order[] = $key.' '. strtoupper($value);
                }
            }
        }
        return $this;
    }

    /**
     * 当长度为null是，把第一个参数作为长度
     * @param integer $start
     * @param integer $length
     */
    public function limit($start, $length = null) {
        $this->limit = $start;
        if (null !== $length) {
            $this->limit .= ','.$length;
        }
        return $this;
    }

    public function offset($start) {
        $this->offset = $start;
        return $this;
    }

    public function insert($table, $column, $value = null) {
        $this->table[] = $table;
        if (is_array($column)) {
            $this->data = array_merge($this->data, $column);
            return $this;
        }
        $this->data[$column] = $value;
        return $this;
    }

    public function update($table, $column, $where) {
        $this->table[] = $table;
        $this->data = array_merge($this->data, $column);
    }

    public function find($id) {
        return $this->where('id', '=', $id);
    }

    public function get() {

    }

    public function save() {

    }
    
    
	private function _select($value) {
		if (!is_array($value)) {
			return 'SELECT '.$value;
		}
		$result[] = array();
		foreach ($value as $key => $item) {
			if (is_int($key)) {
				$result[] = $item;
			} else {
				$result[] = $item. ' AS '. $key;
			}
		}
		return 'SELECT '.implode(',', $result);
	}

	private function _from($value) {
		if (!is_array($value)) {
			return 'FROM '.$value;
		}
		$result[] = array();
		foreach ($value as $key => $item) {
			if (is_int($key)) {
				$result[] = $item;
			} else {
				$result[] = $item. ' '. $key;
			}
		}
		return 'FROM '.implode(',', $result);
	}

	/**
	 * UNION 内部的 SELECT 语句必须拥有相同数量的列。列也必须拥有相似的数据类型。同时，每条 SELECT 语句中的列的顺序必须相同
	 * @param $value
	 * @param bool $all  默认地，UNION 操作符选取不同的值。如果允许重复的值，请使用 UNION ALL。
	 * @return string
	 */
	private function _union($value, $all = false) {
		return 'UNION '.($all ? 'ALL ' : '').$value;
	}

	/**
	 *
	 * @param array $value array($table, $on)
	 * @param string $join inner,left,right
	 * @return string
	 */
	private function _join(array $value, $join = 'inner') {
		return strtoupper($join).' JOIN '.$value[0].' ON '.$value[1];
	}

	private function _group($value) {
		if (is_array($value)) {
			return 'GROUP BY '.implode(',', $value);
		}
		return 'GROUP BY '.$value;
	}

	private function _having($value) {
		if (empty($value)) {
			return null;
		}
		return 'HAVING '.$this->_getWhere($value);
	}

	private function _order($value) {
		if (!is_array($value)) {
			return 'ORDER BY '.$value;
		}
		$result[] = array();
		foreach ($value as $key => $item) {
			if (is_int($key)) {
				$result[] = $item;
			} else {
				$result[] = $key. ' '. strtoupper($item);
			}
		}
		return 'ORDER BY '.implode(',', $result);
	}

	private function _limit($value) {
		if (is_array($value)) {
			return 'LIMIT '.implode(',', $value);
		}
		return 'LIMIT '.$value;
	}

	private function _offset($value) {
		return 'OFFSET '.$value;
	}

	/**
	 * @param string|array $where
	 *
	 * @return null|string
	 */
	private function _where($where) {
		if (empty($where)) {
			return null;
		}
		return 'WHERE '.$this->_getWhere($where);
	}

	/**
	 *
	 * example: a = b;
	 * array(a=b);
	 * array(array(a=b, or))
	 * array(array(a,b,or))
	 * array(array(a,=,b,or))
	 * @param $value
	 * @return mixed
	 */
	private function _getWhere($value) {
		if (!is_array($value)) {
			return $value;
		}
		$result = '';
		foreach ($value as $item) {
			if (empty($result)) {
				$result .= $item;
			} else {
				$result .= $this->_getWhereOne($item);
			}
		}
		return $result;
	}

	private function _getWhereOne($value) {
		if (!is_array($value)) {
			return 'AND '.$value;
		}
		switch(count($value)) {
			case 1:
				return 'AND '.$value[0];
			case 2:
				if (in_array(strtolower($value[1]), array('and', 'or'))) {
					return strtoupper($value[1]).' '.$value[0];
				}
				return "AND `{$value[0]}` = '{$value[1]}'";
			case 3:
				if (in_array(strtolower($value[2]), array('and', 'or'))) {
					return strtoupper($value[2])." `{$value[0]}` = '{$value[1]}'";
				}
				return "AND `{$value[0]}` {$value[1]} '{$value[2]}'";
			case 4:
				return strtoupper($value[3])." `{$value[0]}` {$value[1]} '{$value[2]}'";
		}
		return implode(' ', $value);
	}

    public function __set($name, $value) {
        $this->offsetSet($name, $value);
    }

    public static function __callStatic($name, $arguments) {
        $instance = new static();
        return call_user_func_array($name, $arguments);
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
        return null;
    }

    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
}