<?php
require_once(ROOT . "inc/db/db.inc.php");

/**
 * 使用数据库抽象层PDO类封装对SQLite数据库的访问。
 */
class PDOSQLite implements IDbAccessEnable{
    private $database;
    
	/**
	 * 运行Sql,以多维数组方式返回结果集。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 * @return 成功返回数组，失败时返回false。
	 */
	public function getData($sql, $db = null){
		$result = false;
        $dh = $this->getDbhandle($db);
        if($dh){
            $dh->beginTransaction();
            $sth = $dh->prepare($sql);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
		return $result;
	}

	/**
	 * 运行Sql,以数组方式返回结果集第一条记录。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 * @return 成功返回数组，失败时返回false。
	 */
	public function getLine($sql, $db = null){
		$result = false;
        $items = $this->getData($sql, $db);
        if(count($items) > 0){
            $result = $items[0];
        }
		return $result;
	}
	
	/**
	 * 运行Sql,返回结果集第一条记录的第一个字段值。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 * @return 成功时返回一个值，失败时返回false。
	 */
	public function getVar($sql, $db = null){
        $result = false;
        $dh = $this->getDbhandle($db);
        if($dh){
            $dh->beginTransaction();
            $sth = $dh->prepare($sql);
            $sth->execute();
            $result = $sth->fetchColumn(0);
        }
        return $result;
	}
	
	/**
	 * 运行Sql语句,不返回结果集。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 */
	public function runSql($sql, $db = null){
        $dh =$this->getDbhandle($db);
        if($dh){
            $dh->exec($sql);
        }
	}
	
	/**
	 * 运行Sql语句,不返回结果集。
	 * @param $sql {String} 要执行的SQL语句。
	 * @param $db {String} 指定的数据库。
	 * @return Int 新添加的ID（异常为-1）。
	 */
	public function insert($sql, $db = null){
		$result = -1;
        $dh =$this->getDbhandle($db);
        if($dh){
            $dh->exec($sql);
            $result = $dh->lastInsertId();
        }
		return $result;
	}

    /**
     * 获取需要使用的数据库。
     * @param <String> $db 查询方法中传递过来的数据库。
     * @return <String> 需要使用的数据库。
     */
    private function getDbhandle($db){
        $dh = null;
        $db_name = "";
        if(strlen($db) > 0){
            //首先使用方法中传递过来的数据库
            $db_name = $db;
        }elseif(defined('SQLITEDATABASE')){
            //其次使用系统配置文件中定义的常量SQLITEDATABASE
            $db_name = SQLITEDATABASE;
        }elseif($this->database){
            $db_name = $this->database;
        }
        if(strlen($db_name) > 0){
            try{
                $dh = new PDO("sqlite:$db_name");
                //设定超时时间为1小时
                $dh->setAttribute(PDO::ATTR_TIMEOUT, 60 * 60);
                //注册数据库常用函数
                //$dh->sqliteCreateFunction('SUBSTR', 'substr', 2);
                //$dh->sqliteCreateFunction('INSTR', 'INSTR', 2);
            }catch(Exception $exc){
            }
        }
        return $dh;
    }
}
?>
