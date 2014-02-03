<?php

namespace AlbumTest\Model;

use PHPUnit_Framework_TestCase;
use Album\Model\Album;

class AlbumTest extends \PHPUnit_Framework_TestCase
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
    
    public function testSettingCustomInputFilterIsNotAllowed()
    {
        $this->setExpectedException(
          '\Exception', 'Not used'
        );
        
        $inputFilter = $this->getMockBuilder('Zend\InputFilter\InputFilter')
            ->getMock();
        $this->album->setInputFilter($inputFilter);
    }
    
    public function testIdInputFilter()
    {
        $filterName = 'id';
        $inputFilter = $this->album->getInputFilter();
        
        $this->assertTrue($inputFilter->has($filterName));
        $this->isRequired($filterName);
        
        $inputs = $inputFilter->getInputs();
        $filters = $inputs[$filterName]->getFilterChain()->getFilters()->toArray();
        $filter1 = get_class($filters[0]);
        $this->assertEquals('Zend\Filter\Int', $filter1);
    }
    
    public function testArtistInputFilter()
    {
        $this->validatorTest('artist');
        $this->filterTest('artist');
        $this->isRequired('artist');
    }
    
    public function testTitleInputFilter()
    {
        $this->validatorTest('title');
        $this->filterTest('title');
        $this->isRequired('title');
    }
    
    public function filterTest($filterName)
    {
        $inputFilter = $this->album->getInputFilter();
        $this->assertTrue($inputFilter->has($filterName));
        $inputs = $inputFilter->getInputs();
        $filters = $inputs[$filterName]->getFilterChain()->getFilters()->toArray();
        
        $filter1 = get_class($filters[0]);
        $this->assertEquals('Zend\Filter\StripTags', $filter1);
        
        $filter2 = get_class($filters[1]);
        $this->assertEquals('Zend\Filter\StringTrim', $filter2);
    }
    
    public function validatorTest($filterName)
    {
        $validator = $this->getValidator($filterName);
        
        $className = get_class($validator);
        $this->assertEquals('Zend\Validator\StringLength', $className);
        $this->assertEquals(100, $validator->getMax());
        $this->assertEquals(1, $validator->getMin());
        $this->assertEquals('UTF-8', $validator->getEncoding());
    }
    
    public function isRequired($filterName)
    {
        $inputFilter = $this->album->getInputFilter();
        $filter = $inputFilter->get($filterName);
        $this->assertTrue($filter->isRequired());
    }
    
    public function getValidator($filterName)
    {
        $filter = $this->album->getInputFilter();
        $validators = $filter->get($filterName)
            ->getValidatorChain()
            ->getValidators();
        $validator = $validators[0]['instance'];

        return $validator;
    }
}
