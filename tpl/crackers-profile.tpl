{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


      <div id="main" class="content leadership-profile">
        <div id="crackerscontent" data-hires="true">
    <div class="profile-wrapper">
        <div class="profile">
            <div class="profile-titles">
                <h2>
                {$cracker.name}
                </h2>    
                <p>
                "{$cracker.slogan}"
                </p>
                <h3>{$API->LANG->_('CRACKER_CRACKED_APPS',$cracker.numapps,$cracker.clicks)}</h3>
            </div>
            
        <div style="overflow-y: auto; overflow-x: visible; height: 396px; margin-top:25px;" class="profile-copy-wrapper"><div style="top: 0px; width:300px;" id="profile-copy">
                           
                {if $cracker.story}{$cracker.story}{else}<h1>{$API->LANG->_('No profile story')}</h1>{/if}
                
            </div></div><a href="javascript://" class="truncate">{$API->LANG->_('Scroll down to continue reading')}</a></div>
    </div>
    <div class="portrait-fade"></div>
                <img src="{if $cracker.background}{$cracker.background}{else}img/crackers/no_background.png{/if}" alt="" class="portrait" data-hires="false" height="657" width="575">
    </div>

    </div>


{include file="footer.tpl"}