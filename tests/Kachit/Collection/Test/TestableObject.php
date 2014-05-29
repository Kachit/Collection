<?php
/**
 * TestableObject
 *
 * @author antoxa <kornilov@realweb.ru>
 * @package Kachit\Collection\Test
 */
namespace Kachit\Collection\Test;

use Kachit\Collection\ItemInterface;

class TestableObject implements ItemInterface {

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * Get Id
     *
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set Id
     *
     * @param mixed $id
     * @return $this
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

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