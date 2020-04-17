[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
    window.onload = function ()
    {
        [{if $updatelist == 1}]
            top.oxid.admin.updateList('[{$oxid}]');
        [{/if}]
        var oField = top.oxid.admin.getLockTarget();
        oField.onchange = oField.onkeyup = oField.onmouseout = top.oxid.admin.unlockSave;
    }
//-->
</script>

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="oxidCopy" value="[{$oxid}]">
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
    <input type="hidden" name="language" value="[{$actlang}]">
</form>

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="voxid" value="[{$oxid}]">
    <input type="hidden" name="oxparentid" value="[{$oxparentid}]">
    <input type="hidden" name="editval[oxcountry__oxid]" value="[{$oxid}]">
    <input type="hidden" name="language" value="[{$actlang}]">

    <table cellspacing="0" cellpadding="0" border="0" width="98%">
        <tr>
            <td valign="top" class="edittext" style="width: 45%">

                <table cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td class="edittext" width="120">
                            <label for="d3geoipshop">[{oxmultilang ident="D3_GEOIP_SELSHOP"}]:</label>
                        </td>
                        <td class="edittext">
                            <SELECT id="d3geoipshop" name="editval[oxcountry__d3geoipshop]" class="edittext" onchange="document.getElementById('myedit').fnc.value = 'saveshop'; document.getElementById('myedit').submit();" [{if !$oView->getModCfgValue('blChangeShop')}]disabled[{/if}]>
                                <option value="-1">[{oxmultilang ident="D3_GEOIP_CUSTSELSHOP"}]</option>
                                [{foreach from=$oView->getShopList() item=shop}]
                                    <option value="[{$shop->oxshops__oxid->value}]" [{if $edit->oxcountry__d3geoipshop->value == $shop->oxshops__oxid->value}]selected[{/if}]>[{$shop->oxshops__oxname->value}]</option>
                                [{/foreach}]
                            </SELECT>[{if !$oView->getModCfgValue('blChangeShop')}] [{oxmultilang ident="D3_GEOIP_DISABLED"}][{/if}]
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext">
                            <label for="d3geoiplang">[{oxmultilang ident="D3_GEOIP_SELLANG"}]:</label>
                        </td>
                        <td class="edittext">
                            <SELECT id="d3geoiplang" name="editval[oxcountry__d3geoiplang]" class="edittext"  [{if !$oView->getModCfgValue('blChangeLang')}]disabled[{/if}]>
                                <option value="-1">[{oxmultilang ident="D3_GEOIP_CUSTSELLANG"}]</option>
                                [{foreach from=$oView->getLangList() item=lang}]
                                    <option value="[{$lang->id}]" [{if $edit->oxcountry__d3geoiplang->value == $lang->id}]selected[{/if}]>[{$lang->name}]</option>
                                [{/foreach}]
                            </SELECT>[{if !$oView->getModCfgValue('blChangeLang')}] [{oxmultilang ident="D3_GEOIP_DISABLED"}][{/if}]
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext">
                            <label for="d3geoipcur">[{oxmultilang ident="D3_GEOIP_SELCUR"}]:</label>
                        </td>
                        <td class="edittext">
                            <SELECT id="d3geoipcur" name="editval[oxcountry__d3geoipcur]" class="edittext"  [{if !$oView->getModCfgValue('blChangeCurr')}]disabled[{/if}]>
                                <option value="-1">[{oxmultilang ident="D3_GEOIP_CUSTSELCUR"}]</option>
                                [{foreach from=$oView->getCurList() item=cur}]
                                    <option value="[{$cur->id}]" [{if $edit->oxcountry__d3geoipcur->value == $cur->id}]selected[{/if}]>[{$cur->name}] ([{$cur->sign}])</option>
                                [{/foreach}]
                            </SELECT>[{if !$oView->getModCfgValue('blChangeCurr')}] [{oxmultilang ident="D3_GEOIP_DISABLED"}][{/if}]
                        </td>
                    </tr>
                    <tr>
                        <td class="edittext"><br><br>
                        </td>
                        <td class="edittext"><br><br>
                            <input type="submit" class="edittext" id="oLockButton" name="saveArticle" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit.fnc.value='save'"" [{$readonly}] [{if !$edit->oxcountry__oxtitle->value && !$oxparentid}]disabled[{/if}]><br>
                        </td>
                    </tr>
                </table>
            </td>
            <td valign="top" class="edittext" align="left" width="10%">
                [{oxmultilang ident="D3_GEOIP_OR"}]
            </td>
            <!-- Anfang rechte Seite -->
            <td valign="top" class="edittext" align="left" width="45%">
                <table cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td class="edittext" width="120">
                            <label for="d3geoipurl">[{oxmultilang ident="D3_GEOIP_SELURL"}]:</label>
                        </td>
                        <td class="edittext">
                            <input id="d3geoipurl" type="text" maxlength="255" size="50" name="editval[oxcountry__d3geoipurl]" value="[{$edit->oxcountry__d3geoipurl->value}]"  [{if !$oView->getModCfgValue('blChangeURL')}]disabled[{/if}]> [{if !$oView->getModCfgValue('blChangeURL')}] [{oxmultilang ident="D3_GEOIP_DISABLED"}][{/if}]
                            [{oxinputhelp ident="D3_GEOIP_SELURL_DESC"}]
                        </td>
                    </tr>
                </table>
            </td>
            <!-- Ende rechte Seite -->

        </tr>
    </table>
</form>

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
