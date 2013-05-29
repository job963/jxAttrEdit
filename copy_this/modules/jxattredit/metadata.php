<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.0';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'jxattredit',
    'title'        => 'jxAttrEdit - OXID Product Attibute Editor',
    'description'  => array(
                        'de' => 'Attribut Editor für Produkte.',
                        'en' => 'Attribute Editor for Products.'
                        ),
    'thumbnail'    => 'jxattredit.png',
    'version'      => '0.2',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxAttrEdit',
    'email'        => 'jbarthel@qualifire.de',
    'extend'       => array(
        'oxadmindetails' => array('jxattredit/application/controllers/admin/article_jxattredit'
                                  )
                        ),
    'templates' => array(
                        'article_jxattredit.tpl' => 'jxattredit/views/admin/tpl/article_jxattredit.tpl'
                        ),
    'blocks' => array(
    ),
    'settings' => array(
    )
    /*'settings' => array(
                        array(
                            'group' => 'OXPROBS_ARTICLESETTINGS', 
                            'name'  => 'sOxProbsEANField', 
                            'type'  => 'select', 
                            'value' => 'oxean',
                            'constrains' => 'oxean|oxdistean', 
                            'position' => 0 
                            ),
                        array(
                            'group' => 'OXPROBS_ARTICLESETTINGS', 
                            'name'  => 'sOxProbsMinDescLen', 
                            'type'  => 'str', 
                            'value' => '15'
                            ),
                        array(
                            'group' => 'OXPROBS_ARTICLESETTINGS', 
                            'name'  => 'sOxProbsBPriceMin',  
                            'type'  => 'str', 
                            'value' => '0.5'
                            ),
                        array(
                            'group' => 'OXPROBS_ARTICLESETTINGS', 
                            'name'  => 'sOxProbsMaxActionTime',  
                            'type'  => 'str', 
                            'value' => '14'
                            ),
                        array(
                            'group' => 'OXPROBS_PICTURESETTINGS', 
                            'name'  => 'sOxProbsPictureDirs',  
                            'type'  => 'select', 
                            'value' => 'master',
                            'constrains' => 'master|generated', 
                            'position' => 0 
                            ),
                        )*/
    );

?>
