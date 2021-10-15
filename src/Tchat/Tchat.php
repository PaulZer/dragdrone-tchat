<?php
namespace App\Tchat;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TchatMessage;

class Tchat implements MessageComponentInterface {
    protected $clients;
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->clients = [];
        $this->entityManager = $entityManager;
        echo "Tchat is up and listening for WebSocket connections from your browser !\n\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients[$conn->resourceId] = $conn;

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;

        $msgData = json_decode($msg, true);
        dump($msgData);

        switch ($msgData["action"]) {
            case 'register':
                $this->register($from, $msgData);
                break;
            case 'message':
                $this->sendMessage($from, $msgData);
                break;
        }

        foreach ($this->clients as $resourceId => $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }  
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        unset($this->clients[$conn->resourceId]);

        $userDisconnected = $this->entityManager->getRepository('App\Entity\User')->findOneBy([
            "tchat_resource_id" => $conn->resourceId
        ]);

        if(is_object($userDisconnected)){
            $userDisconnected->setTchatResourceId(null);
            $this->entityManager->persist($userDisconnected);
            $this->entityManager->flush();

            echo "User {$userDisconnected->getUsername()} has disconnected and its resourceId ({$conn->resourceId}) has been unset.\n";
        } else echo "Could nor retrieve user from the disconnecting resourceId ({$conn->resourceId}).\n";     
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    protected function register(ConnectionInterface $from, array $regData){
        $userFrom = $this->entityManager->getRepository('App\Entity\User')->findOneBy([
            "username" => $regData["username"],
            "password" => $regData["password"]
        ]);
        if(is_object($userFrom)){
            $userFrom->setTchatResourceId($from->resourceId);
            $this->entityManager->persist($userFrom);
            $this->entityManager->flush();
            echo "Successfully assigned Tchat resourceId ({$from->resourceId}) to user {$userFrom->getUsername()}.\n";
        } else echo "Invalid credentials.\n";
    }

    protected function sendMessage(ConnectionInterface $from, array $msgData){
        $userFrom = $userFrom = $this->entityManager->getRepository('App\Entity\User')->findOneBy([
            "tchat_resource_id" => $from->resourceId
        ]);
        if(is_object($userFrom)){
            echo "Username {$userFrom->getUsername()} has sent a message.\n";
            $msg = $msgData["message"];
            $to = $msgData["to"];

            $userTo = $this->entityManager->getRepository('App\Entity\User')->find($to);
            if($userTo){
                if(!(in_array('ROLE_ADMIN', $userFrom->getRoles()) || in_array('ROLE_ADMIN', $userTo->getRoles()))){
                    echo "Can't send message. Users are not allowed to talk to each other.\n";
                } else {
                    $tchatMsg = new TchatMessage($userFrom, $userTo, (string) $msgData["message"]);

                    dump($TchatMessage);

                    $userToResourceId = $userTo->getResourceId();
                    if($userToResourceId > 0){
                        $userToConn = $this->clients[$userToResourceId];
                        $userToConn->send($tchatMsg->serializeJSON());
                    }

                    //todo persist chat
                }                
            }
        } else echo "Could not retrieve sender. User with resourceId ({$from->resourceId} has not registered.\n";  
    }
}