<?php


namespace Sentral\Challenge;

use Exception;

class VenueController extends Model
{

	/**
	 * Get all available venues
	 * @return array
	 */
	public function getVenues(){
		$venues = array();

		$query = "SELECT 
						venue_id, 
						venue_name 
					FROM venue 
					WHERE 
						current = 1";
		$res = $this->db->getAll($query);

		if( !empty($res) ){
			foreach( $res as $venue ){
				$venues[] = [
					'venue_id' => $venue['venue_id'],
					'venue_name' => $venue['venue_name']
				];
			}
		}

		return ['venues' => $venues];
	}

	/**
	 * Method responsible for adding a new venue
	 * @return array
	 * @throws Exception
	 */
	public function saveVenue(){
		$new_venue = array();
		$new_venue['venue_name'] 	= trim(isset($_REQUEST['new_venue_name']) ? $_REQUEST['new_venue_name'] : '');
		$new_venue['venue_address'] = trim(isset($_REQUEST['new_venue_address']) ?
			$_REQUEST['new_venue_address'] : '');

		//double check mandatory fields
		if( !$new_venue['venue_name'] ){
			throw new Exception("Please, insert a valid venue name");
		}

		if( empty($new_venue['venue_address']) ){
			throw new Exception("Please, insert a valid address");
		}

		$venue_id = $this->db->insert('venue', $new_venue);

		if( $venue_id == 0 ){
			throw new Exception("Failed to create an venue");
		}


		return ['venue' => ['venue_id' => $venue_id, 'venue_name' => $new_venue['venue_name']]];
	}

}