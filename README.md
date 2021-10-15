# Tchat project:

- Allows real-time tchatting between database users in a Symfony 5 app, using [Ratchet php web sockets](http://socketo.me/)
- User & roles:
	- 2 roles in the application (user and admin)
	- Restrictions are: users can talk to admins but not to each other
- Currently under development:
	- Tchat user interface in `templates/home.html.twig`
	- TchatMessages persistence
	- tchatHistory page (route will include ids of 2 users to retrieve their conversation history)

# dependancies:

- php7.2.5 or higher (and extension pdo_mysql.dll enabled)
- composer (ratchet depends on composer)
- a mysql server (hosted online - hope won't be offline too soon): see database connection information in .env file.

# Startup:

- Install composer dependancies (includes ratchet): `php bin/console composer:install`
- Start built-in Symfony web server: `symfony server:start` (or with php `php -S localhost:8000`)
- On another terminal, start the WebSockets server: `php bin/console app:start-tchat`. messages from users will appear in real-time.
- login and that's it ! Go to `http://localhost:8000/login`: you can live tchat with several logged-in Symfony users.