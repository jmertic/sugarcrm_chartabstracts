<?php
/*
Copyright 2011 John Mertic. All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are
permitted provided that the following conditions are met:

   1. Redistributions of source code must retain the above copyright notice, this list of
      conditions and the following disclaimer.

   2. Redistributions in binary form must reproduce the above copyright notice, this list
      of conditions and the following disclaimer in the documentation and/or other materials
      provided with the distribution.

THIS SOFTWARE IS PROVIDED BY JOHN MERTIC ``AS IS'' AND ANY EXPRESS OR IMPLIED
WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

The views and conclusions contained in the software and documentation are those of the
authors and should not be interpreted as representing official policies, either expressed
or implied, of John Mertic.
*/

require_once('include/Dashlets/DashletGenericChart.php'); 
require_once('include/SugarCharts/SugarChartFactory.php'); 
        
abstract class DashletGenericGaugeChart extends DashletGenericChart 
{  
    /**
     * Array with 4 entries, with the format shown below to override the default strings titles
     *
     * <code>
     * <?php
     * array(
     *   'Section1DisplayName',
     *   'Section2DisplayName',
     *   'Section3DisplayName',
     *   'Section4DisplayName',
     *   );
     * ?>
     * </code>
     *
     * Alternatively, you can use this format to specify the start and end points of the gauge rather
     * than just evenly dividing the gauge up. Will specify the last end value to the greater value of
     * the last specified end value and DashletGenericGaugeChart::getTargetNumber() 
     *
     * <code>
     * <?php
     * array(
     *   'Section1DisplayName' => array('start' => 0,'end'=>'4'),
     *   'Section2DisplayName' => array('start' => 4,'end'=>'8'),
     *   'Section3DisplayName' => array('start' => 8,'end'=>'11'),
     *   'Section4DisplayName' => array('start' => 11,'end'=>'20'),
     *   );
     * ?>
     * </code>
     *
     * @var array
     */
    protected $gaugeTargets = array();
    
    /** 
     * Don't override this method, since it does all the work of actually rendering the chart.
     *
     * @see DashletGenericChart::display() 
     */ 
    public function display() 
    { 
        $sugarChart = SugarChartFactory::getInstance(); 
        $sugarChart->setProperties('', '', 'gauge chart'); 
        $sugarChart->base_url   = array(); 
        $sugarChart->group_by   = array(); 
        $sugarChart->url_params = array();
        if ( !empty($this->gaugeTargets) ) {
            // if there are no start/end values defined, split the chart evenly
            if ( !is_array(current($this->gaugeTargets)) ) {
                $target = $this->getMaximumValue();
                $phases[array_shift($this->gaugeTargets)] = array( 'start' => 0, 'end' => ceil($target/4), );
                $phases[array_shift($this->gaugeTargets)] = array( 'start' => ceil($target/4), 'end' => ceil($target/2), );
                $phases[array_shift($this->gaugeTargets)] = array( 'start' => ceil(($target/2)), 'end' => ceil(($target/4)*3), );
                $phases[array_shift($this->gaugeTargets)] = array( 'start' => ceil(($target/4)*3), 'end' => ceil($target), );			
                $sugarChart->setDisplayProperty('gaugePhases', $phases);
                $sugarChart->setDisplayProperty('gaugeTarget', $target);
            }
            else {
                // advance the array to the last key to replace the end value of the array with the target number
                end($this->gaugeTargets);
                list($key) = each($this->gaugeTargets);
                $this->gaugeTargets[$key]['end'] = $this->getMaximumValue();
                $sugarChart->setDisplayProperty('gaugePhases', $this->gaugeTargets);
                $sugarChart->setDisplayProperty('gaugeTarget', $this->gaugeTargets[$key]['end']);
            }
        }
        else {
            $sugarChart->setDisplayProperty('gaugeTarget', $this->getMaximumValue());
        }
        $sugarChart->setData(array(array('num'=>$this->getCurrentValue())));
        $xmlFile = $sugarChart->getXMLFileName($this->id); 
        $sugarChart->saveXMLFile($xmlFile, $sugarChart->generateXML()); 
     
        return '<div align="center">' . 
            $sugarChart->display($this->id, $xmlFile, '100%', '480', false) . 
            '</div>' . 
            $this->processAutoRefresh(); 
    } 

    /** 
     * Returns the value of where the gauge should be set to
     *
     * @return int
     */ 
    abstract protected function getCurrentValue();
    
    /** 
     * Returns the maximum value of the gauge
     *
     * @return int
     */ 
    abstract protected function getMaximumValue();
}  
