<?php


namespace Sentral\Challenge;

use Exception;

/**
 * Class PersonController
 * @package Sentral\Challenge
 */
class PersonController extends Model
{

	/**
	 * Get all available persons filtering by the person type (not mandatory)
	 * @return array
	 */
	public function getPersons( $person_type_id = '' ){
		$persons = array();
		$params = array();
		$where = "";

		if( empty($person_type_id) ){
			$person_type_id = isset($_REQUEST['person_type_id']) ? $_REQUEST['person_type_id'] : '';
		}

		if( !empty($person_type_id) ){
			$where .= " AND p.person_type_id IN($person_type_id)";
			$params[] = $person_type_id;
		}

		$query = "SELECT 
						p.person_id, 
						p.person_name,
						pt.person_type
					FROM person p
						INNER JOIN person_type pt ON(pt.person_type_id = p.person_type_id AND pt.current = 1)
					WHERE 
						p.current = 1
						$where
					ORDER BY 
						p.person_name";
		$res = $this->db->getAll($query, $params);

		if( !empty($res) ){
			foreach( $res as $person ){
				$persons[] = [
					'person_id' 	=> $person['person_id'],
					'person_name' 	=> $person['person_name'],
					'person_type' 	=> $person['person_type']
				];
			}
		}

		return ['persons' => $persons];
	}
	
	/**
	 * Method responsible for adding a new organiser
	 * @return array
	 * @throws Exception
	 */
	public function saveOrganiser(){
		$new_organiser = array();
		$new_organiser['person_name'] = trim(isset($_REQUEST['new_organiser_name']) ? $_REQUEST['new_organiser_name'] : '');
		$new_organiser['person_type_id'] = PERSON_TYPE_ORGANISER;

		//double check mandatory fields
		if( !$new_organiser['person_name'] ){
			throw new Exception("Please, insert a valid organiser name");
		}

		$organiser_id = $this->db->insert('person', $new_organiser);

		if( $organiser_id == 0 ){
			throw new Exception("Failed to create an organiser");
		}


		return ['organiser' => ['organiser_id' => $organiser_id, 'organiser_name' => $new_organiser['person_name']]];
	}

}