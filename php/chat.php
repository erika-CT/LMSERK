<?php
namespace Erika;

include './db/conn.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $usernames;
    protected $db;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->usernames = [];
        $this->db = (new \Database())->connect(); // Conectar a la base de datos
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->usernames[$conn->resourceId] = null;
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Decodifica el mensaje JSON
        $data = json_decode($msg, true);
        $action = $data['action'] ?? '';
        echo $action . "\n";
        switch ($action) {
            case 'connect':
                $username = $data['username'] ?? '';
                $this->usernames[$from->resourceId] = $username;
                $from->send(json_encode(['status' => 'success', 'message' => "You are now connected as {$username}."]));
                $this->notifyNewUser($from, $username);
                $this->updateUserStatus($username, true); // Marca al usuario como conectado
                break;
                
            case 'message':
                $recipient = $data['recipient'] ?? '';
                $message = $data['message'] ?? '';
                $this->sendMessage($from, $recipient, $message);
                break;

            default:
                $from->send(json_encode(['status' => 'error', 'message' => 'Unknown action.']));
                break;
        }
    }

    protected function sendMessage(ConnectionInterface $from, $recipient, $message) {
        foreach ($this->clients as $client) {
            if ($this->usernames[$client->resourceId] === $recipient) {
                $client->send(json_encode([
                    'action' => 'message',
                    'from' => $this->usernames[$from->resourceId],
                    'message' => $message
                ]));
                return;
            }
        }
        $from->send(json_encode(['status' => 'error', 'message' => "User {$recipient} not found."]));
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        $username = $this->usernames[$conn->resourceId];
        unset($this->usernames[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n" . $username;
        $this->notifyUserDisconnected($username);
        $this->updateUserStatus($username, false); // Marca al usuario como desconectado
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    protected function notifyNewUser(ConnectionInterface $newConn, $newUsername) {
        echo "notificando\n";
        foreach ($this->clients as $client) {
            if ($client !== $newConn) {
                $client->send(json_encode(['action' => 'login', 'username' => $newUsername]));
            }
        }
    }
    
    protected function notifyUserDisconnected($username) {
        echo "notificando\n";
        foreach ($this->clients as $client) {
            $client->send(json_encode(['action' => 'logout', 'username' => $username]));
        }
    }

    protected function updateUserStatus($username, $isConnected) {
        echo $username;
        if($isConnected) {
            // Intenta insertar el usuario; si ya existe, no hace nada
            $sql = "INSERT INTO usuarios_conectados (id_usuario) VALUES(:username) ON CONFLICT (id_usuario) DO NOTHING"; 
        } else {
            // Eliminar el usuario de la tabla
            $sql = "DELETE FROM usuarios_conectados WHERE id_usuario = :username"; 
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
    }
    
}
