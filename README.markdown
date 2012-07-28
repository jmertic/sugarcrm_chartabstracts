This is a set of abstract classes that make it much easier to build chart dashlets in SugarCRM 6.2 or later. To install, copy the abstract classes to the custom/include/Dashlets/ directory.

Here's an example on how to build a chart dashlet from one of these:

1. Create a new directory custom/modules/Charts/Dashlets/YOURCHARTDASHLETNAME/
2. Add a YOURCHARTDASHLETNAME.meta.php file with the following contents

        <?php
        $dashletMeta['YOURCHARTDASHLETNAME'] = array('title'       => 'LBL_TITLE',  
                                                     'description' => 'LBL_TITLE',
                                                     'icon'		  => 'icon_Charts_Horizontal_32.gif',
                                                     'module'	=> 'YOURCHARTDASHLETMODULE', 
                                                     'category'    => 'Charts'
                                                     );

3. Add a YOURCHARTDASHLETNAME.lang.LANGUAGE.php file with the following contents:

        <?php
        $dashletStrings['YOURCHARTDASHLETNAME'] = array('LBL_TITLE'       => 'YOURCHARTDASHLETTITLE',
                                                        'LBL_DESCRIPTION' => 'YOURCHARTDASHLETDESCRIPTION',
                                                        'LBL_REFRESH'     => 'Refresh Chart');

4. Add the Dashlet class like the following ( example is for a bar chart )

        <?php
        require_once('custom/include/Dashlets/DashletGenericBarChart.php'); 
        
        class YOURCHARTDASHLETNAME extends DashletGenericBarChart
        {
            protected $_seedName = 'YOURCHARTDASHLETMODULE'; 

            protected function getDataset()
            {
                $returnArray = array();

                // Have this method get the data, put it in the associative array $returnArray
 
                return $returnArray;
            }
        }

