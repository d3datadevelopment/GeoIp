[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
<!--
[{if $updatelist == 1}]
    UpdateList('[{$oxid}]');
[{/if}]

function UpdateList( sID)
{
    var oSearch = parent.list.document.getElementById("search");
    oSearch.oxid.value=sID;
    oSearch.fnc.value='';
    oSearch.submit();
}

function EditThis( sID)
{
    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value=sID;
    oTransfer.cl.value='';
    oTransfer.submit();

    var oSearch = parent.list.document.getElementById("search");
    oSearch.actedit.value = 0;
    oSearch.oxid.value=sID;
    oSearch.submit();
}

function _groupExp(el) {
    var _cur = el.parentNode;

    if (_cur.className == "exp") _cur.className = "";
      else _cur.className = "exp";
}

var sOldSettingElem = '';

function showFormatSettings(sElemId, visible, blUseOldElem)
{
    if (blUseOldElem && sOldSettingElem) {
        document.getElementById(sOldSettingElem).style.display = 'none';
        sOldSettingElem = sElemId;
    } else if (blUseOldElem) {
        document.getElementById('settings_global').style.display = 'none';
        document.getElementById('settingstxt_global').style.display = 'none';
        sOldSettingElem = sElemId;
    }

    if (visible == true) {
        document.getElementById(sElemId).style.display = 'block';
    } else {
        document.getElementById(sElemId).style.display = 'none';
    }
}

-->
</script>

<style type="text/css">
<!--
.questbox{
    background-color: #07f;
    color: white;
    float: right;
    position: relative;
    display: block;
    padding: 1px 4px;
    font-weight: bold;
    z-index: 98;
    cursor: help;
    font-family: Verdana,Arial,Helvetica,sans-serif;
    font-size: 10px;
    line-height: 12px;
}

.helptextbox{
    background-color: white;
    color: black;
    border: 1px solid black;
    position: absolute;
    overflow: hidden;
    padding: 5px;
    margin-top: 15px;
    width: 300px;
    z-index: 99;
}

fieldset{
    border: 1px inset black;
    background-color: #F0F0F0;
}

legend{
    font-weight: bold;
}

dl dt{
    font-weight: normal;
    width: 55%;
}

.ext_edittext {
    padding: 2px;
}

td.edittext {
    white-space: normal;
}
-->
</style>

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
    <input type="hidden" name="actshop" value="[{$shop->id}]">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
    <input type="hidden" name="fnc" value="save">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="editval[d3_cfg_mod__oxid]" value="[{$oxid}]">

    <table border="0" width="98%">
        <tr>
            <td valign="top" class="edittext">

                [{include file="d3_cfg_mod_active.tpl"}]

                <div class="groupExp">
                    <div class="">
                        <a class="rc" onclick="_groupExp(this); return false;" href="#">
                            <b>
                                [{oxmultilang ident="D3_GEOIP_SET_OPTIONS"}]
                            </b>
                        </a>
                        <dl>
                            <dt>
                                [{oxmultilang ident="D3_GEOIP_SET_OPTIONS_CHANGESHOP"}]
                            </dt>
                            <dd>
                                <input type="hidden" name="value[blChangeShop]" value="0">
                                <input class="edittext ext_edittext" type="checkbox" name="value[blChangeShop]" value='1' [{if $edit->getValue('blChangeShop') == 1}]checked[{/if}]>
                                [{oxinputhelp ident="D3_GEOIP_SET_OPTIONS_CHANGESHOP_DESC"}]
                            </dd>
                            <div class="spacer"></div>
                        </dl>
                        <dl>
                            <dt>
                                [{oxmultilang ident="D3_GEOIP_SET_OPTIONS_CHANGECURR"}]
                            </dt>
                            <dd>
                                <input type="hidden" name="value[blChangeCurr]" value="0">
                                <input class="edittext ext_edittext" type="checkbox" name="value[blChangeCurr]" value='1' [{if $edit->getValue('blChangeCurr') == 1}]checked[{/if}]>
                                [{oxinputhelp ident="D3_GEOIP_SET_OPTIONS_CHANGECURR_DESC"}]
                            </dd>
                            <div class="spacer"></div>
                        </dl>
                        <dl>
                            <dt>
                                [{oxmultilang ident="D3_GEOIP_SET_OPTIONS_CHANGELANG"}]
                            </dt>
                            <dd>
                                <input type="hidden" name="value[blChangeLang]" value="0">
                                <input class="edittext ext_edittext" type="checkbox" name="value[blChangeLang]" value='1' [{if $edit->getValue('blChangeLang') == 1}]checked[{/if}]>
                                [{oxinputhelp ident="D3_GEOIP_SET_OPTIONS_CHANGELANG_DESC"}]
                            </dd>
                            <div class="spacer"></div>
                        </dl>
                        <dl>
                            <dt>
                                [{oxmultilang ident="D3_GEOIP_SET_OPTIONS_CHANGEURL"}]
                            </dt>
                            <dd>
                                <input type="hidden" name="value[blChangeURL]" value="0">
                                <input class="edittext ext_edittext" type="checkbox" name="value[blChangeURL]" value='1' [{if $edit->getValue('blChangeURL') == 1}]checked[{/if}]>
                                [{oxinputhelp ident="D3_GEOIP_SET_OPTIONS_CHANGEURL_DESC"}]
                            </dd>
                            <div class="spacer"></div>
                        </dl>
                        <dl>
                            <dt>
                                [{oxmultilang ident="D3_GEOIP_SET_OPTIONS_NOCOUNTRY"}]
                                <input type="hidden" name="value[blUseFallback]" value="0">
                                <input class="edittext ext_edittext" type="checkbox" name="value[blUseFallback]" value='1' [{if $edit->getValue('blUseFallback') == 1}]checked[{/if}]>
                            </dt>
                            <dd>
                                <select size="5" name="value[sFallbackCountryId]">
                                    [{foreach from=$oView->getCountryList() item="oCountry"}]
                                        <option value="[{$oCountry->getId()}]" [{if $edit->getValue('sFallbackCountryId') == $oCountry->getId()}] selected[{/if}]>[{$oCountry->oxcountry__oxtitle->value}]</option>
                                    [{/foreach}]
                                </select>
                                [{oxinputhelp ident="D3_GEOIP_SET_OPTIONS_NOCOUNTRY_DESC"}]
                            </dd>
                            <div class="spacer"></div>
                        </dl>
                    </div>
                </div>

                <div class="groupExp">
                    <div class="">
                        <a class="rc" onclick="_groupExp(this); return false;" href="#">
                            <b>
                                [{oxmultilang ident="D3_GEOIP_SET_IP"}]
                            </b>
                        </a>
                        <dl>
                            <dt>
                                [{oxmultilang ident="D3_GEOIP_SET_IP_TESTIP"}]
                                <input type="hidden" name="value[blUseTestIp]" value="0">
                                <input class="edittext ext_edittext" type="checkbox" name="value[blUseTestIp]" value='1' [{if $edit->getValue('blUseTestIp') == 1}]checked[{/if}]>
                            </dt>
                            <dd>
                                <input type="text" maxlength="39" size="17" name="value[sTestIp]" value="[{$edit->getValue('sTestIp')}]">
                                [{oxinputhelp ident="D3_GEOIP_SET_IP_TESTIP_DESC"}]
                            </dd>
                            <div class="spacer"></div>
                        </dl>
                        <dl>
                            <dt>
                                [{oxmultilang ident="D3_GEOIP_SET_IP_TESTCOUNTRY"}]
                                <input type="hidden" name="value[blUseTestCountry]" value="0">
                                <input class="edittext ext_edittext" type="checkbox" name="value[blUseTestCountry]" value='1' [{if $edit->getValue('blUseTestCountry') == 1}]checked[{/if}]>
                            </dt>
                            <dd>
                                <select name="value[sTestCountryIp]" size="1" class="edittext ext_edittext">
                                    [{foreach from=$oView->getIPCountryList() item="oCountry"}]
                                        <option value="[{$oCountry->getFieldData('IP')}]" [{if $edit->getValue('sTestCountryIp') == $oCountry->getFieldData('IP')}] selected[{/if}]>[{$oCountry->getFieldData('oxtitle')}][{if !$oCountry->getFieldData('oxactive')}] [{oxmultilang ident="D3_GEOIP_SET_IP_TESTCOUNTRY_INACTIVE"}][{/if}]</option>
                                    [{/foreach}]
                                </select>
                                [{oxinputhelp ident="D3_GEOIP_SET_IP_TESTCOUNTRY_DESC"}]
                            </dd>
                            <div class="spacer"></div>
                        </dl>
                        <dl>
                            <dt>
                                [{oxmultilang ident="D3_GEOIP_SET_IP_CHECKIP"}]
                            </dt>
                            <dd>
                                <input type="text" maxlength="39" size="17" name="value[sCheckIp]" value="[{$edit->getValue('sCheckIp')}]">
                                [{oxinputhelp ident="D3_GEOIP_SET_IP_CHECKIP_DESC"}]

                                [{if $edit->getValue('sCheckIp')}]
                                    [{$oView->getIpCountry($edit->getValue('sCheckIp'))}]
                                [{/if}]
                            </dd>
                            <div class="spacer"></div>
                        </dl>
                    </div>
                </div>

                <table width="100%">
                    <tr>
                        <td class="edittext ext_edittext" align="left">
                            <span class="d3modcfg_btn icon status_ok">
                                <input type="submit" name="save" value="[{oxmultilang ident="D3_GENERAL_GEOIP_SAVE"}]">
                                <div></div>
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>

[{include file="d3_cfg_mod_inc.tpl"}]

<script type="text/javascript">
    if (parent.parent) {
        parent.parent.sShopTitle = "[{$actshopobj->oxshops__oxname->getRawValue()|oxaddslashes}]";
        parent.parent.sMenuItem = "[{oxmultilang ident="d3mxgeoip"}]";
        parent.parent.sMenuSubItem = "[{oxmultilang ident="d3mxgeoip_settings"}]";
        parent.parent.sWorkArea = "[{$_act}]";
        parent.parent.setTitle();
    }
</script>