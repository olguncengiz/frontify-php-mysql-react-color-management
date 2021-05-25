# RESTful API for Frontify Color Management Dashboard with PHP, MySQL and React JS

## Task Description

_"Since one of your main Tasks at Frontify will be consuming and building APIs, we want to see how you would build and consume your API. The task for you is to build a simple API in PHP which manages color definitions. Each color has a name and a hex value. Your API should be responsible for the following things: list, create, and also delete colors. Part of your task is to also build a Frontend in React which interacts with this API."_

## Getting Started

For this project, I used two docker containers; one for MySQL database and one for PHP + React services. The structure of the system is very simple:

- MySQL: Running on docker container and shares the same network with localhost. MySQL is accessible on port :3306.
- PHP + React: Running on docker container and shares the same network with localhost. PHP Restful API runs on port :8081.

## Project Layout

The project uses the following layout:
 
```
.
├── api                  API related files' location
│   ├── colors.php       The API server application responsible from GET, POST and DELETE requests
│   └── functions.php    Helper functions for HTTP response code mapping
├── index.html           Landing page of React front-end with dashboard
├── styles.css           CSS file for HTML styling
```

## Deployment

The application can be run in a docker container or localhost. You can copy the files in any folder and in the root folder of application, run `php -S 127.0.0.1:80801` to start the Restful API and React front-end. 

## Assumptions

There must be a up&running MySQL DB and the connection information must be updated on `colors.php` file. If you have a MySQL docker running on localhost:3306, the code should work just fine with no or very few changes.

## Development Steps

### Database Creation
- I created a MySQL docker container from `mysql:latest` with root password `pass` using the command below:

```
docker run --net=host -e MYSQL_ROOT_PASSWORD=pass -d c0cdc95609f1
```

- Then I created `colordb` database and `colors` table with unique name and unique hexcode using the SQL script below:

```
create database colordb;
use colordb;
CREATE TABLE `colors` (
  `name` varchar(100) NOT NULL UNIQUE,
  `hexcode` varchar(7) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```

### PHP Restful API Server Creation
- I created a PHP docker container from `php:latest` using the command below:

```
docker run --net=host -d a868b8546a9e
```

- Then I created `colors.php` file for API operations (GET, POST and DELETE method handlers).
- At this point, I had problems with mysqli driver, I mentioned these problems below under **Time Consumption of Development Steps & Problems Faced** section.
- I tested API endpoints using Postman and I proceeded with React frontend implementation only after I was confident with endpoints working fine.

### Frontend Server Creation
- After I got a working a backend Restful server, I added `index.html` on the project root folder with React code inside.
- The rest is improvements to code, like make-up changes, CSS styling, error handling and standard return values from API endpoints.
- At first, I used `nano` to write the code from inside the PHP docker container through /bin/bash but after a while, it got harder to use `nano` as an editor. I committed existing PHP docker container as a new image, then created a new container from that image with volume mapped to my localhost folder, so that I can edit files in the container using Sublime on my localhost. That saved lots of time while working on CSS and React.

## Open Points

Due to time and complexity limitations, I intentionally left some aspects unimplemented and listed them below as open points in the system.

- There is no authentication implemented for API endpoints.
- GUI is not perfect, needs more time to make it look better with CSS.
- PHP (Back-end) and Reach (Front-end) are running on the same docker container, there is no separation.
- Tests are not implemented due to time limitations.
- Warnings and other errors are not properly handled due to time limitations.

## Time Consumption of Development Steps & Problems Faced
- Creating MySQL docker container and creating DB & table => Very easy and fast
- Having PHP connect to MySQL using pdo-mysql or php-mysqli extensions => Took most of my time, about a couple of hours
- Having MySQL docker container be accessible to PHP docker container => Took around half an hour
- Learning curve for React and updating code for improvements => Fairly easy and fast
- Adding CSS, polishing up the UI, showing errors and restricting user input => Fairly easy and straight forward
