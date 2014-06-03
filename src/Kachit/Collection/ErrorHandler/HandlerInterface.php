<?php
/**
 * HandlerInterface
 *
 * @author Kachit
 */
namespace Kachit\Collection\ErrorHandler;


interface HandlerInterface {

    /**
     * Handle error
     *
     * @param string $message
     */
    public function handle($message);
} 