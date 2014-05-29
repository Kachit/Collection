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
     * @var Collection
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
    public function testGetObject() {
        $result = $this->testable->getObject(1);
        $this->assertNotEmpty($result);
        $this->assertTrue(is_object($result));
        $this->assertInstanceOf('Kachit\Collection\ItemInterface', $result);
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
     * @return TestableObject
     */
    protected function getTestableObject() {
        return new TestableObject();
    }
}
 