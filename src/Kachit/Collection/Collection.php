<?php
/**
 * Objects collection
 *
 * @author Kachit
 */
namespace Kachit\Collection;

class Collection implements \IteratorAggregate, \JsonSerializable {

    const METHOD_ADD_OBJECT = 'addObject';
    const METHOD_SET_OBJECT = 'setObject';

    /**
     * @var ItemInterface[]
     */
    protected $data = [];

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
    public function __construct(array $data = []) {
        if (!empty($this->data)) {
            $this->fillFromArray($data);
        }
    }

    /**
     * Get iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->data);
    }

    /**
     * Json serialize
     *
     * @return ItemInterface[]
     */
    public function jsonSerialize() {
        return $this->toArray();
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
            $this->handleError('Object with index "' . $index .'" not exists in collection');
        }
        return $this->data[$index];
    }

    /**
     * Get cloned object
     *
     * @param $index
     * @return ItemInterface
     */
    public function cloneObject($index) {
        return clone $this->getObject($index);
    }

    /**
     * Get first object
     *
     * @return ItemInterface
     */
    public function getFirstObject() {
        return array_shift($this->toArray());
    }

    /**
     * Get last object
     *
     * @return ItemInterface
     */
    public function getLastObject() {
        return array_pop($this->toArray());
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
            $this->handleError('Object with index "' . $index .'" not exists in collection');
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
            $this->handleError('Object with index "' . $object->getId() .'" all ready exists in collection');
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
            $this->handleError('Method "' . $method .'" not available for add objects');
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
     * Return new collection which has
     *
     * @param array $keys
     * @return static|Collection|ItemInterface[]
     */
    public function extract(array $keys) {
        if(empty($keys)) {
            $this->handleError('Indexes list is not be empty');
        }
        $diff = array_diff($keys, $this->getIds());
        if ($diff) {
            $this->handleError('This indexes "' . implode(', ', $diff) . '" is not exists in collection');
        }
        /* @var Collection $collection */
        $collection = new static();
        foreach ($keys as $index) {
            $collection->addObject($this->getObject($index));
        }
        return $collection;
    }

    /**
     * Return new collection which has
     *
     * @param int $offset
     * @param int $limit
     * @return static|Collection|ItemInterface[]
     */
    public function slice($offset, $limit = null) {
        return new static(array_slice($this->data, $offset, $limit, true));
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
        $data = [];
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

    /**
     * Handle collection error (throw Exception by default)
     *
     * @param string $message
     * @throws Exception
     */
    protected function handleError($message) {
        throw new Exception($message);
    }
} 