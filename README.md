# Map-Ratings
Map-Ratings is a PHP Based Call of Duty: Modern Warfare web application for viewing the most popular maps based on B3's mapvote system.

### Notice
The current state of the tool is complete; however, users can user and modify the tool. A stable version of the Map-Ratings tool will release [here](https://github.com/MichaelHillcox/Map-Ratings/releases) when available.

## Requirements
- B3 running on your server
- B3 running the `map stats` plugin
- MySQL / MariaDB
    - B3 writing the map stats to a map stats table
- PHP5.6 -> PHP 7.x

## To Do
### Version 1.0
- [ ] Add back image support
    - Using a custom solution instead of gametracker
- [ ] Add back sorting by popularity, likes, and dislikes
- [ ] Add an update checker

## Installation
Begin by cloning the repository:
```
git clone https://github.com/MichaelHillcox/Map-Ratings.git
```
or downloading the [zip](https://github.com/MichaelHillcox/Map-Ratings/archive/master.zip) and place the contents inside the web applications working directory.

Once the project is inside the desired directory on the web server, navigate into the `app/` folder and rename the `config.php.tmp` file to `config.php`. After renaming the file go through the commented sections inside the `config.php` file and fill in the correct data for the specific use case.

When the `config.php` is setup correctly the program is ready for use.

## Preview
![preview](https://i.imgur.com/4EG1ory.png)
