<?php


namespace Sentral\Challenge;

use Exception;
use DateTime;

class EventController extends Model
{

	/**
	 * Get all available events
	 * @return array
	 */
	public function getEvents(){
		$persons = array();

		$query = "SELECT 
						e.event_id,
						e.event_name,
						DATE_FORMAT(e.event_datetime, '%d/%m/%Y %H:%i') as event_datetime,
						s.school_name,
						v.venue_name,
						c.category_name,
						e.distance,
						e.travel_time,
						GROUP_CONCAT(DISTINCT p_org.person_name SEPARATOR ', ') AS 'organisers',
						GROUP_CONCAT(DISTINCT p_par.person_name SEPARATOR ', ') AS 'participants',
						GROUP_CONCAT(DISTINCT p_att.person_name SEPARATOR ', ') AS 'attendees'
					FROM event e
						INNER JOIN school s ON(s.school_id = e.school_id AND s.current = 1)
						INNER JOIN venue v ON(v.venue_id = e.venue_id AND v.current = 1)
						INNER JOIN category c ON(c.category_id = e.category_id AND v.current = 1)
						INNER JOIN event_organiser eo ON(eo.event_id = e.event_id AND eo.current = 1)
						INNER JOIN person p_org ON(p_org.person_id = eo.person_id AND p_org.current = 1)
						LEFT JOIN event_participant ep ON(ep.event_id = e.event_id AND ep.current = 1)
						LEFT JOIN person p_par ON(p_par.person_id = ep.person_id AND p_org.current = 1)
						LEFT JOIN event_attendee ea ON(ea.event_id = e.event_id AND ea.current = 1)
						LEFT JOIN person p_att ON(p_att.person_id = ea.person_id AND p_att.current = 1)
					WHERE 
						e.current = 1
					GROUP BY e.event_id
					ORDER BY e.event_datetime DESC";
		$res = $this->db->getAll($query);

		if( !empty($res) ){
			foreach( $res as $event ){
				$events[] = [
					'event_id' 			=> $event['event_id'],
					'event_name' 		=> $event['event_name'],
					'event_datetime' 	=> $event['event_datetime'],
					'school_name' 		=> $event['school_name'],
					'venue_name' 		=> $event['venue_name'],
					'category_name' 	=> $event['category_name'],
					'distance' 			=> ($event['distance'] ? $event['distance']." km" : ""),
					'travel_time' 		=> ($event['travel_time'] ? $event['travel_time']." minutes" : ""),
					'organisers' 		=> $event['organisers'],
					'participants' 		=> $event['participants'],
					'attendees' 		=> $event['attendees'],
				];
			}
		}

		return ['events' => $events];
	}

