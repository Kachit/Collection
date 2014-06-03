<?php
/**
 * Collection exception
 *
 * @author Kachit
 */
namespace Kachit\Collection\ErrorHandler;

use Kachit\Collection\Exception;

class HandlerException implements HandlerInterface {

    /**
     * Handle error
     *
     * @param string $message
     * @throws \Kachit\Collection\Exception
     */
    public function handle($message) {
        throw new Exception($message);
    }
} 