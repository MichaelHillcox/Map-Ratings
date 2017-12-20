# Map-Ratings
PHP Based CoD4 web app for seeing the most popular maps based on B3's mapvote system

### Notice
Currently I am not officially saying this product is finished. You can use it and play around with it but by no means is it yet completed. I will release a stable version [here](https://github.com/MichaelHillcox/Map-Ratings/releases) when one is available

# Requirements
- B3 running on your server.
- B3 running the `map stats` plugin
- MySQL / MariaDB
    - B3 writing the map stats to a map stats table
- PHP5.6 -> PHP 7.x

# To Do
#### Version 1.0
- [ ] Add back image support
    - This is going to be using a custom solution instead of gametracker
- [ ] Add back sorting by popularity, likes, dislikes
- [ ] Add update checker

# How to use
Either use
```
git clone https://github.com/MichaelHillcox/Map-Ratings.git
```
or download the [zip](https://github.com/MichaelHillcox/Map-Ratings/archive/master.zip) of the project to get started.

Once you have the project inside your desired directory on your web server you'll want to go into `app/config.php.tmp` and rename it to `config.php`.
After you have renamed the file you will want to go through the commented sections inside the
`config.php` and fill in the correct data for your use case.

Once that's done you should be good too go.

# Preview
![preview](https://i.imgur.com/4EG1ory.png)
