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

function switchVariant(obj)
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
function fillAttrValues(checkbox){
    var input = document.getElementById(checkbox.dataset.attr);
    var parent = checkbox.closest(".attrvals");
    var allattrs = parent.querySelectorAll(".attrval");
    var checkedattrs = [].filter.call( allattrs, function( el ) {
       return el.checked;
    });
    var retvalue = '';
    for (var i = 0; i < checkedattrs.length; ++i){
        if (i) retvalue += ', ';
        retvalue += checkedattrs[i].value;
    }
    input.value = retvalue;
    input.onchange();
}
function showAttributes(elementId){
    var allattrvals = document.querySelectorAll(".attrvals");
    [].forEach.call(allattrvals, function(e) {
        e.style.display = 'none';
    });
    document.getElementById(elementId).style.display = 'block';
}
function hideAttributes(elementId){
    document.getElementById(elementId).style.display = 'none';
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

[{ assign var="colCount" value=$nColumns }]
[{if $colCount == 2 }]
    [{ assign var="colWidth" value="49" }]
    [{ assign var="txtWidth" value="30" }]
[{elseif $colCount == 3 }]
    [{ assign var="colWidth" value="33" }]
    [{ assign var="txtWidth" value="20" }]
[{elseif $colCount == 4}]
    [{ assign var="colWidth" value="24" }]
    [{ assign var="txtWidth" value="15" }]
[{/if}]
[{ assign var="colCount" value="4" }]
<form name="allattredit" id="allattredit" action="[{ $shop->selflink }]" method="post" autocomplete="off">>
    [{ $shop->hiddensid }]
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
    <input type="hidden" name="cl" value="article_jxattredit">
    <input type="hidden" name="fnc" value="saveallattrs">
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="parentvarname" value="[{$edit->oxarticles__oxvarname->value}]">
    [{ assign var="onChangeStyle" value="this.style.color='blue';this.style.fontWeight='bold';" }]
    [{ assign var="onSelectChange" value="var txtbox = document.getElementById('show');" }]

    <div style="font-weight:bold; padding-bottom:6px;">
        <select name="editvariant" style="font-weight:bold;" onChange="Javascript:switchVariant(this);">
            [{foreach name=prodlist item=Product from=$aProdList}]
                <option value="[{$Product.oxid}]" [{if $Product.oxid==$edit->oxarticles__oxid}]selected[{/if}]>[{$Product.oxartnum}] - [{$Product.oxtitle}]</option>
            [{/foreach}]
        </select>
        
        <select name="editlanguage" id="test_editlanguage" class="saveinnewlanginput" onChange="Javascript:document.allattredit.fnc.value='';document.allattredit.submit();" [{$custreadonly}]>
            [{foreach from=$otherlang key=lang item=olang}]
                <option value="[{ $lang }]"[{if $olang->selected}]SELECTED[{/if}]>[{ $olang->sLangDesc }]</option>
            [{/foreach}]
        </select>
    </div>
    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;"><tr>
       <td valign="top" style="width:[{$colWidth}]%;">
       <table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
            [{$smarty.capture.subtablehead}]
            [{ assign var="rownum" value=-1 }]
            <colgroup>
                <col width="[{$colWidth}]%">
                <col width="*">
                <col width="[{$colWidth}]%">
                [{if $colCount>=3 }]<col width="*"><col width="[{$colWidth}]%">[{/if}]
                [{if $colCount>=4 }]<col width="*"><col width="[{$colWidth}]%">[{/if}]
            </colgroup>

            [{foreach name=outer item=Attribute from=$aAttrList}]
                [{ cycle values="listitem,listitem2" assign="listclass" }]
                [{ assign var="rownum" value=$rownum+1 }]
                [{if $rownum is div by $nAttrSplit && $rownum != 0}]
                    </table>
                    </td>
                    <td>&nbsp;</td>
                    <td valign="top" style="width:[{$colWidth}]%;">
                    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;">
                        [{$smarty.capture.subtablehead}]
                [{/if}]
                <tr>
                    <td class="[{ $listclass }]">
                        <input type="hidden" name="oxattrid_[{$rownum}]" value="[{$Attribute.oxid}]">
                        <input type="hidden" name="oxvalueid_[{$rownum}]" value="[{$Attribute.oxvalueid}]">
                        &nbsp;[{if $Attribute.oxdisplayinbasket}]<b>[{/if}][{ $Attribute.oxtitle }][{if $Attribute.oxdisplayinbasket}] *</b>[{/if}]&nbsp;&nbsp;
                    </td>
                    <td class="[{ $listclass }]">
                        <input type="text" size="30" maxlength="255" id="attrval_[{$rownum}]" name="attrval_[{$rownum}]" value="[{ $Attribute.oxartvalue }]" 
                               style="width:95%" onChange="[{$onChangeStyle}]" 
                               onfocus="showAttributes('attrlist_[{$rownum}]')">
                    </td>
                    <td class="[{ $listclass }] attrvals" id="attrlist_[{$rownum}]" 
                    style="display: none; border-width: 1px; position: absolute; border-color: #666; 
                    padding: 10px; margin-left: 3px;">
                                
                             [{foreach name=outer2 item=AttrValue from=$oView->splitAttributeValues($Attribute.oxvalues, $Attribute.oxartvalue)}]
                                [{ assign var="attrvalnum" value=$attrvalnum+1 }]
                                <div>
                                <input class="attrval" data-attr="attrval_[{$rownum}]" type="checkbox" id="oxattrvalid_[{$rownum}]_[{$attrvalnum}]" 
                                value="[{$AttrValue.value}]" onchange="fillAttrValues(this)" [{if $AttrValue.checked}]checked="checked"[{/if}]>
                                <label for="oxattrvalid_[{$rownum}]_[{$attrvalnum}]">[{$AttrValue.value}]</label>
                                </div>
                            [{/foreach}]
                    </td>
                </tr>
            [{/foreach}]
            <input type="hidden" name="rownum" value="[{$rownum}]">

            <tr>
                <td colspan="2" align="right">&nbsp;
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>* [{ oxmultilang ident="ARTICLE_ATTRIBS_DISPLAYEDINBASKET" }]</b>
                    <div style="display:inline-block;float:right;"><input class="edittext" type="submit" 
                         onClick="document.forms['allattredit'].elements['parentvarname'].value = document.forms['search'].elements['editval[oxarticles__oxvarname]'].value;" 
                         value=" [{ oxmultilang ident="ARTICLE_ATTRIBUTE_SAVE" }]" [{ $readonly }]></div>
                </td>
            </tr>

        </table>
    </td></tr></table>
</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
