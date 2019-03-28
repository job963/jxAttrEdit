<?php
/**
 *
 * @package ##@@PACKAGE@@##
 * @version ##@@VERSION@@##
 * @link www.proudcommerce.com
 * @author Proud Sourcing <support@proudcommerce.com>
 * @copyright Proud Sourcing GmbH | 2019
 *
 * This Software is the property of Proud Sourcing GmbH
 * and is protected by copyright law, it is not freeware.
 *
 * Any unauthorized use of this software without a valid license
 * is a violation of the license agreement and will be
 * prosecuted by civil and criminal law.
 *
 **/

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
    'version' => '1.0.0',
    'author' => 'Joachim Barthel, ProudCommerce',
    'url' => 'http://www.proudcommerce.com',
    'email' => 'module@proudcommerce.com',
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
