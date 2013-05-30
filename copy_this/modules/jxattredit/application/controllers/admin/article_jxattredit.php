<?php
/**
 *    This file is part of OXID eShop Community Edition.
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
 * @link      http://www.oxid-esales.com
 * @package   admin
 * @copyright (C) OXID eSales AG 2003-2010
 * @version OXID eShop CE
 * @version   SVN: $Id: oxattrs.php 27134 2010-04-09 13:50:28Z arvydas $
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
        $myConfig = $this->getConfig();

        parent::render();
        $oSmarty = oxUtilsView::getInstance()->getSmarty();
        $oSmarty->assign( "oViewConf", $this->_aViewData["oViewConf"]);
        $oSmarty->assign( "shop", $this->_aViewData["shop"]);
        
        $this->_aViewData["edit"] = $oArticle = oxNew( "oxarticle");
        $soxId = oxConfig::getParameter( "oxid");
        
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

            $sSql1 = "SELECT oxid, oxtitle FROM oxattribute a ORDER BY oxtitle";
            $aAttrList = array();
            $i = 0;
            $oDb = oxDb::getDb( oxDB::FETCH_MODE_ASSOC );
            $rs1 = $oDb->Execute($sSql1);
            while (!$rs1->EOF) {
                /////array_push($aAttrList, $rs1->fields);
                //echo $rs1->fields['oxid'].'/';
                $sSql2 = "SELECT DISTINCT oxvalue FROM oxobject2attribute WHERE oxattrid = '" . $rs1->fields['oxid'] . "' ORDER BY OXVALUE";
                $aAttrValues = array();
                $rs2 = $oDb->Execute($sSql2);
                /*echo '<hr><pre>';
                print_r($rs2);
                echo '</pre>';*/
                while (!$rs2->EOF) {
                    //$aAttrList[$i]['oxvalues']
                    //echo $rs2->fields['oxvalue'].'<br/>';
                    array_push($aAttrValues, $rs2->fields['oxvalue']);
                    $rs2->MoveNext();
                }
                /*echo '<hr><pre>';
                print_r($aAttrValues);
                echo '</pre>';
                echo '<hr>';*/
                $sSql3 = "SELECT oxid AS voxid, oxvalue FROM oxobject2attribute WHERE oxobjectid = '$soxId' AND oxattrid = '" . $rs1->fields['oxid'] . "' ";
                //echo '<hr>'.$sSql3.'<hr>';
                $rs3 = $oDb->Execute($sSql3);
                /*echo '<hr><hr><pre>';
                print_r($rs3);
                echo '</pre>';*/
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
            /*echo '<pre>';
            print_r($aAttrList);
            echo '</pre>';*/
            $nAttrHalf = round(($i+2)/2, PHP_ROUND_HALF_DOWN);

            $sSql = "SELECT a.oxtitle AS oxtitle, 'Testwert' as oxvalue FROM oxattribute a ORDER BY oxpos, oxtitle";
            $aAttributes = array();
            $rs = $oDb->Execute($sSql);
            while (!$rs->EOF) {
                array_push($aAttributes, $rs->fields);
                $rs->MoveNext();
            }

            $oSmarty->assign("nAttrHalf", $nAttrHalf);
            $oSmarty->assign("aAttrList", $aAttrList);
            $oSmarty->assign("aAttributes", $aAttributes);
        }

        return $this->_sThisTemplate;
     }

     
     
     
     public function saveAllAttrs($sOXID = null, $aParams = null)
     {

        if ( !isset( $sOXID ) && !isset( $aParams ) ) {
            $sOXID   = oxConfig::getParameter( "voxid" );
            $aParams = oxConfig::getParameter( "editval" );
        }
         
// for later, on inserting new attribute values
                //$sUid = $myUtilsObject->generateUid();
                // --->  $sUid = oxUtilsObject::getInstance()->generateUID();
                // --->  echo "-".$sUid."-<hr>";
                //array_push($aAttrList, $rs1->fields);
        $oDb = oxDb::getDb();
        $sOXID = oxConfig::getParameter( "oxid" );
        
        $sSql = "";
        $iRows = oxConfig::getParameter( "rownum" );
        for ($i = 1; $i <= $iRows; $i++) {
            // ... if exist or not
            $sValueID = oxConfig::getParameter( "oxvalueid_$i" );
            $sAttrID = oxConfig::getParameter( "oxattrid_$i" );
            $sAttrValue = oxConfig::getParameter( "attrval_$i" );
            //echo " - ".$sValueID." - ".$sAttrID." - ".$sAttrValue." - <hr>";
            
            $sSql = "";
            
            if (($sValueID != '') && ($sAttrValue != '')) {   //attribute exists and not empty value received --> update
                $sSql = "UPDATE oxobject2attribute SET OXVALUE='$sAttrValue' WHERE OXID='$sValueID' ";
            }
            
            if (($sValueID != '') && ($sAttrValue == '')) {   //attribute exists, but empty value --> delete from DB
                $sSql = "DELETE FROM oxobject2attribute WHERE OXID='$sValueID' ";
            }
            
            if (($sValueID == '') && ($sAttrValue != '')) {   //attribute doesn't exists, value received --> insert new value
                $sNewUid = oxUtilsObject::getInstance()->generateUID();
                $sSql = "INSERT INTO oxobject2attribute (OXID, OXOBJECTID, OXATTRID, OXVALUE, OXPOS) VALUES ('$sNewUid', '$sOXID', '$sAttrID', '$sAttrValue', 0)";
            }
            
            if (($sValueID == '') && ($sAttrValue == '')) {   //attribute doesn't exists, no value received --> do nothing
                // nothing to do 
            }
            
            // db zugriff
            if ($sSql != "") {
                //echo $sSql.'<hr>';
                $oDb->execute($sSql);
            }
        }
         
     }
}
