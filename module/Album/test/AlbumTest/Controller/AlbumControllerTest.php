<?php

namespace AlbumTest\Controller;

use AlbumTest\Bootstrap;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Album\Controller\AlbumController;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use PHPUnit_Framework_TestCase;

class AlbumControllerTest extends \PHPUnit_Framework_TestCase
{

    protected $controller;
    protected $request;
    protected $response;
    protected $routeMatch;
    protected $event;
    protected $albumTable;
    protected $albumList;

    protected function setUp()
    {
        $serviceManager = Bootstrap::getServiceManager();
        $this->controller = new AlbumController();
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'album'));
        $this->event = new MvcEvent();
        $config = $serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);

        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($serviceManager);

        $this->setAlbumTable();
        $this->setAlbumList();
    }

    public function setAlbumTable()
    {
        $this->albumTable = $this->getMockBuilder('Album\Model\AlbumTable')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function setAlbumList()
    {
        $this->albumList = array('var' => 'test');
    }
    
    public function prepAlbumTable()
    {
        $this->albumTable->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue($this->albumList));
    }

    
    
    public function testGetAlbumTable()
    {
        $isAlbumTable = false;
        $this->prepServiceManager();
        
        $className = get_class($this->controller->getAlbumTable());
        if (strpos($className, 'Mock_AlbumTable_') !== false) {
            $isAlbumTable = true;
        }
        $this->assertTrue($isAlbumTable);
    }
    
    public function prepServiceManager()
    {
        $serviceManager = $this->controller->getServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Album\Model\AlbumTable', $this->albumTable);
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->prepAlbumTable();
        $this->prepServiceManager();

        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch($this->request);
        $viewVars = $result->getVariables();
        $this->assertEquals($this->albumList, $viewVars['albums']);
        
        $this->checkResponsecode($this->controller);
    }
    
    public function checkResponseCode($controller)
    {
        $response = $controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
    }

//    public function testAddActionCanBeAccessed()
//    {
//        $this->dispatch('/album/add');
//        $this->assertPageFound();
//    }
//
//    public function testEditActionCanBeAccessed()
//    {
//        $this->dispatch('/album/edit');
//        $this->assertPageFound();
//    }
//
//    public function testDeleteActionCanBeAccessed()
//    {
//        $this->dispatch('/album/delete');
//        $this->assertPageFound();
//    }

    protected function assertPageFound()
    {
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }
}
