[{include file="headitem.tpl" title="CONTENT_MAIN_TITLE"|oxmultilangassign}]

<style type="text/css">
<!--
.externallink {
  background-position: center right;
  background-repeat: no-repeat;
  background-image: -webkit-linear-gradient(transparent,transparent),url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiP…AyLjg4MS0yLjg4MS0xLjg1MS0xLjg1MXoiIGZpbGw9IiNmZmYiLz48L2c+PC9nPjwvc3ZnPg==);
  background-image: linear-gradient(transparent,transparent),url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiP…AyLjg4MS0yLjg4MS0xLjg1MS0xLjg1MXoiIGZpbGw9IiNmZmYiLz48L2c+PC9nPjwvc3ZnPg==);
  padding-right: 13px;
}
-->
</style>

[{if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="oxid" value="[{ $oxid }]">
    <input type="hidden" name="cl" value="moe_backup_admin_main">
    <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

        <table cellspacing="0" cellpadding="0" border="0" width="98%">
          <colgroup><col width="30%"><col width="5%"><col width="65%"></colgroup>
          <form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
          [{ $oViewConf->getHiddenSid() }]
          <input type="hidden" name="cl" value="moe_backup_admin_main">
          <input type="hidden" name="fnc" value="">
          <input type="hidden" name="oxid" value="[{ $oxid }]">
          <input type="hidden" name="editval[moe_backup__oxid]" value="[{ $oxid }]">
          <tr>
            <td valign="top" class="edittext" width="200">
              <table cellspacing="0" cellpadding="0" border="0">

                [{block name="moe_backup_admin_main_form"}]
                    <tr>
                      <td class="edittext">
                      [{ oxmultilang ident="GENERAL_TITLE" }]
                      </td>
                      <td class="edittext">
                      <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__ocbtitle->fldmax_length}]" name="editval[moe_backup__ocbtitle]" value="[{$edit->moe_backup__ocbtitle->value}]" [{ $readonly }]>
                      [{ oxinputhelp ident="HELP_GENERAL_TITLE" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                      [{ oxmultilang ident="GENERAL_DATE" }]
                      </td>
                      <td class="edittext">
                      <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__ocbdatetime->fldmax_length}]" name="editval[moe_backup__ocbdatetime]" value="[{$edit->moe_backup__ocbdatetime->value}]" [{ $readonly }]>
                      [{ oxinputhelp ident="HELP_GENERAL_DATE" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                      [{ oxmultilang ident="MOE_FILEPREFIX" }]
                      </td>
                      <td class="edittext">
                      <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__moefileprefix->fldmax_length}]" name="editval[moe_backup__moefileprefix]" value="[{$edit->moe_backup__moefileprefix->value}]" [{ $readonly }]>
                      [{ oxinputhelp ident="HELP_MOE_FILEPREFIX" }]
                      </td>
                    </tr>
                    [{*
                    <tr>
                      <td class="edittext">
                      [{ oxmultilang ident="OCB_PLACE" }]
                      </td>
                      <td class="edittext">
                      <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__ocbplace->fldmax_length}]" name="editval[moe_backup__ocbplace]" value="[{$edit->moe_backup__ocbplace->value}]" [{ $readonly }]>
                      [{ oxinputhelp ident="HELP_OCB_PLACE" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                      [{ oxmultilang ident="OCB_EVENT" }]
                      </td>
                      <td class="edittext">
                      <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__ocbevent->fldmax_length}]" name="editval[moe_backup__ocbevent]" value="[{$edit->moe_backup__ocbevent->value}]" [{ $readonly }]>
                      [{ oxinputhelp ident="HELP_OCB_EVENT" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                      [{ oxmultilang ident="OCB_LAT" }]
                      </td>
                      <td class="edittext">
                      <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__ocblat->fldmax_length}]" name="editval[moe_backup__ocblat]" value="[{$edit->moe_backup__ocblat->value}]" [{ $readonly }]>
                      [{ oxinputhelp ident="HELP_OCB_LAT" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                      [{ oxmultilang ident="OCB_LONG" }]
                      </td>
                      <td class="edittext">
                      <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__ocblong->fldmax_length}]" name="editval[moe_backup__ocblong]" value="[{$edit->moe_backup__ocblong->value}]" [{ $readonly }]>
                      [{ oxinputhelp ident="HELP_OCB_LONG" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                      [{ oxmultilang ident="OCB_TOURDATES_MAIN_TICKET" }]
                      </td>
                      <td class="edittext">
                        <select name="editval[moe_backup__ocbticket]" class="editinput" [{ $readonly }]>
                            <option value="0">---</option>
                            [{foreach from=$productlist item=product}]
                                <option value="[{ $product->oxarticles__oxid->value }]" [{if $product->oxarticles__oxid->value == $edit->moe_backup__ocbticket->value}]SELECTED[{/if}]>[{ $product->oxarticles__oxtitle->value|oxtruncate:33:"..":true }]</option>
                            [{/foreach}]
                        </select>
                        [{ oxinputhelp ident="HELP_OCB_TOURDATES_MAIN_TICKET" }]
                      </td>
                    </tr>
                    *}]
                    <tr>
                      <td class="edittext">
                        [{ oxmultilang ident="MOE_BACKUPDIR" }]
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__moebackupdir->fldmax_length}]" name="editval[moe_backup__moebackupdir]" value="[{$edit->moe_backup__moebackupdir->value}]" [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_MOE_BACKUPDIR" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                        [{ oxmultilang ident="MOE_LOGGINGDIR" }]
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__moeloggingdir->fldmax_length}]" name="editval[moe_backup__moeloggingdir]" value="[{$edit->moe_backup__moeloggingdir->value}]" [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_MOE_LOGGINGDIR" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                        [{ oxmultilang ident="MOE_INCLUDE" }]
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__moeinclude->fldmax_length}]" name="editval[moe_backup__moeinclude]" value="[{$edit->moe_backup__moeinclude->value}]" [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_MOE_INCLUDE" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                        [{ oxmultilang ident="MOE_EXCLUDE" }]
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__moeexclude->fldmax_length}]" name="editval[moe_backup__moeexclude]" value="[{$edit->moe_backup__moeexclude->value}]" [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_MOE_EXCLUDE" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                        [{ oxmultilang ident="MOE_COMPRESSIONSCHEME" }]
                      </td>
                      <td class="edittext">
                        <input type="text" class="editinput" size="28" maxlength="[{$edit->moe_backup__moecompressionscheme->fldmax_length}]" name="editval[moe_backup__moecompressionscheme]" value="[{$edit->moe_backup__moecompressionscheme->value}]" [{ $readonly }]>
                        [{ oxinputhelp ident="HELP_MOE_COMPRESSIONSCHEME" }]
                      </td>
                    </tr>
                    <tr>
                      <td class="edittext">
                        [{ oxmultilang ident="MOE_DESCRIPTION" }]
                      </td>
                      <td class="edittext">
                        <textarea type="text" class="editinput" cols="27" rows="4" name="editval[moe_backup__moedescription]" [{ $readonly }]>[{$edit->moe_backup__moedescription->value}]</textarea>
                        [{ oxinputhelp ident="HELP_MOE_DESCRIPTION" }]
                      </td>
                    </tr>
                    [{*
                    <tr>
                      <td class="edittext" colspan="2">
                      [{include file="language_edit.tpl"}]<br>
                      </td>
                    </tr>
                    *}]
                [{/block}]
                <tr>
                  <td class="edittext">
                  </td>
                  <td class="edittext">
                  <input type="submit" class="edittext" name="saveContent" value="[{ oxmultilang ident="GENERAL_SAVE" }]" onClick="Javascript:document.myedit.fnc.value='save'"" [{ $readonly }]><br>
                  </td>
                </tr>
                [{if $edit->moe_backup__oxid->value != ""}]
                <tr>
                  <td colspan="2">
                    <br>
                    <a href="[{$edit->getBackupStarterFile()}][{$edit->moe_backup__oxid->value}]" target="_blank" class="externallink">Backup Link</a>
                  </td>
                </tr>
                [{/if}]
              </table>
            </td>
          </tr>
     </table>
    </form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
