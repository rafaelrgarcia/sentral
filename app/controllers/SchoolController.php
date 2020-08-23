<?php


namespace Sentral\Challenge;

class SchoolController extends Model
{

	/**
	 * Get all available schools
	 * @return array
	 */
	public function getSchools(){
		$schools = array();

		$query = "SELECT 
						school_id, 
						school_name 
					FROM school 
					WHERE 
						current = 1";
		$res = $this->db->getAll($query);

		if( !empty($res) ){
			foreach( $res as $school ){
				$schools[] = [
					'school_id' => $school['school_id'],
					'school_name' => $school['school_name']
				];
			}
		}

		return ['schools' => $schools];
	}

}