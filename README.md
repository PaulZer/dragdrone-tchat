Tchat project:
	- Allows real-time tchatting between database users in a Symfony 5 app, using Ratchet php web sockets.

dependancies:
	- php7.2.5 or higher (and extension pdo_mysql.dll enabled)
	- composer
	- a mysql server (online for test): see database connection information in .env file.

User & roles:
	- 2 roles in the application (user and admin)
	- Restrictions are: users can talk to admins but not to each other

Startup:
	- install composer dependancies (includes ratchet) (php bin/console composer:install)
	- start built-in Symfony web server (php bin/console server:start)
	- on another terminal, start the WebSockets server (php bin/console app:start-tchat): messages from users will appear in real-time.
	- login and that's it !(http://localhost:8000/login) you can live tchat with several users.

--/ MAIL MATHIEU /--

Bonjour Mathieu,

Comme convenu je reviens vers vous concernant mon avancement.

J'ai initialisé un repo GitHub sur mon profil (lien ci-dessous). Toutes les informations nécessaires au démarrage et fonctionnement du projet sont renseignées dans le fichier README.md à la racine du projet.

J'ai choisi d'utiliser des websockets pour une connexion bi-directionnelle en temps réel entre plusieurs clients web.
J'ai intégré ce serveur de websockets à une commande Symfony, ce qui permet d'accéder aux entités comme l'utilisateur au sein du serveur webSocket, faire le lien entre le numéro associé (resourceId) et l'id de l'utilisateur en BDD. et ainsi de faire les contrôles nécessaires à chaque envoi de message.

 Pour un démarrage plus rapide, la base de donnée est hebergée à distance donc vous n'avez pas besoin d'apache/mysql/phpmyadmin (extension php pdo_mysql.dll requise)

 Pour les comptes user/admin en BDD: Les identifiants sont pour chaque compte comme suit:
 	- rôle utilisateur: user1, user2, user3... mdp: toto
 	- rôle admin: admin1, admin2... mdp: tata

 Pour tester, ouvrez deux onglets sur des navigateurs différents (ou navigation privée), identifiez-vous avec les comptes que vous voulez. Le terminal du serveur de websocket log les différents évènements, et les événements côté client apparaissent dans la console javascript.
 



