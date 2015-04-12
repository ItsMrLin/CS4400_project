<?php

class Map
{
    protected $map;

    public function link($from, $to)
    {
        $this->map[$from] = $to;
    }

    public function get($from)
    {
        return (isset($this->map[$from])) ? $this->map[$from] : false;
    }
}