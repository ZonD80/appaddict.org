{include file='header.tpl'}

<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Archived versions of')} {$appdata.name}</h1>
                        </div>
                        <div class="right">
                            {$API->LANG->_('Total')} {count($links)} {$API->LANG->_('archived versions available')} | <a href="{$API->SEO->make_link('view','trackid',$appdata.trackid)}">{$API->LANG->_('Go back')}</a>
                        </div>
                    </div>

                    <div class="center-stack">
                        <h1>{$API->LANG->_('Select what to download')}:</h1>
                        {if !is_premium()}
                            {literal}
                                <iframe scrolling="no" style="border: 0; width: 728px; height: 90px;" src="//coinurl.com/get.php?id=26498&SSL=1"></iframe>
                            {/literal}
                        {/if}
                        {foreach from=$links item=ldata key=version}
                            <h5>{$API->LANG->_('Version')} {$version}</h5>
                            {foreach from=$ldata item=l}
                                <a href="{if $l.no_redirection}{$l.link_ticket}{else}{$API->SEO->make_link('redirector','lt',$l.link_ticket)}{/if}" rel="nofollow" target="_blank">{$l.host}</a> {if $l.verified}<span style="color:#5fb629;font-weight:bold;">{$API->LANG->_('By')} {$l.cracker} [{$API->LANG->_('Verified')} {$API->LANG->_('cracker')}]</span>{else}
                                {$API->LANG->_('By')} {$l.cracker} [{$API->LANG->_('Unverified')} {$API->LANG->_('cracker')}]{/if}{if ($l.di_compatible)}<br/>[<a href="{$API->SEO->make_link('directinstaller','id',$l.id)}">{$API->LANG->_('DirectInstaller')} {$API->LANG->_('OTA')}</a>]{/if}{if ($l.ss_compatible)}<br/>[<a href="{$API->SEO->make_link('signservice','id',$l.id)}">{$API->LANG->_('Sign & Download')}</a>]{/if}<br/>
                                    <br/>
                                    {/foreach}
                                        {/foreach}
                                        </div>

                                        <div id="left-stack">

                                            {if !is_premium()}
                                                {literal}
                                                    <!-- ads -->
                                                    <div>
                                                        <iframe scrolling="no" style="border: 0; width: 160px; height: 600px;" src="//coinurl.com/get.php?id=26490&SSL=1"></iframe>
                                                    </div>
                                                {/literal}
                                            {/if}
                                        </div>
                                    </div>


                                </div>
                            </div>


                        </div>






                        {include file="footer.tpl"}