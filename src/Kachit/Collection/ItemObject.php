<?php
/**
 * ItemObject
 *
 * @author Kachit
 */
namespace Kachit\Collection;

class ItemObject implements ItemInterface {

    /**
     * @var mixed
     */
    protected $id;

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
} 