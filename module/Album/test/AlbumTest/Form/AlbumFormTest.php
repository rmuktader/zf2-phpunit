<?php

namespace AlbumTest\Form;

use PHPUnit_Framework_TestCase;
use Album\Form\AlbumForm;

class AlbumFormTest extends PHPUnit_Framework_TestCase
{

    protected $traceError = true;
    protected $albumForm;
    protected $formElements;

    public function setup()
    {
        $this->albumForm = new AlbumForm();
        $this->formElements = $this->albumForm->getElements();
        parent::setup();
    }

    public function testFormMethod()
    {
        $method = $this->albumForm->getAttribute('method');
        $this->assertEquals('POST', $method);
    }

    public function testFormName()
    {
        $name = $this->albumForm->getAttribute('name');
        $this->assertEquals('album', $name);
    }

    public function testIdElement()
    {
        $this->assertArrayHasKey('id', $this->formElements);
        $className = get_class($this->formElements['id']);
        $this->assertEquals('Zend\Form\Element\Hidden', $className);
    }

    public function testTitleElement()
    {
        $this->assertArrayHasKey('title', $this->formElements);
        $className = get_class($this->formElements['title']);
        $this->assertEquals('Zend\Form\Element\Text', $className);
    }

    public function testArtistElement()
    {
        $this->assertArrayHasKey('artist', $this->formElements);
        $className = get_class($this->formElements['artist']);
        $this->assertEquals('Zend\Form\Element\Text', $className);
    }

    public function testSubmitElement()
    {
        $this->assertArrayHasKey('submit', $this->formElements);
        $className = get_class($this->formElements['submit']);
        $this->assertEquals('Zend\Form\Element\Submit', $className);
    }
}
