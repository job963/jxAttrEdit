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
    );

?>
