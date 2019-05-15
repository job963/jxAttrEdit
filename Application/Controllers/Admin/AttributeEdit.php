<?php
/**
 * @package Internetfabrik
 * @author Florian Palme <florian.palme@internetfabrik.de>
 */

namespace ProudCommerce\Jx\AttrEdit\Application\Controllers\Admin;


use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;

class AttributeEdit extends AdminDetailsController
{
    protected $_sThisTemplate = "article_jxattredit.tpl";

    /**
     * @return string
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     * @throws \OxidEsales\Eshop\Core\Exception\SystemComponentException
     */
    public function render()
    {
        parent::render();

        $myConfig = Registry::getConfig();
        /** @var Request $request */
        $request = Registry::get(Request::class);

        $nColumns = $myConfig->getConfigParam("sJxAttrEditNumberOfColumns");
        $this->_aViewData["edit"] = $oArticle = oxNew("oxarticle");
        $soxId = $request->getRequestParameter("oxid");

        if ($soxId != "-1" && isset($soxId)) {
            // load object
            $oArticle->loadInLang($this->_iEditLang, $soxId);

            // load object in other languages
            $oOtherLang = $oArticle->getAvailableInLangs();
            if (!isset($oOtherLang[$this->_iEditLang])) {
                // echo "language entry doesn't exist! using: ".key($oOtherLang);
                $oArticle->loadInLang(key($oOtherLang), $soxId);
            }

            foreach ($oOtherLang as $id => $language) {
                $oLang = new \stdClass();
                $oLang->sLangDesc = $language;
                $oLang->selected = ($id == $this->_iEditLang);
                $this->_aViewData["otherlang"][$id] = clone $oLang;
            }

            // variant handling
            if ($oArticle->oxarticles__oxparentid->value) {
                /** @var Article $oParentArticle */
                $oParentArticle = oxNew(Article::class);
                $oParentArticle->load($oArticle->oxarticles__oxparentid->value);
                $this->_aViewData["parentarticle"] = $oParentArticle;
                $this->_aViewData["oxparentid"] = $oArticle->oxarticles__oxparentid->value;
            }

            $sShopID = $myConfig->getShopID();
            $sOxvArticles = getViewName('oxarticles', $this->_iEditLang, $sShopID);
            $sOxvAttribute = getViewName('oxattribute', $this->_iEditLang, $sShopID);
            $sOxvObject2Attribute = getViewName('oxobject2attribute', $this->_iEditLang, $sShopID);

            $oDb = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

            if ($oArticle->oxarticles__oxparentid->value) {
                $sSrcId = $oArticle->oxarticles__oxparentid->value;
            } else {
                $sSrcId = $soxId;
            }

            $sSql = "SELECT oxid AS oxid, oxartnum AS oxartnum, "
                . "IF(oxparentid='', "
                . "oxtitle, "
                . "IF(oxtitle='', "
                . "CONCAT((SELECT b.oxtitle FROM $sOxvArticles b WHERE b.oxid='$sSrcId'), ' - ', oxvarselect), "
                . "CONCAT(oxtitle, ' - ', oxvarselect) "
                . ") "
                . ") AS oxtitle "
                . "FROM $sOxvArticles "
                . "WHERE "
                . "oxid='$sSrcId' "
                . "OR oxparentid='$sSrcId' "
                . "ORDER BY oxvarselect ";
            $aProdList = $oDb->getAll($sSql);

            $sSql1 = "SELECT "
                . "oxid AS oxid, oxtitle AS oxtitle, oxdisplayinbasket AS oxdisplayinbasket "
                . "FROM $sOxvAttribute a "
                . "ORDER BY oxtitle";

            $aAttrList = $oDb->getAll($sSql1);

            foreach ($aAttrList as $i => $fields) {
                $sSql2 = "SELECT DISTINCT oxvalue AS oxvalue "
                    . "FROM $sOxvObject2Attribute "
                    . "WHERE oxattrid = '" . $fields['oxid'] . "' "
                    . "ORDER BY oxvalue ";
                $aAttrValues = $oDb->getCol($sSql2);

                $sSql3 = "SELECT oxid AS voxid, oxvalue AS oxvalue "
                    . "FROM $sOxvObject2Attribute "
                    . "WHERE oxobjectid = '$soxId' "
                    . "AND oxattrid = '" . $fields['oxid'] . "' ";
                $rs3 = $oDb->getRow($sSql3);

                if (count($rs3)) {
                    $ValueID = $rs3['voxid'];
                    $ArtValue = $rs3['oxvalue'];
                } else {
                    $ValueID = "";
                    $ArtValue = "";
                }

                $aAttrList[$i]['oxvalueid'] = $ValueID;
                $aAttrList[$i]['oxartvalue'] = $ArtValue;
                $aAttrList[$i]['oxvalues'] = $aAttrValues;
                $i++;
            }
            $nAttrSplit = round(($i / $nColumns), 0, PHP_ROUND_HALF_UP);


            $sSql = "SELECT a.oxtitle AS oxtitle, 'Testwert' as oxvalue FROM oxattribute a ORDER BY oxpos, oxtitle";
            $aAttributes = array();
            $aAttributes = $oDb->getAll($sSql);

            $this->_aViewData["nAttrSplit"] = $nAttrSplit;
            $this->_aViewData["nColumns"] = $nColumns;
            $this->_aViewData["aProdList"] = $aProdList;
            $this->_aViewData["aAttrList"] = $aAttrList;
            $this->_aViewData["aAttributes"] = $aAttributes;
        }

        return $this->_sThisTemplate;
    }


