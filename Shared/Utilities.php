<?php

namespace FsRestApi\Shared;

class Utilities
{

    public function getPaging($page, $total_rows, $records_per_page, $page_url){
        $pagingArr = [];
        $pagingArr["first"] = $page > 1 ? "{$page_url}page=1" : "";
        $total_pages = ceil($total_rows / $records_per_page);
 
        $range = 2;
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
        $pagingArr['pages'] = [];
        $page_count = 0;

        for ($x = $initial_num; $x < $condition_limit_num; $x++){
            if (($x > 0) && ($x <= $total_pages)) {
                $pagingArr['pages'][$page_count]["page"]=$x;
                $pagingArr['pages'][$page_count]["url"]="{$page_url}page={$x}";
                $pagingArr['pages'][$page_count]["current_page"] = ($x == $page ? "yes" : "no");
                 $page_count++;
            }
        }

        $pagingArr["last"] = $page < $total_pages ? "{$page_url}page={$total_pages}" : "";
        return $pagingArr;
    }

}