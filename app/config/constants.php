<?php

DEFINE("APP_NAME", "Sentral - Developer Challenge Exercise");
define("ROOT", __DIR__ . "/../../");
define("PUBLIC_PATH", "/");
define("APP_PATH", ROOT . "app/" );
define("VIEW_PATH", APP_PATH . "view/" );

define("PERSON_TYPE_ORGANISER", "1");
define("PERSON_TYPE_STAFF", 	"2");
define("PERSON_TYPE_PARENT", 	"3");
define("PERSON_TYPE_VOLUNTEER", "4");
define("PERSON_TYPE_OTHER", 	"5");

define("PERSON_TYPE_NON_ORGANISERS", "'" .PERSON_TYPE_STAFF . "','" . PERSON_TYPE_PARENT . "','" .
	PERSON_TYPE_VOLUNTEER . "','" . PERSON_TYPE_OTHER . "'");

define("BING_URL", "http://dev.virtualearth.net/REST/v1/Routes/");
define("BING_APP", "sentralrafagarcia");
define("BING_KEY", "AotD7ToXSAJKyoy0YZbknGXSClJcm-HYGOi_DP-uOEkhke0JB4QVyax8J5iPAuDy");