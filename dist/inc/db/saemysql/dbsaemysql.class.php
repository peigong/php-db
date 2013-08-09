<?php
require_once(ROOT . "inc/db/db.inc.php");

/**
 * 封装新浪云主机基于MySQL模块的SaeMysql类。
 */
class DbSaeMySql implements IDbAccessEnable{
	/**
	 * 运行Sql,以多维数组方式返回结果集。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 * @return 成功返回数组，失败时返回false。
	 */
	public function getData($sql, $db = null){
	    $result = null;
	    $mysql = new SaeMysql();
	    $result = $mysql->getData($sql);
	    $mysql->closeDb();
	    return $result;
	}

	/**
	 * 运行Sql,以数组方式返回结果集第一条记录。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 * @return 成功返回数组，失败时返回false。
	 */
	public function getLine($sql, $db = null){
	    $result = null;
	    $mysql = new SaeMysql();
	    $result = $mysql->getLine($sql);
	    $mysql->closeDb();
	    return $result;
	}
	
	/**
	 * 运行Sql,返回结果集第一条记录的第一个字段值。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 * @return 成功时返回一个值，失败时返回false。
	 */
	public function getVar($sql, $db = null){
	    $result = null;
	    $mysql = new SaeMysql();
	    $result = $mysql->getVar($sql);
	    $mysql->closeDb();
	    return $result;
	}
	
	/**
	 * 运行Sql语句,不返回结果集。
	 * @param String $sql 要执行的SQL语句。
	 * @param String $db 指定的数据库。
	 */
	public function runSql($sql, $db = null){
	    $mysql = new SaeMysql();
	    $mysql->runSql($sql);
	    $mysql->closeDb();
	}
}
?>
