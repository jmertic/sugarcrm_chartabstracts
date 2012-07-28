<?php
require_once('custom/include/Dashlets/DashletGenericPieChart.php'); 

class PieChart extends DashletGenericPieChart
{
    protected $groupBy = array('column1');

    protected function getDataset()
    {
        $returnArray = array(
            array('column1' => 'one', 'total' => 5, ),
            array('column1' => 'two', 'total' => 4, ),
            array('column1' => 'three', 'total' => 8, ),
            );
        
        return $returnArray;
    }
}

