<?php

namespace FsRestApi\Config;

class Core
{
    public static $homeUrl = "http://localhost/fsRestApi/";
    public static $recordsPerPage = 5;
    public $page = 1;
    public $fromRecordNum = 0;

    public function setPage($page = 1)
    {
        $this->page = (int) $page;
        if ($this->page > 1) {
            $this->fromRecordNum = (int) ((self::$recordsPerPage * $this->page) - self::$recordsPerPage);
        }
    }

}