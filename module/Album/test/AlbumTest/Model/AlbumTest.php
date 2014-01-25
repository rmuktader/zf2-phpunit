<?php

namespace AlbumTest\Model;

use PHPUnit_Framework_TestCase;
use Album\Model\Album;

class AlbumTest extends PHPUnit_Framework_TestCase
{

    protected $traceError = true;
    protected $albumData;
    protected $album;

    public function setup()
    {
        $this->albumData = array(
            'id' => 1,
            'artist' => 'Test Artist',
            'title' => 'Test Title',
        );

        $this->album = new Album();
        $this->album->exchangeArray($this->albumData);

        parent::setup();
    }

    public function testAlbumId()
    {
        $this->assertEquals($this->album->id, $this->albumData['id']);
    }

    public function testAlbumArtist()
    {
        $this->assertEquals($this->album->artist, $this->albumData['artist']);
    }

    public function testAlbumTitle()
    {
        $this->assertEquals($this->album->title, $this->albumData['title']);
    }
}
