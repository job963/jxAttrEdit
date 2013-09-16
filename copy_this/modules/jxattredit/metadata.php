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
    'title'        => 'jxAttrEdit - Product Attibute Editor',
    'description'  => array(
                        'de' => 'Attribut-Editor für Produkte.',
                        'en' => 'Attribute Editor for Products.'
                        ),
    'thumbnail'    => 'jxattredit.png',
    'version'      => '0.3',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxAttrEdit',
    'email'        => 'jbarthel@qualifire.de',
    'extend'       => array(
                        ),
    'files'        => array(
        'article_jxattredit' => 'jxattredit/application/controllers/admin/article_jxattredit.php'
                        ),
    'templates'    => array(
        'article_jxattredit.tpl' => 'jxattredit/views/admin/tpl/article_jxattredit.tpl'
                        )
    );

?>
