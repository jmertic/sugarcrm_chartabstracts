<?php

require_once('include/Dashlets/DashletGenericChart.php'); 
require_once('include/SugarCharts/SugarChartFactory.php'); 
        
abstract class DashletGenericPieChart extends DashletGenericChart 
{  
    /**
     * Array of group by columns
     *
     * @var array 
     */
    protected $groupBy = array();
    
    /**
     * Array of columns from the dataset to be passed to the URL for drilldown
     * Defaults to self::$groupBy if empty
     *
     * @var array 
     */
    protected $urlParams = array();
    
    /**
     * Label that goes below the chart and above the legend
     *
     * @var string
     */
    protected $subtitle = '';
    
    /** 
     * Don't override this method, since it does all the work of actually rendering the chart.
     *
     * @see DashletGenericChart::display() 
     */ 
    public function display() 
    { 
        $sugarChart = SugarChartFactory::getInstance(); 
        $sugarChart->setProperties('', $this->subtitle, 'pie chart'); 
        $sugarChart->base_url   = array(
            'module' =>  !empty($this->_seedName) ? $this->_seedName : 'Home', 
            'action' => 'index', 
            'query' => 'true', 
            'searchFormTab' => 'advanced_search', 
            ); 
        $sugarChart->group_by   = $this->groupBy; 
        if ( empty($this->urlParams) ) $this->urlParams = $this->groupBy;
        $sugarChart->url_params = $this->urlParams;
        $sugarChart->setData($this->getDataset());
        if ( !empty($this->groupBy[0]) ) {
            $sugarChart->data_set = $sugarChart->sortData($sugarChart->data_set, $this->groupBy[0], true);
        }
        $xmlFile = $sugarChart->getXMLFileName($this->id); 
        $sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML()); 
     
        return '<div align="center">' . 
            $sugarChart->display($this->id, $xmlFile, '100%', '480', false) . 
            '</div>' . 
            $this->processAutoRefresh(); 
    } 

    /** 
     * This is the format of the data returned
     *
     * <code>
     * <?php
     * array(
     *   array('<ColumnName>' => '<ColumnValue1>', 'total' => 5, ),
     *   array('<ColumnName>' => '<ColumnValue2>', 'total' => 4, )
     *   array('<ColumnName>' => '<ColumnValue3>', 'total' => 8, )
     *   );
     * ?>
     * </code>
     *
     * @return array
     */ 
    abstract protected function getDataset();
}  
