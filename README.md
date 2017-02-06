# Auto Recommend Content Tags (Thru Apache Stanbol)

Drupal 8 + [Apache Stanbol](https://stanbol.apache.org/) +
[Socket.IO](http://socket.io/) = Auto Recommend Content Tags!

This module utilizes Apache Stanbol to suggest tags, or search keywords while
an editor is typing or creating new content.
  
It hooks with Apache Stanbol via a web socket to provide real-time tags 
recommendations when adding/editing content.

#### Video Demo:

See this video for a demo: [https://www.youtube.com/watch?v=ry0accNDhnc]
(https://www.youtube.com/watch?v=ry0accNDhnc)  
_This video has been made after installing this module with 
[Sportsleague distribution](https://www.drupal.org/sandbox/jain_deepak/2732165),
 and using the [Apache Stanbol and Node.js Docker containers]
(https://hub.docker.com/r/vardot/stanbol-nodejs/)._

# Dependencies:

- You will need to setup an Apache Stanbol instance with a web socket for this
 module to work. For convenience, **we recommend you use this Docker image**:
 [https://hub.docker.com/r/vardot/stanbol-nodejs/]
(https://hub.docker.com/r/vardot/stanbol-nodejs/) to easily setup Apache Stanbol
with Node.js Socket.IO.  

# Installing Apache Stanbol + Socket.IO through Docker
1. Make sure you have Docker and Docker Compose installed.
2. Clone the Apache Stanbol with Node.js Socket.IO Docker image files: `git clone https://github.com/Vardot/docker-stanbol-nodejs.git`.
3. Run this command `docker-compose up -d` to run the container in the background.
4. Your Apache Stanbol Socket URL will bt: `http://MYHOSTNAME:8071`. Put that in the module configuration.
