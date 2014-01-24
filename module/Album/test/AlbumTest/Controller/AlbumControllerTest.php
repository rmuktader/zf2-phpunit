<?php

namespace AlbumTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    
    public function setUp()
    {
        $this->setApplicationConfig(
            include 'config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/album');
        $this->assertPageFound();
    }
    
    public function testAddActionCanBeAccessed()
    {
        $this->dispatch('/album/add');
        $this->assertPageFound();
    }
    
    public function testEditActionCanBeAccessed()
    {
        $this->dispatch('/album/edit');
        $this->assertPageFound();
    }
    
    public function testDeleteActionCanBeAccessed()
    {
        $this->dispatch('/album/delete');
        $this->assertPageFound();
    }
    
    protected function assertPageFound()
    {
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }
}
