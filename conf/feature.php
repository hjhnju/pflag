<?php
$g_arrFeatureConfig = array(
    'features' => array(
       'MYFEATURE1' => array(
            'enabled' => false,
            'type' => 'release'   
        ),
       'MYFEATURE2' => array(
            'enabled' => true,
            'type' => 'business',
            'strategy' => 'Gradual',
            'params' => array(
                'percent' => 0.05
            ),            
        ),
         'MYFEATURE3' => array(
            'enabled' => true,
            'type' => 'business',
            'strategy' => 'ClientIp',
            'params' => array(
                'white_list' => array(
                    '172.*.*.*'
                )
            ),
        ),
        'MYFEATURE4' => array(
            'enabled' => true,
            'type' => 'business',
            'strategy' => 'ReleaseDate',
            'params' => array(
                'start_date' => '2013-11-06 00:00:00',
                'end_date' => '2013-11-11 23:59:59',
            ),
        ),
   ),
);