	/**
	 * Get event data
	 * @return array
	 */
	public function getEvent(){
		$event_id = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : 0;
		
		if( $event_id == 0 ){
			throw new Exception("Please, choose an event");
		}

		$query = "SELECT 
						e.event_id,
						e.event_name,
						e.description,
						DATE_FORMAT(e.event_datetime, '%d/%m/%Y %H:%i') as event_datetime,
						e.school_id,
						e.venue_id,
						e.category_id,
						e.distance,
						e.travel_time,
						COALESCE(GROUP_CONCAT(DISTINCT eo.person_id SEPARATOR ','),0) AS 'organiser_id',
						COALESCE(GROUP_CONCAT(DISTINCT ep.person_id SEPARATOR ','),0) AS 'participant_id',
						COALESCE(GROUP_CONCAT(DISTINCT ea.person_id SEPARATOR ','),0) AS 'attendee_id'
					FROM event e
						INNER JOIN event_organiser eo ON(eo.event_id = e.event_id AND eo.current = 1)
						LEFT JOIN event_participant ep ON(ep.event_id = e.event_id AND ep.current = 1)
						LEFT JOIN event_attendee ea ON(ea.event_id = e.event_id AND ea.current = 1)
					WHERE 
						e.current = 1 AND 
						e.event_id = ?
					GROUP BY e.event_id";
		$res = $this->db->getRow($query, [$event_id]);

		if( empty($res) ) {
			throw new Exception("Event not found!");
		}
		$organisers = explode(',', $res['organiser_id']);

		if( strpos($res['participant_id'], ',') === true ){
			$participants = explode(',', $res['participant_id']);
		} else {
			$participants = [$res['participant_id']];
		}

		if( strpos($res['attendee_id'], ',') === true ){
			$attendees = explode(',', $res['attendee_id']);
		} else {
			$attendees = [$res['attendee_id']];
		}

		
		$event = [
			'event_id' 			=> $res['event_id'],
			'event_name' 		=> $res['event_name'],
			'event_datetime' 	=> $res['event_datetime'],
			'description' 		=> $res['description'],
			'school_id' 		=> $res['school_id'],
			'venue_id' 			=> $res['venue_id'],
			'category_id' 		=> $res['category_id'],
			'organisers' 		=> explode(',', $res['organiser_id']),
			'participants' 		=> explode(',', $res['participant_id']),
			'attendees' 		=> explode(',', $res['attendee_id']),
		];

		return ['event' => $event];
	}

	/**
	 * Method responsible for saving or updating the event
	 * @return array
	 * @throws Exception
	 */
	public function saveEvent(){
		$new_event = array();
		$update_distance = true;
		$event_id 						= intval(isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : 0);
		$new_event['event_name'] 		= trim(isset($_REQUEST['event_name']) ? $_REQUEST['event_name'] : '');
		$new_event['description'] 		= trim(isset($_REQUEST['description']) ? $_REQUEST['description'] : '');
		$new_event['event_datetime'] 	= trim(isset($_REQUEST['event_datetime']) ?
			$_REQUEST['event_datetime'] : '');
		$new_event['school_id'] 		= intval(isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0);
		$new_event['venue_id'] 			= intval(isset($_REQUEST['venue_id']) ? $_REQUEST['venue_id'] : 0);
		$new_event['category_id'] 		= intval(isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : 0);
		$new_event['current'] 			= 1;
		$event_organisers 				= isset($_REQUEST['organiser_id']) ? $_REQUEST['organiser_id'] : [];
		$event_participants 			= isset($_REQUEST['participant_id']) ? $_REQUEST['participant_id'] : [];
		$event_attendees 				= isset($_REQUEST['attendee_id']) ? $_REQUEST['attendee_id'] : [];
		$new_event['event_datetime'] 	= DateTime::createFromFormat('d/m/Y H:i', $new_event['event_datetime'])
			->format('Y-m-d H:i:s');

		//double check mandatory fields
		if( !$new_event['event_datetime'] ){
			throw new Exception("Please, insert a valid date");
		}

		if( empty($new_event['event_name']) ){
			throw new Exception("Please, insert a name for the event");
		}

		if( $new_event['school_id'] == 0 ){
			throw new Exception("Please, select a school");
		}

		if( $new_event['venue_id'] == 0 ){
			throw new Exception("Please, select a venue");
		}

		if( $new_event['category_id'] == 0 ){
			throw new Exception("Please, select a category");
		}

		if( count($event_organisers) == 0 ){
			throw new Exception("Please, select an organiser");
		}

		//check if we the school or the venue has changed to update the distance
		if( $event_id > 0 ){
			$query = "SELECT school_id, venue_id FROM event WHERE event_id = ?";
			list($existent_school_id, $existent_venue_id) = $this->db->getRow($query, [$event_id]);

			if( $existent_school_id == $new_event['school_id'] AND $existent_venue_id == $new_event['venue_id'] ){
				$update_distance = false;
			}
		}

		//get the distance
		if( $update_distance ){
			$distance = $this->getDistance($new_event['school_id'], $new_event['venue_id']);
			$new_event['distance'] = $distance['travel_distance'];
			$new_event['travel_time'] = $distance['travel_duration'];
		}

		//save or insert the event
		if( $event_id > 0 ){
			$this->db->edit('event', $new_event, 'event_id = ?', [$event_id]);

			//clear the relational tables to be inserted later
			$this->db->edit('event_organiser', ['current' => 0], 'event_id = ?', [$event_id]);
			$this->db->edit('event_participant', ['current' => 0], 'event_id = ?', [$event_id]);
			$this->db->edit('event_attendee', ['current' => 0], 'event_id = ?', [$event_id]);
		} else {
			$event_id = $this->db->insert('event', $new_event);
		}

		if( $event_id == 0 ){
			throw new Exception("Failed to create an event");
		}


		//insert the updated organisers
		if( $event_organisers ){
			foreach( $_REQUEST['organiser_id'] as $person_id ){
				$new_event_organiser = [
					'event_id' 	=> $event_id,
					'person_id'	=> $person_id
				];
				$event_organiser_id = $this->db->insert('event_organiser', $new_event_organiser);
			}
		}

		//insert the updated participants
		if( $event_participants ){
			foreach( $_REQUEST['participant_id'] as $person_id ){
				$new_event_participant = [
					'event_id' 	=> $event_id,
					'person_id'	=> $person_id
				];
				$event_participant_id = $this->db->insert('event_participant', $new_event_participant);
			}
		}

		//insert the updated attendees
		if( $event_attendees ){
			foreach( $_REQUEST['attendee_id'] as $person_id ){
				$new_event_attendee = [
					'event_id' 	=> $event_id,
					'person_id'	=> $person_id
				];
				$event_attendee_id = $this->db->insert('event_attendee', $new_event_attendee);
			}
		}




		return ['event_id' => $event_id];
	}

