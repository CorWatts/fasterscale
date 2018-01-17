<?php

namespace common\tests\unit\components;

use Yii;
use common\components\Graph;

/**
 * Graph test
 */

class GraphTest extends \Codeception\Test\Unit
{
  use \Codeception\Specify;

  private $user;
  private $filepath = __DIR__.'/../../_output/test_graph.png';
  private $filepath_extra = __DIR__.'/../../_output/charts/test_graph.png';

  public function setUp() {
    $this->user = $this->getMockBuilder('\site\tests\_support\MockUser')
      ->setMethods(['getIdHash'])
      ->getMock();

    parent::setUp();
  }

  protected function tearDown() {
    $this->user = null;
    parent::tearDown();
  }

  public function testGetFilepath() {
    $this->user
      ->method('getIdHash')
      ->willReturn('random1DH4sh');
    $graph = new Graph($this->user);

    expect('the expected graph image filepath will be returned', $this->assertEquals(dirname(dirname(dirname(dirname(__DIR__)))).'/site/web/charts/random1DH4sh.png', $graph->getFilepath()));
  }

  public function testGetUrl() {
    $this->user
      ->method('getIdHash')
      ->willReturn('random1DH4sh');
    $graph = new Graph($this->user);

    expect('the expected graph image filepath will be returned', $this->assertStringEndsWith('/charts/random1DH4sh.png', $graph->getUrl()));
  }

  public function testDestroy() {
    $data = [
      '2018-01-17T00:57:11-08:00' => 22,
      '2018-01-18T00:53:28-08:00' => 14,
      '2018-01-19T03:56:44-08:00' => 0,
      '2018-01-20T04:35:51-08:00' => 29
    ];

    $this->graph = $this->getMockBuilder('\common\components\Graph', [$this->user])
      ->setConstructorArgs([$this->user])
      ->setMethods(['getFilepath'])
      ->getMock();
    $this->graph
      ->method('getFilepath')
      ->willReturn($this->filepath);

    if(!file_exists($this->filepath) && preg_match('%/_output/test_graph.png$%', $this->filepath)) {
      touch($this->filepath);
      expect('just a check to be sure $filepath is sane', $this->assertStringEndsWith('/_output/test_graph.png', $this->filepath));
      expect('the generated file should exist', $this->assertFileExists($this->filepath));
      expect('the generated file should be readable', $this->assertFileExists($this->filepath));
      $this->graph->destroy();
      expect('the generated file should no longer exist', $this->assertFileNotExists($this->filepath));
    } else {
      expect('the file should not exist yet. If this fails, delete the file', $this->assertFileNotExists($this->filepath));
      expect('this should NOT happen', $this->assertTrue(false));
    }
  }

  public function testCreate() {
    $data = [
      '2018-01-17T00:57:11-08:00' => 22,
      '2018-01-18T00:53:28-08:00' => 14,
      '2018-01-19T03:56:44-08:00' => 0,
      '2018-01-20T04:35:51-08:00' => 29
    ];

    $this->graph = $this->getMockBuilder('\common\components\Graph', [$this->user])
      ->setConstructorArgs([$this->user])
      ->setMethods(['getFilepath'])
      ->getMock();
    $this->graph
      ->method('getFilepath')
      ->willReturn($this->filepath_extra);

    expect('the file should not exist yet. If this fails, delete the file', $this->assertFileNotExists($this->filepath));
    expect('the containing directory should not exist yet either. If this fails, delete the directory', $this->assertFalse(is_dir(dirname($this->filepath_extra))));

    $this->graph->create($data, true);

    expect('just a check to be sure $filepath_extra is sane', $this->assertStringEndsWith('/_output/charts/test_graph.png', $this->filepath_extra));
    expect('the generated file should exist', $this->assertFileExists($this->filepath_extra));
    expect('the generated file should be readable', $this->assertFileExists($this->filepath_extra));

    // cleanup
    if(file_exists($this->filepath_extra) && preg_match('%/_output/charts/test_graph.png%', $this->filepath_extra)) {
      // just in case something is weird, we don't want to straight rm this file
      unlink($this->filepath_extra);
      rmdir(dirname($this->filepath_extra));
    }
  }
}
