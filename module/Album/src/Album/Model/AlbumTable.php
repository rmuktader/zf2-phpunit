<?php

namespace Album\Model;

use Zend\Db\TableGateway\TableGateway;

class AlbumTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getAlbum($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = $this->albumToArray($album);

        $id = (int) $album->id;
        if ($id == 0) {
            $dbMsg = $this->tableGateway->insert($data);
        } else {
            $dbMsg = $this->updateAlbum($id, $data);
        }

        return $dbMsg;
    }
    
    public function albumToArray(Album $album)
    {
        return array(
            'artist' => $album->artist,
            'title' => $album->title,
        );
    }

    public function updateAlbum($id, $data)
    {
        if ($this->getAlbum($id)) {
            $dbMsg = $this->tableGateway->update($data, array('id' => $id));
        } else {
            throw new \Exception('Album id does not exist');
        }
        
        return $dbMsg;
    }

    public function deleteAlbum($id)
    {
        return $this->tableGateway->delete(array('id' => (int) $id));
    }
}
