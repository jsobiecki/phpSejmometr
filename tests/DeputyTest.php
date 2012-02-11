<?php
namespace Sejmometr;

class DeputyTest extends \PHPUnit_Framework_TestCase
{
  public function setUp() {
    $this->object = new Deputy(10);
  }

  public function testgetInfo() {
    $data = $this->object->getInfo();

    //$this->assertNotNull($data, "Deputy info is not null");
  }

  public function testRetrieve() {
    $deputies_all = Deputy::retrieve();

    $deputies_single = Deputy::retrieve(array(19));
  }

  public function testMagicGet() {
    $this->assertNotNull(
      $this->object->id,
      'Method __get works ok for id field'
    );


    $this->assertNotNull(
      $this->object->data_urodzenia,
      'Method __get works ok for data_urodzenia field'
    );
  }
}
