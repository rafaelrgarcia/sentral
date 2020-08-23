# sentral/challenge

This minimalist APP created by Rafael Garcia as an exercise for a code challenge for the company Sentral.

In here, I tried to describe how a minimum app would be in my point of view, regarding the common standards of a MVC OOP 
built in PHP design pattern and based on my previous experiences with non-framework applications.

##Assumptions

- The venues can by added automatically, but also reused for different events
- Schools would be pre-inserted from other sources, no need to add a a "add new"


##Things that could be improved with extra time

- A proper ACL with a middleware class, user permissions and Auth header on the api requests
- More modern front end structure (eg. Vue.js, React or Angular) - From my understanding, the purpose of this test is 
to my backend skills, so I focused my effort on that.
- I would make a better way to manage the model part, specially on the update function adding a better way to build 
the where statement
- a proper routing to call APIs avoiding sending controllers/methods via $_REQUEST
- call index.php/api.php via httaccess or NGINX Directives to facilitate page routing
- get the attendees from the participants
- abstract EventController/getDistance to be more generic or/and create a controller specifically for the api
- show maps with distance once they select the 2 way points (now it is doing only after saving)
- get address from api when inserting a new venue
- check if venue, category, organiser already exists before insert and maintain inserted resources

##Installation

###Requirements
- Apache/Nginx 
- MySQL
- PHP 7.x
- composer

###Steps
- Download application via git or zip
- create database from /database/dump.sql
- create .env from the /.env.example updating it with your database details
- point your document root to /public folder
- open the terminal in your application folder and run composer install

##Folders and files description
| Folder            | Description |
| -------------     | ------------------------------------------------------- |
| `app/`            | Folder containing all the PHP classes                   |
|  --`config/`      | files with the app configuration - eg. constants        |
|  --`controllers/` | contains all the controller classes of the app          |
|  --`model/`       | contains all the model classes of the app together with the database connection and basic functions         |
|  --`App.php`      | Main class of the application - this class will call the requested method, checking if it is a page or api and capturing exceptions from the lower level         |
| `database/`       | database files                                          |
| `public/`         | public documents - eg. css, js, imgs                    |
| `vendor/`         | third part libraries from the composer and autoload     |
| `.env`            | environment variables - eg. database user     |


