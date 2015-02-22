<?php
/**
 * TestableCollection
 *
 * @author Kachit
 */
namespace Kachit\Collection\Testable;

use Kachit\Collection\Collection as BaseCollection;
use Kachit\Collection\Exception;

class Collection extends BaseCollection {

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