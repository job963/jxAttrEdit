[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
function loadLang(obj)
{
    var langvar = document.getElementById("agblang");
    if (langvar != null )
        langvar.value = obj.value;
    document.myedit.submit();
}
function editThis( sID )
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = sID;
    oTransfer.cl.value = top.basefrm.list.sDefClass;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.actedit.value = 0;
    oSearch.submit();
}

function JumpVariant(obj)
{
    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=obj.value;
    oTransfer.cl.value='article_jxattredit';

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = parent.list.document.getElementById("search");
    oSearch.oxid.value=obj.value;
    oSearch.submit();
}
//-->
</script>

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

[{capture name="subtablehead"}]
    <tr>
        <td class="listheader" width="25%">[{ oxmultilang ident="ATTRIBUTE_LIST_MENUSUBITEM" }]</td>
        <td class="listheader" width="45%"><span style="float:left; display:block; width:95%;">[{ oxmultilang ident="ARTICLE_ATTRIBS_VALUE" }]</span></td>
        <td class="listheader" width="30%"><span style="float:left; display:block; width:95%;">[{ oxmultilang ident="ARTICLE_ATTRIBS_PROPOSAL" }]</span></td>
    </tr>
[{/capture}]

<form name="transfer" id="transfer" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="article_jxattredit">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>


[{*debug*}]
[{*<h3>[{ oxmultilang ident="ARTICLE_ATTRIBS_EDITOR" }]</h3>*}]
<form name="allattredit" id="allattredit" action="[{ $shop->selflink }]" method="post">
    [{ $shop->hiddensid }]
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
    <input type="hidden" name="cl" value="article_jxattredit">
    <input type="hidden" name="fnc" value="saveallattrs">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="parentvarname" value="[{$edit->oxarticles__oxvarname->value}]">
    [{ assign var="onChangeStyle" value="this.style.color='blue';this.style.fontWeight='bold';" }]
    [{ assign var="onSelectChange" value="var txtbox = document.getElementById('show');" }]

    <div style="font-weight:bold; padding-bottom:6px;">
    [{* if $oxparentid }]
        [{$edit->oxarticles__oxartnum->value}] - [{ $parentarticle->oxarticles__oxtitle->value}], [{$edit->oxarticles__oxvarselect->value}]
    [{else}]
        [{$edit->oxarticles__oxartnum->value}] - [{$edit->oxarticles__oxtitle->value}]
    [{/if*}]
        <select style="font-weight:bold;" onChange="Javascript:JumpVariant(this);">
        [{foreach name=prodlist item=Product from=$aProdList}]
        <option value="[{$Product.oxid}]" [{if $Product.oxid==$edit->oxarticles__oxid}]selected[{/if}]>[{$Product.oxartnum}] - [{$Product.oxtitle}]</option>
        [{/foreach}]
        </select>
    </div>
    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;"><tr>
       <td valign="top" style="width:49%;">
       <table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
            [{$smarty.capture.subtablehead}]
            [{foreach name=outer item=Attribute from=$aAttrList}]
                [{ cycle values="listitem,listitem2" assign="listclass" }]
                [{ assign var="rownum" value=$rownum+1 }]
                [{if $rownum == $nAttrHalf}]
                    </table>
                    </td>
                    <td>&nbsp;</td>
                    <td valign="top" style="width:49%;">
                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
                        [{$smarty.capture.subtablehead}]
                [{/if}]
                <tr>
                    <td class="[{ $listclass }]">
                        <input type="hidden" name="oxattrid_[{$rownum}]" value="[{$Attribute.oxid}]">
                        <input type="hidden" name="oxvalueid_[{$rownum}]" value="[{$Attribute.oxvalueid}]">
                        &nbsp;[{ $Attribute.oxtitle }]&nbsp;&nbsp;
                    </td>
                    <td class="[{ $listclass }]">
                        <input type="text" size="30" maxlength="255" id="attrval_[{$rownum}]" name="attrval_[{$rownum}]" value="[{ $Attribute.oxartvalue }]" 
                               style="width:95%" onChange="[{$onChangeStyle}]">
                    </td>
                    <td class="[{ $listclass }]">
                        <select style="width:95%;" onchange="document.getElementById('attrval_[{$rownum}]').value=this.options[this.selectedIndex].value;
                            document.getElementById('attrval_[{$rownum}]').style.color='blue';document.getElementById('attrval_[{$rownum}]').style.fontWeight='bold';">
                            <option value=""></option>
                            [{foreach name=outer2 item=AttrValue from=$Attribute.oxvalues}]
                                <option value="[{$AttrValue}]">[{$AttrValue}]</option>
                            [{/foreach}]
                        </select>
                    </td>
                </tr>
            [{/foreach}]
            <input type="hidden" name="rownum" value="[{$rownum}]">

            <tr>
                <td colspan="2" align="right">&nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                  <input class="edittext" type="submit" 
                         onClick="document.forms['allattredit'].elements['parentvarname'].value = document.forms['search'].elements['editval[oxarticles__oxvarname]'].value;" 
                         value=" [{ oxmultilang ident="ARTICLE_ATTRIBUTE_SAVE" }]" [{ $readonly }]>
                </td>
            </tr>

        </table>
    </td></tr></table>
</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]