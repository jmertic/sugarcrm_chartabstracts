<?php

require_once('include/Dashlets/DashletGenericChart.php'); 
require_once('include/SugarCharts/SugarChartFactory.php'); 
        
abstract class DashletGenericHorizontalBarChart extends DashletGenericChart 
{  
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
        $sugarChart->setProperties('', $this->subtitle, 'horizontal bar chart'); 
        $sugarChart->base_url   = array(); 
        $sugarChart->group_by   = array(); 
        $sugarChart->url_params = array();
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
     *   '<Field1DisplayName>' => 5,
     *   '<Field2DisplayName>' => 6,
     *   );
     * ?>
     * </code>
     *
     * @return array
     */  
    abstract protected function getDataset();
}  
