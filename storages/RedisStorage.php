<?php

namespace storages;

/**
 * Description of RedisStorage
 *
 * @author user
 */
class RedisStorage implements \storages\Storage
{
    private $redis;
    private $id;

    public function __construct($param)
    {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1');
    }
    public function load($id)
    {
        $this->redis->get($id);
    }

    public function save($chessboard)
    {
        $this->redis->set($this->id, $chessboard);
    }
}
