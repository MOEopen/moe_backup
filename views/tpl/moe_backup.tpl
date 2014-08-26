
	<h1>Backup aller ShopFiles</h1>
	<h3>[{ oxmultilang ident="moe_BackupTyp" }]<h3>
  [{ foreach from=$aCommands key=butyp item=bucommands }]
    [{ foreach from=$bucommands  key=buart item=buString }]
       [{ if $buart == Link }]
          [{  $buString  }]<br />
       [{ /if }]
    [{ /foreach }]           
  [{ /foreach }]
  [{ $moe_backup }] 
	
	