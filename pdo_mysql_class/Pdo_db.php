<?php
/**
 * PDO 操作
 * Created by PhpStorm.
 * User: sumiaowen
 * Date: 13-11-28
 * Time: 下午9:12
 * To change this template use File | Settings | File Templates.
 */
class Pdo_db
{
	private $dns = null;
	private $username = null;
	private $password = null;
	private $conn = null;
	private static $_instance = null;

	private function __construct($params = array())
	{
		$this->dns      = $params['dns'];
		$this->username = $params['username'];
		$this->password = $params['password'];

		$this->_connect();
	}

	private function __clone() { }

	public function get_instance($params = array())
	{
		if(!(self::$_instance instanceof self))
		{
			self::$_instance = new self($params);
		}

		return self::$_instance;
	}

	private function _connect()
	{
		try
		{
			$this->conn = new PDO($this->dns, $this->username, $this->password);
			$this->conn->query('set names utf8');
		}
		catch(PDOException $e)
		{
			exit('PDOException: ' . $e->getMessage());
		}
	}

	/**
	 * 查询一条SQL语句
	 * @param string $sql
	 * @param array  $parameters 需要绑定的参数
	 * @param int    $option
	 * @return array
	 */
	public function query($sql, $parameters = array(), $option = PDO::FETCH_ASSOC)
	{
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($parameters);

		$tmp = array();
		while($row = $stmt->fetch($option))
		{
			$tmp[] = $row;
		}

		return $tmp;
	}

	/**
	 * 插入一条数据
	 * @param string $sql
	 * @param array  $parameters
	 * @return int  1 or 0 返回影响行数
	 */
	public function insert($sql, $parameters = array())
	{
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($parameters);

		return $stmt->rowCount();
	}

	/**
	 * 更新一条数据
	 * @param string $sql
	 * @param array  $parameters
	 * @return int  1 or 0 返回影响行数
	 */
	public function update($sql, $parameters = array())
	{
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($parameters);

		return $stmt->rowCount();
	}

	/**
	 * 删除一条数据
	 * @param string $sql
	 * @param array  $parameters
	 * @return int  1 or 0 返回影响行数
	 */
	public function delete($sql, $parameters = array())
	{
		$stmt = $this->conn->prepare($sql);
		$stmt->execute($parameters);

		return $stmt->rowCount();
	}
}