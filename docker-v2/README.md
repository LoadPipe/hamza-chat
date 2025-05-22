# Docker Setup for HNS Chat

This directory contains the Docker configuration for setting up the HNS Chat application, which consists of three main components:
1. Web Client (PHP/Apache)
2. WebSocket Server (Node.js)
3. MySQL Database

## Prerequisites

- Docker
- Docker Compose

## Setup Instructions

1. Build and start the containers:
```bash
docker compose up -d
```

NOTE: websocket may not start properly, to restart, do (2) first, then 

2a. Import the init.sql script:
```bash
docker exec -i docker-v2-db-1 mysql -u root -proot-password < init.sql
```

2b. start websocket again:
```bash
docker compose up websocket -d
```

3a. Create a default channel:
NOTE: when it asks for domain, enter: 'hamzamarket' - this is a test domain created in init.sql
```bash
docker exec -i docker-v2-websocket-1 node create-channel.js
```

3b.
```bash
docker exec -i docker-v2-db-1 mysql -u root -proot-password hnschat -e "UPDATE channels set slds=1, activated=1, hidden=0;"
```

4. To get a test chat going in the hamzamarket channel (created in init.sql) without varo:
4a. visit http://localhost (you may need to clear out storage: chrome devtools -> Application -> Local storage -> http://localhost - just select session and delete it - then refresh page)

4b. open chrome devtools -> Application -> Local storage -> http://localhost

4c. grab the value in "session"

4d. Check if session is in db:
```bash
docker exec -i docker-v2-db-1 mysql -u root -proot-password hnschat -e "SELECT * FROM sessions WHERE id='the_value_in_c';"
```

4e. if (d) is ok, then update the domain session:
```bash
docker exec -i docker-v2-db-1 mysql -u root -proot-password hnschat -e "UPDATE domains set session='the_value_in_c' WHERE domain='hamzamarket'"
```

4d. Check if domain session is updated in db (a test domain was created in init.sql):
```bash
docker exec -i docker-v2-db-1 mysql -u root -proot-password hnschat -e "SELECT session FROM domains WHERE domain='hamzamarket';"
```

4e. visit http://localhost - if all works, the chat window should display with the channel you created in (3)

4f. sometimes... channels may not display the created channel - potentially because the websocket server died... you'll need to run (2b) again

5. (optional) Create a second users with different domain - yogibear (created already in init.sql)
- Repeat steps in (4), BUT, in a different browser session.
- NOTE 1: In step (4c) this session value will be new and different from the other one
- NOTE 2: In steps (4e) and (4d) - the domain is 'yogibear', so you'll need to replace 'hamzamarket' with 'yogibear'.

If done correctly, you should now be chatting in the channel created in (3), with 2 "people".  You can also use private chat to chat with the person created in (4).


### The following services will be available:
   - Web client: http://localhost:80
   - WebSocket server: ws://localhost:8080
   - MySQL database: localhost:3306


## Configuration

- Database credentials:
  - Database: hnschat
  - Username: hnschat
  - Password: hnschat-password
  - Root password: root-password

## Services

1. **Web Client (Apache + PHP)**
   - Serves the web application
   - Port: 80
   - Source: hamza-chat/chat-client

2. **WebSocket Server (Node.js)**
   - Handles real-time communication
   - Port: 8080
   - Source: hamza-chat/chat-server

3. **MySQL Database**
   - Stores application data
   - Port: 3306
   - Schema: hamza-chat/chat-server/hnschat.sql

## Volumes

- `mysql_data`: Persistent storage for MySQL data
- Web client files are mounted from `hamza-chat/chat-client`
- WebSocket server files are mounted from `hamza-chat/chat-server`

## Troubleshooting

1. If the web client is not accessible:
   - Check if all containers are running: `docker compose ps`
   - View logs: `docker compose logs web`

2. If the WebSocket server is not working:
   - Check logs: `docker compose logs websocket`
   - Verify the server configuration in `hamza-chat/chat-server/config.json`

3. If database connection fails:
   - Check logs: `docker compose logs db`
   - Verify database initialization: `docker compose exec db mysql -u root -p` 