<?php

require dirname(__DIR__) . '/vendor/autoload.php'; // Ajusta la ruta si es necesario

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Erika\Chat; // Asegúrate de que la clase Chat esté correctamente cargada

echo "Socket iniciado\n"; // Mensaje para verificar que el script está ejecutándose

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    2512, //Puerto que escucha el socket
    '0.0.0.0'
);

echo "servidor en el puerto 2512\n"; // Mensaje para verificar que el servidor está escuchando

$server->run(); //para iniciar el socket o lo hace desde la terminal de visual o una ventana cmd
