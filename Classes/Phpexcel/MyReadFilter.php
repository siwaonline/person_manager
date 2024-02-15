<?php

namespace Personmanager\PersonManager\Phpexcel;

class MyReadFilter implements \PHPExcel_Reader_IReadFilter
{
    public function readCell($column, $row, $worksheetName = '')
    {
        // Read title row and rows 20 - 30
        if (in_array($column, range('A', 'R'))) {
            return true;
        }
        return false;
    }

    public function xrange($start, $end, $limit = 10000)
    {
        $l = [];
        while ($start !== $end && count($l) < $limit) {
            $l[] = $start;
            $start++;
        }
        $l[] = $end;
        return $l;
    }
}
