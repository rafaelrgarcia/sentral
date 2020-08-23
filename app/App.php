<?php


namespace Sentral\Challenge;

use Exception;

class App
{

	/**
	 * Inits the application by checking if it is an API return or a page call by calling the requested controller and
	 * returns the proper response
	 * @param int $api
	 * @return mixed
	 */
	public static function init( $api = 0 ) {
		$controller = ucfirst(strtolower(isset($_REQUEST['c']) ? $_REQUEST['c'] : 'home')) . "Controller";
		$controller_name = "Sentral\\Challenge\\$controller";
		$method = isset($_REQUEST['m']) ? $_REQUEST['m'] : 'home';

		//if it is an API call
		if ( $api ) {
			header("Content-Type: application/json");

			try {
				$response = self::dispatch($controller_name, $method, $api);
			} catch(Exception $e) {
				die(json_encode(['error' => $e->getMessage()]));
			}

			die(json_encode($response));

		} else { // or just calling a page
			try {
				self::dispatch($controller_name, $method, $api);
			} catch(Exception $e) {
				die("Error starting the application: " .$e->getMessage());
			}
		}
	}

	// Instantiate the controller class and call its method
	private static function dispatch($controller_name, $method, $api){

		$controller = new $controller_name();

		if( $api ){
			return $controller->$method();
		}

		$controller->$method();
	}

}