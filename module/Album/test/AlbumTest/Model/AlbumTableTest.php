<?php

namespace AlbumTest\Model;

use PHPUnit_Framework_TestCase;
use Album\Model\AlbumTable;

class AlbumTableTest extends PHPUnit_Framework_TestCase
{

    protected $mockTableGateway;
    protected $albumTable;

    public function setup()
    {
        $this->mockTableGateway = \Mockery::mock(
                'Zend\Db\TableGateway\TableGateway'
        );
        $this->albumTable = new AlbumTable($this->mockTableGateway);
    }

    public function testFetchAll()
    {
        $mockResultset = \Mockery::mock('Zend\Db\ResultSet\ResultSet');

        $this->mockTableGateway
            ->shouldReceive('select')
            ->times(1)
            ->andReturn($mockResultset);

        $this->assertEquals($mockResultset, $this->albumTable->fetchAll());
    }

    public function testSaveNewAlbum()
    {
        $mockAlbum = $this->generateMockAlbum($id=0, 'Title', 'Artist');
        $insertCount = 1;
        $data = array(
            'artist' => $mockAlbum->artist,
            'title' => $mockAlbum->title,
        );

        $this->mockTableGateway
            ->shouldReceive('insert')
            ->times(1)
            ->with($data)
            ->andReturn($insertCount);

        $this->assertEquals(
            $insertCount, $this->albumTable->saveAlbum($mockAlbum)
        );
    }
    
    public function generateMockAlbum($id = 0, $title = '', $artist = '')
    {
        $mockAlbum = \Mockery::mock('\Album\Model\Album');
        $mockAlbum->artist = $artist;
        $mockAlbum->title = $title;
        $mockAlbum->id = $id;
        
        return $mockAlbum;
    }
    
    public function albumToArray(Album $album)
    {
        return array(
            'artist' => $album->artist,
            'title' => $album->title,
        );
    }

    public function testUpdateExistingRecord()
    {
        $mockAlbum = \Mockery::mock('\Album\Model\Album');
        $mockAlbum->artist = "Matthew Setter";
        $mockAlbum->title = "Artist";
        $mockAlbum->id = 12;
        $updateCount = 1;
        $data = array(
            'artist' => $mockAlbum->artist,
            'title' => $mockAlbum->title,
            'id' => 12
        );

        $mockResultset = \Mockery::mock('Zend\Db\ResultSet\ResultSet');
        $mockResultset->shouldReceive('current')
            ->times(1)
            ->andReturn($mockAlbum);

        $this->mockTableGateway
            ->shouldReceive('select')
            ->times(1)
            ->with(array('id' => $data['id']))
            ->andReturn($mockResultset);

        $this->mockTableGateway
            ->shouldReceive('update')
            ->times(1)
            ->with(array(
                'artist' => $data['artist'],
                'title' => $data['title']
                ), array('id' => $mockAlbum->id)
            )
            ->andReturn($updateCount);

        $this->assertEquals(
            $updateCount, $this->albumTable->saveAlbum($mockAlbum)
        );
    }

    public function testCannotDeleteAlbumWithoutId()
    {
        $this->mockTableGateway
            ->shouldReceive('delete')
            ->times(1)
            ->andReturn(false);

        $this->assertEquals(
            false, $this->albumTable->deleteAlbum("")
        );
    }

    public function testCanDeleteAlbumWithValidId()
    {
        $recordId = 22;
        $recordsDeleted = 1;
        $this->mockTableGateway
            ->shouldReceive('delete')
            ->times(1)
            ->with(array('id' => 22))
            ->andReturn($recordsDeleted);

        $this->assertEquals(
            $recordsDeleted, $this->albumTable->deleteAlbum($recordId)
        );
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
