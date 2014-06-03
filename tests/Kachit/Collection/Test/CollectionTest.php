<?php
/**
 * CollectionTest
 *
 * @author antoxa <kornilov@realweb.ru>
 */
namespace Kachit\Collection\Test;

use Kachit\Collection\Collection;
use Kachit\Collection\ItemInterface;

class CollectionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var TestableCollection|TestableObject[]
     */
    protected $testable;

    /**
     * Init
     */
    protected function setUp() {
        $this->testable = new TestableCollection();
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
    public function testJsonSerialize() {
        $result = $this->testable->jsonSerialize();
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
    }

    /**
     * RTFN
     */
    public function testToArray() {
        $result = $this->testable->toArray();
        $this->assertNotEmpty($result);
        $this->assertTrue(is_array($result));
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
    public function testGetObjectWithNullIndex() {
        $result = $this->testable->getObject();
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('Kachit\Collection\ItemInterface', $result);
        $this->assertEquals(1, $result->getId());
    }

    /**
     * RTFN
     */
    public function testExtract() {
        $filter = [1, 2, 10];
        $result = $this->testable->extract($filter);
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('Kachit\Collection\Collection', $result);
        $this->assertEquals(3, $result->count());
        $this->assertTrue($result->hasObject(1));
        $this->assertTrue($result->hasObject(2));
        $this->assertTrue($result->hasObject(10));
    }

    /**
     * RTFN
     * @expectedException \Kachit\Collection\Exception
     * @expectedExceptionMessage Indexes list is not be empty
     */
    public function testExtractWithEmptyFilter() {
        $filter = [];
        $this->testable->extract($filter);
    }

    /**
     * RTFN
     * @expectedException \Kachit\Collection\Exception
     * @expectedExceptionMessage This indexes "foo, bar" is not exists in collection
     */
    public function testExtractWithBadFilter() {
        $filter = ['foo', 1, 'bar'];
        $this->testable->extract($filter);
    }

    /**
     * RTFN
     */
    public function testCloneObject() {
        $result = $this->testable->cloneObject(1);
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('Kachit\Collection\ItemInterface', $result);
        $this->assertFalse($result === $this->testable->getObject(1));
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
        foreach ($collection->getIds() as $key) {
            $this->assertFalse($collection->getObject($key) === $this->testable->getObject($key));
        }
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
    public function testFilterOdd() {
        $indexes = [1, 3, 5, 7, 9];
        $function = $this->getFunctionForFilterOdd();
        $result = $this->testable->filter($function);
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('Kachit\Collection\Collection', $result);
        $this->assertEquals(5, $result->count());
        foreach ($indexes as $key) {
            $this->assertTrue($result->hasObject($key));
        }
    }

    /**
     * RTFN
     */
    public function testWalkChangeName() {
        $function = $this->getFunctionForWalkChangeName();
        $this->testable->walk($function);
        foreach ($this->testable as $object) {
            $name = 'name' . $object->getId();
            $this->assertEquals($name, $object->getName());
        }
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
     * RTFN
     */
    public function testSerializeToJson() {
        $result = json_encode($this->testable);
        $json = '{"1":{},"2":{},"3":{},"4":{},"5":{},"6":{},"7":{},"8":{},"9":{},"10":{}}';
        $this->assertEquals($json, $result);
    }

    /**
     * RTFN
     */
    public function testGetMethodsForAddObject() {
        $result = $this->testable->getMethodsForAddObject();
        $this->assertTrue(is_array($result));
        $this->assertEquals(2, count($result));
    }

    /**
     * RTFN
     */
    public function testSliceWithOffsetWithoutLimit() {
        $result = $this->testable->slice(3);
        $this->assertEquals(7, $result->count());
    }

    /**
     * RTFN
     */
    public function testSliceWithOffsetAndLimit() {
        $result = $this->testable->slice(3, 3);
        $this->assertEquals(3, $result->count());
    }

    /**
     * RTFN
     */
    public function testFillCollectionConstruct() {
        $array = $this->getCollectionForMerge()->toArray();
        $result = new TestableCollection($array);
        $this->assertEquals(6, $result->count());
    }

    /**
     * RTFN
     *
     * @expectedException \Kachit\Collection\Exception
     * @expectedExceptionMessage Test message
     */
    public function testHandleError() {
        $this->testable->handleError('Test message');
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
     * @return callable
     */
    protected function getFunctionForFilterOdd() {
        /* @var ItemInterface $element */
        $func = function($element) {
            return($element->getId() & 1);
        };
        return $func;
    }

    /**
     * RTFN
     *
     * @return callable
     */
    protected function getFunctionForWalkChangeName() {
        /* @var TestableObject $element */
        $func = function($element) {
            $name = 'name' . $element->getId();
            $element->setName($name);
        };
        return $func;
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
 