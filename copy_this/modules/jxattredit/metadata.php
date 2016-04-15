<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'jxattredit',
    'title'        => 'jxAttrEdit - Product Attibute Editor',
    'description'  => array(
                        'de' => 'Attribut-Editor für Artikel.',
                        'en' => 'Attribute Editor for Products.'
                        ),
    'thumbnail'    => 'jxattredit.png',
    'version'      => '0.4.5',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxAttrEdit',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'files'        => array(
        'article_jxattredit' => 'jxattredit/application/controllers/admin/article_jxattredit.php'
                        ),
    'templates'    => array(
        'article_jxattredit.tpl' => 'jxattredit/application/views/admin/tpl/article_jxattredit.tpl'
                        ),
    'settings' => array(
                        array(
                                'group' => 'JXATTREDIT_DISPLAY', 
                                'name'  => 'sJxAttrEditNumberOfColumns', 
                                'type'  => 'str', 
                                'value' => '2'
                                )
                        )
    );

?>
