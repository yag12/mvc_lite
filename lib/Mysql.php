<?php
class Mysql
{
	/*
	* @Desc : Database
	* @Var : Object
	*/
	protected $db = null;

	protected $where = array();
	protected $fields = array();
	protected $limit = 0;
	protected $offset = 0;
	protected $sort = null;
	protected $total = 0;
	protected $count = 5;

	/*
	* @Desc : Construct
	* @Param : Database $db
	* @Return : void
	*/
	public function __construct(&$config = null)
	{
		$server = $config['host'] . (!empty($config['port']) ? ':' . $config['port'] : '');
		$user = $config['user'];
		$passwd = $config['passwd'];
		$name = $config['db'];

		$connect = mysql_connect($server, $user, $passwd) or die(mysql_error());
		$this->db = mysql_select_db($name, $connect);
		$this->setEncode();
	}

	private function setEncode()
	{
		mysql_query("SET NAMES UTF8");
	}

	/*
	* @Desc : Data Select
	* @Param : mixed $tb
	* @Param : false or string $cache
	* @Return : array
	*/
	public function select($tb = null, $cache = false)
	{
		$rows = null;
		$fields = !empty($this->fields) ? join(',', $this->fields) : '*';

		$wheres = null;
		if(!empty($this->where))
		{
			foreach($this->where as $key=>$value)
			{
				$wheres[] = $key . '=' . (is_string($value) ? "'" . $value . "'" : $value);
			}
			$wheres = join(' AND ', $wheres);
		}

		$sort = !empty($this->sort) ? ' ORDER BY ' . join(',', $this->sort) : '';
		$limit = null;

		if(!empty($this->limit['limit']))
		{
			$limit = ' LIMIT ' . $this->limit['limit'];
		}

		if(!empty($limit) && !empty($this->limit['offset']))
		{
			$limit = $limit . ', ' . $this->limit['offset'];
		}

		$query = mysql_query('SELECT COUNT(*) FROM ' . $tb . ' ' . $wheres);
		$count = mysql_fetch_array($query);
		$this->total = !empty($count[0]) ? $count[0] : 0;

		$query = mysql_query('SELECT ' . $fields . ' FROM ' . $tb . ' ' . $wheres . $sort . $limit);
		while(($row = mysql_fetch_array($query)) !== false)
		{
			$rows[] = $row;
		}

		return $rows;
	}

	/*
	* @Desc : Data Insert
	* @Param : mixed $tb
	* @Return : boolean
	*/
	public function insert($tb = null)
	{
		if(!empty($this->fields))
		{
			$fields = null;
			foreach($this->fields as $field=>$value)
			{
				$fields[] = $field . '=' . (is_string($value) ? "'" . $value . "'" : $value);
			}
			$fields = join(',', $fields);

			mysql_query('INSERT INTO ' . $tb . ' SET ' . $fields);

			return true;
		}

		return false;
	}

	/*
	* @Desc : Data Update
	* @Param : mixed $tb
	* @Return : boolean
	*/
	public function update($tb = null)
	{
		if(!empty($this->fields))
		{
			$fields = null;
			foreach($this->fields as $key=>$value)
			{
				$fields[] = $key . '=' . (is_string($value) ? "'" . $value . "'" : $value);
			}
			$fields = join(',', $fields);

			$wheres = null;
			if(!empty($this->where))
			{
				foreach($this->where as $key=>$value)
				{
					$wheres[] = $key . '=' . (is_string($value) ? "'" . $value . "'" : $value);
				}
				$wheres = join(' AND ', $wheres);
			}

			mysql_query('UPDATE ' . $tb . ' SET ' . $fields . ' ' . $wheres);

			return true;
		}

		return false;
	}

	/*
	* @Desc : Data Delete
	* @Param : mixed $tb
	* @Return : boolean
	*/
	public function remove($tb = null)
	{
		$wheres = null;
		if(!empty($this->where))
		{
			foreach($this->where as $key=>$value)
			{
				$wheres[] = $key . '=' . (is_string($value) ? "'" . $value . "'" : $value);
			}
			$wheres = join(' AND ', $wheres);
		}

		mysql_query('DELETE FROM ' . $tb . ' ' . $where);

		return true;
	}

	/*
	* @Desc : Data Where
	* @Param : array $data
	* @Return : Model
	*/
	public function where($data = array())
	{
		$this->where = $data;

		return $this;
	}

	/*
	* @Desc : Data Limit
	* @Param : int $offset
	* @Param : int $limit
	* @Return : Model
	*/
	public function limit($offset = 0, $limit = 0)
	{
		$this->limit = $limit;
		$this->offset = $offset;

		return $this;
	}

	/*
	* @Desc : Select Data Field
	* @Param : array $data
	* @Return : Model
	*/
	public function fields($data = array())
	{
		$this->fields = $data;

		return $this;
	}

	/*
	* @Desc : Select Data Sort
	* @Param : array $data
	* @Return : Model
	*/
	public function sort($data = array())
	{
		$this->sort = $data;

		return  $this;
	}

	/*
	* @Desc : Paginator
	* @Param : int $pgnum
	* @Param : int $count
	* @Return : array
	*/
	public function getPaginator($pgnum = 1, $count = 0)
	{
		if(empty($count)) $count = $this->count;
		$limit = $this->limit;
		$total = $this->total;

		$paginator = Func::getPaginator($total, $limit, $pgnum, $count);
		return array(
			'total' => $total,
			'pgnum' => $pgnum,
			'totalpg' => ceil($total / $limit),
			'paginator' => $paginator
		);
	}
}
