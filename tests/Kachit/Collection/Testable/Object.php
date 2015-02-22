<?php
/**
 * TestableObject
 *
 * @author antoxa <kornilov@realweb.ru>
 * @package Kachit\Collection\Test
 */
namespace Kachit\Collection\Testable;

use Kachit\Collection\ItemInterface;
use Kachit\Collection\ItemTrait;

class Object implements ItemInterface {

    use ItemTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * Get Name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
} 