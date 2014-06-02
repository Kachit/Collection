<?php
/**
 * TestableCollection
 *
 * @author Kachit
 */
namespace Kachit\Collection\Test;

use Kachit\Collection\Collection;
use Kachit\Collection\Exception;

class TestableCollection extends Collection {

    /**
     * Get MethodsForAddObject
     *
     * @return array
     */
    public function getMethodsForAddObject() {
        return parent::getMethodsForAddObject();
    }

    /**
     * Handle collection error (throw Exception by default)
     *
     * @param string $message
     * @throws Exception
     */
    public function handleError($message) {
        parent::handleError($message);
    }
} 