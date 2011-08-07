<?php

require_once('include/Dashlets/DashletGenericChart.php'); 
require_once('include/SugarCharts/SugarChartFactory.php'); 
        
abstract class DashletGenericStackedGroupByChart extends DashletGenericChart 
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
     * @see DashletGenericChart::display() 
     */ 
    public function display() 
    { 
        $sugarChart = SugarChartFactory::getInstance(); 
        $sugarChart->setProperties('', $this->subtitle, 'stacked group by chart'); 
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
     *   array('<Column1Name>' => '<Column1Value1>', '<Column2Name>' => '<Column2Value1>', 'total' => 5,),
     *   array('<Column1Name>' => '<Column1Value1>', '<Column2Name>' => '<Column2Value2>', 'total' => 4,),
     *   array('<Column2Name>' => '<Column2Value2>', '<Column2Name>' => '<Column2Value1>', 'total' => 8,),
     *   );
     * ?>
     * </code>
     * 
     * Column1 is the represents the column for each bar in the chart, and Column2 
     * a segment in the bar in the chart
     *
     * @return array
     */ 
    abstract protected function getDataset();
}  
