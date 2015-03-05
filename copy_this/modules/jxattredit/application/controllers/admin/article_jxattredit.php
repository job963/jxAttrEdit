<?php
/**
 *    This file is part of the module jxAttrEdit for OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      https://github.com/job963/jxAttrEdit
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @copyright (C) Joachim Barthel 2012-2015
 *
 */

class article_jxattredit extends oxAdminView
{

    protected $_sThisTemplate = "article_jxattredit.tpl";
    
    /**
     * Executes parent method parent::render(), passes data to Smarty engine
     * and returns name of template file "article_jxattredit.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $myConfig = oxRegistry::get("oxConfig");
        
        $nColumns = $myConfig->getConfigParam("sJxAttrEditNumberOfColumns");
        $this->_aViewData["edit"] = $oArticle = oxNew( "oxarticle");
        $soxId = $this->getConfig()->getRequestParameter( "oxid");
        
        if ( $soxId != "-1" && isset( $soxId)) {
            // load object
            $oArticle->loadInLang( $this->_iEditLang, $soxId );

            // load object in other languages
            $oOtherLang = $oArticle->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oArticle->loadInLang( key($oOtherLang), $soxId );
            }

            foreach ( $oOtherLang as $id => $language) {
                $oLang= new oxStdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] =  clone $oLang;
            }

            // variant handling
            if ( $oArticle->oxarticles__oxparentid->value) {
                $oParentArticle = oxNew( "oxarticle");
                $oParentArticle->load( $oArticle->oxarticles__oxparentid->value);
                $this->_aViewData["parentarticle"] =  $oParentArticle;
                $this->_aViewData["oxparentid"] =  $oArticle->oxarticles__oxparentid->value;
            }

            $sShopID = $myConfig->getShopID();
            $sOxvArticles = getViewName( 'oxarticles', $this->_iEditLang, $sShopID );
            $sOxvAttribute = getViewName( 'oxattribute', $this->_iEditLang, $sShopID );
            $sOxvObject2Attribute = getViewName( 'oxobject2attribute', $this->_iEditLang, $sShopID );
            
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );

            if ( $oArticle->oxarticles__oxparentid->value )
                $sSrcId = $oArticle->oxarticles__oxparentid->value;
            else
                $sSrcId = $soxId;

            $sSql = "SELECT oxid AS oxid, oxartnum AS oxartnum, "
                    . "IF(oxparentid='', "
                        ."oxtitle, "
                        . "IF(oxtitle='', "
                            ."CONCAT((SELECT b.oxtitle FROM $sOxvArticles b WHERE b.oxid='$sSrcId'), ' - ', oxvarselect), "
                            ."CONCAT(oxtitle, ' - ', oxvarselect) "
                        . ") "
                    . ") AS oxtitle "
                    . "FROM $sOxvArticles "
                    . "WHERE "
                        . "oxid='$sSrcId' "
                        . "OR oxparentid='$sSrcId' "
                    . "ORDER BY oxvarselect ";
            $rs = $oDb->Execute($sSql);
            $aProdList = array();
            while (!$rs->EOF) {
                array_push($aProdList, $rs->fields);
                $rs->MoveNext();
            }
            
            $sSql1 = "SELECT oxid AS oxid, oxtitle AS oxtitle FROM $sOxvAttribute a ORDER BY oxtitle";
            $aAttrList = array();
            $i = 0;
            $rs1 = $oDb->Execute($sSql1);

            while (!$rs1->EOF) {
                $sSql2 = "SELECT DISTINCT oxvalue AS oxvalue "
                        . "FROM $sOxvObject2Attribute "
                        . "WHERE oxattrid = '" . $rs1->fields['oxid'] . "' "
                        . "ORDER BY oxvalue ";
                $aAttrValues = array();
                $rs2 = $oDb->Execute($sSql2);
                while (!$rs2->EOF) {
                    array_push($aAttrValues, $rs2->fields['oxvalue']);
                    $rs2->MoveNext();
                }
                $sSql3 = "SELECT oxid AS voxid, oxvalue AS oxvalue "
                        . "FROM $sOxvObject2Attribute "
                        . "WHERE oxobjectid = '$soxId' "
                            ."AND oxattrid = '" . $rs1->fields['oxid'] . "' ";
                $rs3 = $oDb->Execute($sSql3);
                if (!$rs3->EOF) {
                    $ValueID = $rs3->fields['voxid'];
                    $ArtValue = $rs3->fields['oxvalue'];
                }
                else {
                    $ValueID = "";
                    $ArtValue = "";
                }

                array_push($aAttrList, $rs1->fields);
                $aAttrList[$i]['oxvalueid'] = $ValueID;
                $aAttrList[$i]['oxartvalue'] = $ArtValue;
                $aAttrList[$i]['oxvalues'] = $aAttrValues;
                $i++;
                $rs1->MoveNext();
            }
            $nAttrSplit = round(($i/$nColumns)+0.5, 0, PHP_ROUND_HALF_UP);

            $sSql = "SELECT a.oxtitle AS oxtitle, 'Testwert' as oxvalue FROM oxattribute a ORDER BY oxpos, oxtitle";
            $aAttributes = array();
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aAttributes, $rs->fields);
                $rs->MoveNext();
            }

            $this->_aViewData["nAttrSplit"] = $nAttrSplit;
            $this->_aViewData["nColumns"] = $nColumns;
            $this->_aViewData["aProdList"] = $aProdList;
            $this->_aViewData["aAttrList"] = $aAttrList;
            $this->_aViewData["aAttributes"] = $aAttributes;
        }

        return $this->_sThisTemplate;
     }

     
     
     
     public function saveAllAttrs($sOXID = null, $aParams = null)
     {

        if ( !isset( $sOXID ) && !isset( $aParams ) ) {
            $sOXID   = $this->getConfig()->getRequestParameter( "voxid" );
            $aParams = $this->getConfig()->getRequestParameter( "editval" );
        }
         
        // for later, on inserting new attribute values
            //$sUid = $myUtilsObject->generateUid();
            // --->  $sUid = oxUtilsObject::getInstance()->generateUID();
            // --->  echo "-".$sUid."-<hr>";
                //array_push($aAttrList, $rs1->fields);
        $sOXID = $this->getConfig()->getRequestParameter( "oxid" );
        $sOxvObject2Attribute = getViewName( 'oxobject2attribute', $this->_iEditLang, $sShopID );
        $oDb = oxDb::getDb();
        
        $sSql = "";
        $iRows = $this->getConfig()->getRequestParameter( "rownum" );
        for ($i = 0; $i <= $iRows; $i++) {
            $sValueID = $this->getConfig()->getRequestParameter( "oxvalueid_$i" );
            $sAttrID = $this->getConfig()->getRequestParameter( "oxattrid_$i" );
            $sAttrValue = $oDb->quote( $this->getConfig()->getRequestParameter( "attrval_$i" ) );
            
            $sSql = "";
            
            if (($sValueID != '') && ($sAttrValue != '')) {   //attribute exists and not empty value received --> update
                $sSql = "UPDATE $sOxvObject2Attribute SET oxvalue=$sAttrValue WHERE oxid='$sValueID' ";
            }
            
            if (($sValueID != '') && ($sAttrValue == '')) {   //attribute exists, but empty value --> delete from DB
                $sSql = "DELETE FROM oxobject2attribute WHERE oxid='$sValueID' ";
            }
            
            if (($sValueID == '') && ($sAttrValue != '')) {   //attribute doesn't exists, value received --> insert new value
                $sNewUid = oxUtilsObject::getInstance()->generateUID();
                $sSql = "INSERT INTO $sOxvObject2Attribute (OXID, OXOBJECTID, OXATTRID, OXVALUE, OXPOS) VALUES ('$sNewUid', '$sOXID', '$sAttrID', $sAttrValue, 0)";
            }
            
            if (($sValueID == '') && ($sAttrValue == '')) {   //attribute doesn't exists, no value received --> do nothing
                // nothing to do 
            }
            
            // db changes
            if ($sSql != "") {
                $oDb->execute($sSql);
            }
        }
         
     }
}
