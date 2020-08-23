<?php


namespace Sentral\Challenge;

use \PDO;
use DBException;

/**
 * Class DBConnection
 * @package Sentral\Challenge
 */
class DBConnection extends PDO {

	public function __construct() {
		 parent::__construct(
			$_ENV['DB_CONNECTION'] . ":dbname=" . $_ENV['DB_DATABASE'] . ";host" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'],
			$_ENV['DB_USERNAME'],
			$_ENV['DB_PASSWORD'],
			array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
		);
	}

	static function __getInstance() {
		return new self();
	}

	/**
	 * Runs a generic query.
	 * It should be used only for specific queries.
	 * if you are trying to run a select, update or insert, please use getAll/getRow/getOne, insert or edit.
	 * @param string $sql
	 * @param array $params
	 * @param array $return_affected
	 * @return bool|\PDOStatement
	 */
	public function query($sql, $params = array(), $return_affected = 0) {
		if (empty($params)){
			if( $return_affected ){
				$res = $this->prepare($sql);
				$res->execute($params);
				return $res->rowCount();
			}
			return parent::query($sql);
		}
		if (!is_array($params)) {
			$params = func_get_args();
			array_shift($params);
		}
		$res = $this->prepare($sql);

		return $res->execute($params);
	}

	/**
	 * Returns an array with all the results of the query
	 * @param $query
	 * @return array
	 */
	public function getAll($query, $params = array()) {
		if (!is_array($params)) {
			$params = func_get_args();
			array_shift($params);
		}
		$res = $this->prepare($query);
		$res->execute($params);
		$result = array();
		foreach( $res as $row )
			$result[] = $row;
		return $result;
	}

	/**
	 * Returns an array with the first result of the query
	 * @param $query
	 * @return array
	 */
	public function getRow($query, $params = array()) {
		if (!is_array($params)) {
			$params = func_get_args();
			array_shift($params);
		}
		$res = $this->prepare($query);
		$res->execute($params);
		foreach( $res as $row )
			return $row;
	}

	/**
	 * Return a string with the first column of the query
	 * @param $query
	 * @return string
	 */
	public function getOne($query, $params = array()) {
		$res = $this->prepare($query);
		$res->execute($params);
		foreach( $res as $row )
			return ($row[0]);
	}

	/**
	 * Set the table fields with the params sent from the model and cleans the string.
	 * Any extra generic DB pre insert/update should be added here
	 * @param string $table
	 */
	public function calcValues($table, $params) {
		$rows = $this->getAll("SHOW COLUMNS FROM $table");
		$fields = array();

		// List all the fields
		foreach( $rows as $row ){
			if( $row['Extra'] == "auto_increment" ){
				continue;
			}
			if( isset($params[$row['Field']]) ) {
				$params[$row['Field']] =
					preg_replace(
						'~[\x00\x0A\x0D\x1A\x22\x25\x27\x5C\x5F]~u',
						'\\\$0',
						$params[$row['Field']]
					);
				$fields[$row['Field']] = $row['Type'];
			}
		}
		// compare table fields with the columns sent by the controller
		$values = array_intersect_key($params, $fields);

		return $values;
	}

	/**
	 * Inserts a new record on the database
	 * @param $table
	 * @param $params
	 * @return string
	 */
	public function insert($table, $params) {

		$field_list = array();  //field list string
		$value_list = array();  //value list string
		$params_list = array();

		$values = $this->calcValues($table, $params);

		foreach( $values AS $key => $value ){
			$field_list[] = $key;
			$value_list[] = ":$key";
			$params_list[":$key"] = $value;
		}

		$field_list[] = "inserted_at";
		$value_list[] = ":inserted_at";
		$params_list[':inserted_at'] = date('Y-m-d H:i:s');

		$field_list[] = "updated_at";
		$value_list[] = ":updated_at";
		$params_list[':updated_at'] = date('Y-m-d H:i:s');

		$query = "INSERT INTO $table 
					(" . implode(',', $field_list) . ") 
					VALUES
					(" . implode(',', $value_list) . ")";

		$cmd = $this->prepare($query);
		$cmd->execute($params_list);

		return $this->lastInsertId();
	}

	/**
	 * Edits a list of columns from that table for the where statement
	 * @param $table
	 * @param $params
	 * @param $where_string
	 * @param $where_params
	 * @return string
	 */
	public function edit($table, $params, $where_string, $where_params) {

		$field_list = array(); //field list string
		$params_list = array();

		$values = $this->calcValues($table, $params);

		foreach( $values AS $key => $value ){
			$field_list[] = "$key = ?";
			$params_list[] = $value;
		}

		$field_list[] = "updated_at = ?";
		$params_list[] = date('Y-m-d H:i:s');

		foreach( $where_params as $where_item ){
			array_push($params_list, $where_item);
		}

		$query = "UPDATE $table SET " . implode(',', $field_list) . " WHERE $where_string";

		$cmd = $this->prepare($query);
		$res = $cmd->execute($params_list);

		return $this->lastInsertId();
	}
}