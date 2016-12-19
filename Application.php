<?php

/**
 * Description of Application
 *
 * @author user
 */
class Application
{
    private $storage;

    public function __construct()
    {
        $this->storage = new storages\FileStorage('chess');
    }
    public function save($chessboard)
    {
        return $this->storage->save($chessboard->toJson());
    }

    public function load($id)
    {
        return $this->storage->load($id);
    }
}