    /**
     * aves all attributes of the currently selected article
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function saveAllAttrs()
    {
        $myConfig = Registry::getConfig();
        /** @var Request $request */
        $request = Registry::get(Request::class);

        $sOXID = $request->getRequestParameter("oxid");
        $sOxvObject2Attribute = getViewName('oxobject2attribute', $this->_iEditLang, $myConfig->getShopId());
        $oDb = DatabaseProvider::getDb();

        $sSql = "";
        $iRows = $request->getRequestParameter("rownum");
        for ($i = 0; $i <= $iRows; $i++) {
            $sValueID = $request->getRequestParameter("oxvalueid_$i");
            $sAttrID = $request->getRequestParameter("oxattrid_$i");
            $sAttrValue = $request->getRequestParameter("attrval_$i");

            $sSql = "";

            if (($sValueID != '') && ($sAttrValue != '')) {   //attribute exists and not empty value received --> update
                $sSql = "UPDATE $sOxvObject2Attribute SET oxvalue=" . $oDb->quote($sAttrValue) . " WHERE oxid='$sValueID' ";
            }

            if (($sValueID != '') && ($sAttrValue == '')) {   //attribute exists, but empty value --> delete from DB
                $sSql = "DELETE FROM oxobject2attribute WHERE oxid='$sValueID' ";
            }

            if (($sValueID == '') && ($sAttrValue != '')) {   //attribute doesn't exists, value received --> insert new value
                $sNewUid = Registry::getUtilsObject()->generateUID();
                $sSql = "INSERT INTO $sOxvObject2Attribute (OXID, OXOBJECTID, OXATTRID, OXVALUE, OXPOS) VALUES ('$sNewUid', '$sOXID', '$sAttrID', " . $oDb->quote($sAttrValue) . ", 0)";
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
    public function splitAttributeValues($aValues, $sCurrentValue){
        $aTemp = array();
        $aRet = array();
        foreach ($aValues as $sVal){
            $aParts = explode(', ', $sVal);
            foreach ($aParts as $sPart){
                $aTemp[$sPart] = $sPart;
            }
        }
        sort($aTemp);
        $aCurrentVals = explode(', ', $sCurrentValue);  
        foreach ($aTemp as $sValue){
            if ($sValue){
                if (in_array($sValue, $aCurrentVals)){
                    $aRet[$sValue]['checked'] = 1;
                }
                else{
                    $aRet[$sValue]['checked'] = 0;
                }
                $aRet[$sValue]['value'] = $sValue;
            }
        }
        return $aRet;
    }
}