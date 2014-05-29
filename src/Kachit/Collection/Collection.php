<?php
/**
 * Collection
 *
 * @author Kachit
 */
namespace Kachit\Collection;

class Collection implements \IteratorAggregate {

    const METHOD_ADD_OBJECT = 'addObject';
    const METHOD_SET_OBJECT = 'setObject';

    /**
     * @var ItemInterface[]
     */
    protected $data = array();

    /**
     * @var array
     */
    protected $methodsForAddObject = array(
        self::METHOD_ADD_OBJECT,
        self::METHOD_SET_OBJECT,
    );

    /**
     * Init collection
     *
     * @param array $data
     */
    public function __construct(array $data = array()) {
        if (!empty($this->data)) {
            $this->fillFromArray($data);
        }
    }

    /**
     * getIterator
     *
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

    /**
     * To array
     *
     * @return ItemInterface[]
     */
    public function toArray() {
        return $this->data;
    }

    /**
     * Get object
     *
     * @param mixed $index
     * @return ItemInterface
     * @throws Exception
     */
    public function getObject($index) {
        if (!$this->hasObject($index)) {
            throw new Exception('Object with index "' . $index .'" not exists in collection');
        }
        return $this->data[$index];
    }

    /**
     * Has object
     *
     * @param mixed $index
     * @return bool
     */
    public function hasObject($index) {
        return isset($this->data[$index]);
    }

    /**
     * Delete object
     *
     * @param mixed $index
     * @return $this
     * @throws Exception
     */
    public function deleteObject($index) {
        if (!$this->hasObject($index)) {
            throw new Exception('Object with index "' . $index .'" not exists in collection');
        }
        unset($this->data[$index]);
        return $this;
    }

    /**
     * Add object with rewrite
     *
     * @param ItemInterface $object
     * @return $this
     */
    public function setObject(ItemInterface $object) {
        $this->data[$object->getId()] = $object;
        return $this;
    }

    /**
     * Add object with check unique
     *
     * @param ItemInterface $object
     * @return $this
     * @throws Exception
     */
    public function addObject(ItemInterface $object) {
        if ($this->hasObject($object->getId())) {
            throw new Exception('Object with index "' . $object->getId() .'" all ready exists in collection');
        }
        return $this->setObject($object);
    }

    /**
     * Add set of objects in the collection
     *
     * @param array $objects
     * @param string $method
     * @return $this
     * @throws Exception
     */
    public function fillFromArray(array $objects, $method = self::METHOD_ADD_OBJECT) {
        if (!in_array($method, $this->getMethodsForAddObject())) {
            throw new Exception('Method "' . $method .'" not exists in collection');
        }
        if (!empty($objects)) {
            foreach ($objects as $item) {
                $this->$method($item);
            }
        }
        return $this;
    }

    /**
     * Count objects
     *
     * @return int
     */
    public function count() {
        return count($this->data);
    }

    /**
     * Check is empty
     *
     * @return bool
     */
    public function isEmpty() {
        return empty($this->data);
    }

    /**
     * Clear objects
     *
     * @return $this
     */
    public function clear() {
        $this->data = [];
        return $this;
    }

    /**
     * Get object ids
     *
     * @return array
     */
    public function getIds() {
        return array_keys($this->data);
    }

    /**
     * Append collection
     *
     * @param Collection $otherCollection
     * @return $this
     */
    public function append(Collection $otherCollection) {
        return $this->fillFromArray($otherCollection->toArray());
    }

    /**
     * Merge with another collection
     *
     * @param Collection $otherCollection
     * @return $this
     */
    public function merge(Collection $otherCollection) {
        return $this->fillFromArray($otherCollection->toArray(), 'setObject');
    }

    /**
     * Clone collection
     */
    public function __clone() {
        $data = array();
        foreach ($this->data as $index => $object) {
            $newObject = clone $object;
            $data[$index] = $newObject;
        }
        $this->data = $data;
    }

    /**
     * Get MethodsForAddObject
     *
     * @return array
     */
    protected function getMethodsForAddObject() {
        return $this->methodsForAddObject;
    }
} 