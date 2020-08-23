<?php


namespace Sentral\Challenge;

use Exception;

/**
 * Class Model - Class responsible for the Model layer of the application.
 * Gets the DB connection and works as a middleware between the controllers and the database
 * @package Sentral\Challenge
 */
class Model extends DBConnection
{
	protected $db = NULL;

	public function __construct() {
		$this->db = DBConnection::__getInstance();

		parent::__construct( $this );
	}
}