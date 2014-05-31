<?php
/**
 * CollectionTest
 *
 * @author antoxa <kornilov@realweb.ru>
 */
namespace Kachit\Collection\Test;

use Kachit\Collection\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Collection|TestableObject[]
     */
    protected $testable;

    /**
     * Init
     */
    protected function setUp() {
        $this->testable = new Collection();
        $this->fillCollection();
    }

    /**
     * RTFN
     */
    public function testFilledCollection() {
        $this->assertEquals(10, $this->testable->count());
    }

    /**
     * RTFN
     */
    public function testGetIds() {
        $result = $this->testable->getIds();
        $this->assertTrue(is_array($result));
        $this->assertEquals($this->testable->count(), count($result));
    }

    /**
     * RTFN
     */
    public function testGetIterator() {
        $result = $this->testable->getIterator();
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('ArrayIterator', $result);
    }

    /**
     * RTFN
     */
    public function testGetObject() {
        $result = $this->testable->getObject(1);
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('Kachit\Collection\ItemInterface', $result);
    }

    /**
     * RTFN
     */
    public function testClearCollection() {
        $this->testable->clear();
        $this->assertTrue($this->testable->isEmpty());
    }

    /**
     * RTFN
     */
    public function testCloneCollection() {
        $collection = clone $this->testable;
        $this->assertFalse($collection === $this->testable);
    }

    /**
     * RTFN
     */
    public function testAppendCollection() {
        $collection = $this->getCollectionForAppend();
        $this->testable->append($collection);
        $this->assertEquals(16, $this->testable->count());
        foreach ($collection as $object) {
            $this->assertTrue($this->testable->hasObject($object->getId()));
        }
    }

    /**
     * RTFN
     */
    public function testMergeCollection() {
        $collection = $this->getCollectionForMerge();
        $this->testable->merge($collection);
        $this->assertEquals(12, $this->testable->count());
        foreach ($collection as $object) {
            $this->assertTrue($this->testable->hasObject($object->getId()));
        }
    }

    /**
     * RTFN
     * @expectedException \Kachit\Collection\Exception
     * @expectedExceptionMessage Object with index "7" all ready exists in collection
     */
    public function testMergeCollectionWithExistingIndex() {
        $collection = $this->getCollectionForMerge();
        $this->testable->append($collection);
    }

    /**
     * RTFN
     */
    public function testAddObject() {
        $object = $this->getTestableObject();
        $object->setId('foo');
        $this->testable->addObject($object);
        $this->assertEquals(11, $this->testable->count());
        $result = $this->testable->getObject('foo');
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('Kachit\Collection\ItemInterface', $result);
    }

    /**
     * RTFN
     */
    public function testDeleteObject() {
        $this->testable->deleteObject(1);
        $this->assertEquals(9, $this->testable->count());
    }

    /**
     * RTFN
     * @expectedException \Kachit\Collection\Exception
     * @expectedExceptionMessage Object with index "100" not exists in collection
     */
    public function testDeleteUnavailableObject() {
        $this->testable->deleteObject(100);
    }

    /**
     * RTFN
     * @expectedException \Kachit\Collection\Exception
     * @expectedExceptionMessage Object with index "1" all ready exists in collection
     */
    public function testAddObjectWithExistingIndex() {
        $object = $this->getTestableObject();
        $object->setId(1);
        $this->testable->addObject($object);
    }

    /**
     * RTFN
     */
    public function testSetObject() {
        $object = $this->getTestableObject();
        $object->setId('foo');
        $this->testable->setObject($object);
        $this->assertEquals(11, $this->testable->count());
        $result = $this->testable->getObject('foo');
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('Kachit\Collection\ItemInterface', $result);
    }

    /**
     * RTFN
     */
    public function testSetWithExistsIndex() {
        $object = $this->getTestableObject();
        $object->setName('bar');
        $object->setId(1);
        $this->testable->setObject($object);
        $this->assertEquals(10, $this->testable->count());
        $this->assertEquals('bar', $this->testable->getObject(1)->getName());
    }

    /**
     * RTFN
     *
     * @expectedException \Kachit\Collection\Exception
     * @expectedExceptionMessage Object with index "100" not exists in collection
     */
    public function testGetObjectUnavailable() {
        $this->testable->getObject(100);
    }

    /**
     * RTFN
     *
     * @expectedException \Kachit\Collection\Exception
     * @expectedExceptionMessage Method "fake" not available for add objects
     */
    public function testFillFromArrayNotAvailableMethod() {
        $array = $this->getCollectionForMerge()->toArray();
        $this->testable->fillFromArray($array, 'fake');
    }

    /**
     * Fill collection
     *
     * @throws \Kachit\Collection\Exception
     */
    protected function fillCollection() {
        for ($i = 1; $i <= 10; $i++) {
            $object = $this->getTestableObject();
            $object->setId($i);
            $this->testable->addObject($object);
        }
    }

    /**
     * RTFN
     *
     * @return Collection|TestableObject[]
     */
    protected function getCollectionForAppend() {
        $collection = new Collection();
        for ($i = 15; $i <= 20; $i++) {
            $object = $this->getTestableObject();
            $object->setId($i);
            $collection->addObject($object);
        }
        return $collection;
    }

    /**
     * RTFN
     *
     * @return Collection|TestableObject[]
     */
    protected function getCollectionForMerge() {
        $collection = new Collection();
        for ($i = 7; $i <= 12; $i++) {
            $object = $this->getTestableObject();
            $object->setId($i);
            $collection->addObject($object);
        }
        return $collection;
    }

    /**
     * RTFN
     *
     * @return TestableObject
     */
    protected function getTestableObject() {
        return new TestableObject();
    }
}
 