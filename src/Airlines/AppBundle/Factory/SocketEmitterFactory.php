<?php

namespace Airlines\AppBundle\Factory;

use Symfony\Component\Debug\Exception\ContextErrorException;
use SocketIO\Emitter;

class SocketEmitterFactory
{
    /**
     * Crafts socket emitter instance
     *
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