	/**
	 * gets the distance using bing api
	 * @param int $school_id
	 * @param int $venue_id
	 * @url https://docs.microsoft.com/en-us/bingmaps/rest-services/routes/calculate-a-route
	 * @return array|bool|mixed|string
	 */
	public function getDistance( $school_id = 0, $venue_id = 0 ){

		if( $school_id == 0 ){
			$school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
		}

		if( $venue_id == 0 ){
			$venue_id = isset($_REQUEST['venue_id']) ? $_REQUEST['venue_id'] : 0;
		}

		$query = "SELECT school_address FROM school WHERE school_id = ?";
		$waypoint0 = $this->db->getOne($query, [$school_id]);

		$query = "SELECT venue_address FROM venue WHERE venue_id = ?";
		$waypoint1 = $this->db->getOne($query, [$venue_id]);


		$url = BING_URL;
		$curl = curl_init();
		$data = [
			'wp.0' 	=> $waypoint0,
			'wp.1' 	=> $waypoint1,
			'key' 	=> BING_KEY,
			'o' 	=> 'json'
		];
		$url = sprintf("%s?%s", $url, http_build_query($data));

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);

		curl_close($curl);

		$result = json_decode($result);


		if( isset($result->errorDetails) ){
			throw new Exception("API Error: " . $result->errorDetails[0]);
		}

		$travel_distance = $result->resourceSets[0]->resources[0]->travelDistance;
		$travel_duration = $result->resourceSets[0]->resources[0]->travelDuration;

		$travel_distance = isset($travel_distance) ? $travel_distance : 0;
		$travel_duration = isset($travel_duration) ? $travel_duration : 0;

		return [
			'travel_distance' => round($travel_distance, 1),
			'travel_duration' => round($travel_duration/60)
		];
	}

	/**
	 * delete event
	 * @return array
	 */
	public function deleteEvent(){
		$event_id = isset($_REQUEST['event_id']) ? $_REQUEST['event_id'] : 0;

		$this->db->edit('event', ['current' => 0], 'event_id = ?', [$event_id]);

		return ['deleted_event_id' => $event_id];
	}
}