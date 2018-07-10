<?php

namespace FsRestApi\Config;

class Core
{
    public $recordsPerPage;
    public $page;
    public $fromRecordNum;
    
    public function __construct()
    {
        $this->setPaging();
    }

    public function setPaging($page = 1, $rpp = 10)
    {
        $this->page = ($page > 0) ? $page : 1;
        $this->recordsPerPage = ($rpp > 0) ? $rpp : 10;
        $this->fromRecordNum = (int) (($this->recordsPerPage * $this->page) - $this->recordsPerPage);
    }

}