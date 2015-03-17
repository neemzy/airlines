<?php

namespace Airlines\AppBundle\Factory;

use SocketIO\Emitter;
use Symfony\Component\Debug\Exception\ContextErrorException;

class SocketEmitterFactory
{
    /**
     * @param string $host
     * @param string $port
     *
     * @return Emitter
     */
    public static function createSocketEmitter($host, $port)
    {
        $emitter = null;

        try {
            $emitter = new Emitter(false, [$host, $port]);
        } catch (ContextErrorException $e) {
            // The socket server could not be reached, just ignore it
        }

        return $emitter;
    }
}
