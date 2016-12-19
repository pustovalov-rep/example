<?php

namespace storages;

/**
 * Description of FileStorage
 *
 * @author user
 */
class FileStorage implements \storages\Storage
{
    public function __construct($file)
    {
        $this->file = $file;
    }

    public function load($file = NULL)
    {
        if ($file === NULL) {
            $file = $this->file;
        }
        
        file_get_contents($file);
    }

    /**
     *
     * @param string $chessboard
     * @return string
     */
    public function save($chessboard)
    {
        file_put_contents($chessboard, $this->file);

        return $this->file;
    }
}
