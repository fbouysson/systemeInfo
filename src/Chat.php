<?php


namespace App;

use App\Entity\Logs;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class Chat implements MessageComponentInterface
{
    private $clients;
    private $users = [];
    private $botName = 'ChatBot';
    private $idUser = 0;
    private $defaultChannel = 'general';

    public function __construct()
    {
        $this->clients = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->users[$conn->resourceId] = [
            'connection' => $conn,
            'user' => '',
            'id' => '',
            'channels' => [],
            'img' => null
        ];

        $this->clients->attach($conn);
        //$conn->send(sprintf('New connection: Hello #%d', $conn->resourceId));
    }

    public function onClose(ConnectionInterface $closedConnection)
    {
        unset($this->users[$closedConnection->resourceId]);
        $this->clients->detach($closedConnection);
        echo sprintf('Connection #%d has disconnected\n', $closedConnection->resourceId);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $conn->send('An error has occurred: '.$e->getMessage());
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        $messageData = json_decode($message);
        if ($messageData === null) {
            return false;
        }

        $action = $messageData->action ?? 'unknown';
        $channel = $messageData->channel ?? $this->defaultChannel;
        $user = $messageData->user ?? $this->botName;
        $id = $messageData->id ?? $this->idUser;
        $message = $messageData->message ?? '';
        $img = $messageData->img ?? null;

        switch ($action) {
            case 'subscribe':
                $this->subscribeToChannel($from, $channel, $user);
                return true;
            case 'unsubscribe':
                $this->unsubscribeFromChannel($from, $channel, $user);
                return true;
            case 'message':
                return $this->sendMessageToChannel($from, $channel, $user, $message, $id,'message');
            case 'img':
                return $this->sendImgToChannel($from, $channel, $user, $img, $id);
            case 'exit':
                return $this->exit($from, $channel, $user);
            case 'welcome':
                return $this->welcome($from, $channel, $user);
            default:
                echo sprintf('Action "%s" is not supported yet!', $action);
                break;
        }
        return false;
    }

    /**
     * @param ConnectionInterface $conn
     * @param $channel
     * @param $user
     */
    private function subscribeToChannel(ConnectionInterface $conn, $channel, $user)
    {
        $this->users[$conn->resourceId]['channels'][$channel] = $channel;
        $this->sendMessageToChannel(
            $conn,
            $channel,
            $this->botName,
            $user.' a rejoin le chat',
            0,
            "co"
        );
    }

    /**
     * @param ConnectionInterface $conn
     * @param $channel
     * @param $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function unsubscribeFromChannel(ConnectionInterface $conn, $channel, $user)
    {

        $this->users[$conn->resourceId]['channels'][$channel] = $channel;
        $this->sendMessageToChannel(
            $conn,
            $channel,
            $this->botName,
            $user.' a quitté le chat',
            0,
            "deco"
        );

        if (array_key_exists($channel, $this->users[$conn->resourceId]['channels'])) {
            unset($this->users[$conn->resourceId]['channels']);
        }

    }

    /**
     * @param ConnectionInterface $conn
     * @param $channel
     * @param $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function exit(ConnectionInterface $conn, $channel, $user)
    {

        $this->users[$conn->resourceId]['channels'][$channel] = $channel;
        $this->sendMessageToChannel(
            $conn,
            $channel,
            $this->botName,
            $user,
            0,
            "exit"
        );

        if (array_key_exists($channel, $this->users[$conn->resourceId]['channels'])) {
            unset($this->users[$conn->resourceId]['channels']);
        }

    }

    /**
     * @param ConnectionInterface $conn
     * @param $channel
     * @param $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function welcome(ConnectionInterface $conn, $channel, $user)
    {

        $this->users[$conn->resourceId]['channels'][$channel] = $channel;
        $this->sendMessageToChannel(
            $conn,
            $channel,
            $this->botName,
            $user,
            0,
            "welcome"
        );

        if (array_key_exists($channel, $this->users[$conn->resourceId]['channels'])) {
            unset($this->users[$conn->resourceId]['channels']);
        }

    }

    private function sendMessageToChannel(ConnectionInterface $conn, $channel, $user, $message, $id, $action)
    {
        if (!isset($this->users[$conn->resourceId]['channels'][$channel])) {
            return false;
        }
        foreach ($this->users as $connectionId => $userConnection) {
            if (array_key_exists($channel, $userConnection['channels'])) {
                $userConnection['connection']->send(json_encode([
                    'action' => $action,
                    'channel' => $channel,
                    'user' => $user,
                    'id' => $id,
                    'message' => $message
                ]));
            }
        }
        return true;
    }

    private function sendImgToChannel(ConnectionInterface $conn, $channel, $user, $img, $id)
    {
        if (!isset($this->users[$conn->resourceId]['channels'][$channel])) {
            return false;
        }

        foreach ($this->users as $connectionId => $userConnection) {
            if (array_key_exists($channel, $userConnection['channels'])) {
                $userConnection['connection']->send(json_encode([
                    'action' => 'img',
                    'channel' => $channel,
                    'user' => $user,
                    'id' => $id,
                    'img' => $img
                ]));
            }
        }
        return true;
    }
}