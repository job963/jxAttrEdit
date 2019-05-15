<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id' => 'jxattredit',
    'title' => 'jxAttrEdit - Product Attibute Editor',
    'description' => [
        'de' => 'Attribut-Editor für Artikel.',
        'en' => 'Attribute Editor for Products.'
    ],
    'thumbnail' => 'jxattredit.png',
    'version' => '1.1.3',
    'author' => 'Joachim Barthel, ProudCommerce, Zunderweb',
    'url' => 'http://www.zunderweb.de',
    'email' => 'info@zunderweb.de',
    'controllers' => [
        'article_jxattredit' => \ProudCommerce\Jx\AttrEdit\Application\Controllers\Admin\AttributeEdit::class,
    ],

    'extend' => [
    ],

    'templates' => [
        'article_jxattredit.tpl' => 'jx/attredit/Application/views/admin/tpl/article_jxattredit.tpl',
    ],

    'blocks' => [
    ],

    'settings' => [
        [
            'group' => 'JXATTREDIT_DISPLAY',
            'name'  => 'sJxAttrEditNumberOfColumns',
            'type'  => 'select',
            'value' => '2',
            'constraints' => '2|3|4'
        ]
    ],

    'events' => [
    ],
);
