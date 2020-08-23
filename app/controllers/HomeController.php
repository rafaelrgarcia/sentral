<?php


namespace Sentral\Challenge;

use Exception;

class HomeController extends Model
{

	/**
	 * Method responsible for the main page creation
	 */
	public function home(){
		$options = array();

		$oSchool = new SchoolController();
		$schools = $oSchool->getSchools();

		$oVenue = new VenueController();
		$venues = $oVenue->getVenues();

		$oCategory = new CategoryController();
		$categories = $oCategory->getCategories();

		$oPerson = new PersonController();
		$organisers = $oPerson->getPersons( PERSON_TYPE_ORGANISER);

		$oPerson = new PersonController();
		$non_organisers = $oPerson->getPersons( PERSON_TYPE_NON_ORGANISERS);

		$options = array_merge(
			$schools,
			$venues,
			$categories,
			['organisers' => $organisers['persons']],
			['non_organisers' => $non_organisers['persons']]
		);

		include VIEW_PATH . 'home.php';
	}
}