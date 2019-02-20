<?php
namespace Personmanager\PersonManager\Phpexcel;

class MyReadFilter implements \PHPExcel_Reader_IReadFilter
{
    public function readCell($column, $row, $worksheetName = '') {
        // Read title row and rows 20 - 30
        //if (in_array($column,'A')||in_array($column,'D')||in_array($column,'E')||in_array($column,'G')||in_array($column,'H')||in_array($column,'I')||in_array($column,'V')||in_array($column,'W')||in_array($column,'X')||in_array($column,'Y')||in_array($column,'AP')||in_array($column,'AQ')||in_array($column,'AR')||in_array($column,'AS')||in_array($column,'AT')||in_array($column,'AU')||in_array($column,'AV')||in_array($column,'AW')||in_array($column,'AX')||in_array($column,'AY')||in_array($column,'AZ')||in_array($column,'BA')||in_array($column,'BB')||in_array($column,'BC')||in_array($column,'BD')||in_array($column,'BE')||in_array($column,'BF')||in_array($column,'BG')||in_array($column,'BH')||in_array($column,'BI')  ) { 
        if (in_array($column,range('A','R')) ) {
            return true;
        }
        return false;
    }
    function xrange($start, $end, $limit = 10000) {
        $l = array();
        while ($start !== $end && count($l) < $limit) {
            $l[] = $start;
            $start ++;
        }
        $l[] = $end;
        return $l;
    }
}