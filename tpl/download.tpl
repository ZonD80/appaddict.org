{include file='header.tpl'}

<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Downloading Content...')}</h1>
                        </div>
                        <div class="right">
                            <!--<div class="gc-badge app-badge"><span>Game Center</span></div>-->

                            <a href="{$API->SEO->make_link('view','trackid',$appdata.trackid)}">{$API->LANG->_('Go back')}</a>{if $API->account['id']} | <a href="{$API->SEO->make_link('upload','url',urlencode("https://itunes.apple.com/{$appdata.store}/{$appdata.type}/id{$appdata.trackid}"))}">{$API->LANG->_('Submit new link')}</a>{/if}
                        </div>
                    </div>

                    <div class="center-stack">
                        <h1>{$API->LANG->_('Select download location')}:</h1>
                        <br>
                        {if !is_premium()}
                            {literal}
                                <iframe scrolling="no" style="border: 0; width: 728px; height: 90px;" src="//coinurl.com/get.php?id=26499&SSL=1"></iframe>

                            {/literal}
                        {/if}

                        {if !$links}
                            <h1>{$API->LANG->_('NO_LINKS_CHECK_ARCHIVE',$API->SEO->make_link('archive','trackid',$appdata.trackid))}</h1>
                        {else}
                            {foreach from=$links item=l}
                                <div style="">
                                    <h1><a href="{if $l.no_redirection}{$l.link_ticket}{else}{$API->SEO->make_link('redirector','lt',$l.link_ticket)}{/if}" rel="nofollow" target="_blank">{$l.host}</a>{if ($l.di_compatible)}<br/>[<a href="{$API->SEO->make_link('directinstaller','id',$l.id)}">{$API->LANG->_('DirectInstaller')} {$API->LANG->_('OTA')}</a>]{/if}{if ($l.ss_compatible)}<br/>[<a href="{$API->SEO->make_link('signservice','id',$l.id)}">{$API->LANG->_('Sign & Download')}</a>]{/if}</h1>
                                    {if $l.verified}<span style="color:#5fb629;font-weight:bold;">{$API->LANG->_('By')} {$l.cracker} [{$API->LANG->_('Verified')} {$API->LANG->_('cracker')}]</span>{else}
                                        {$API->LANG->_('By')} {$l.cracker} [{$API->LANG->_('Unverified')} {$API->LANG->_('cracker')}]{/if}
                                        {if !$l.protected}[<a href="javascript://" onclick="report({$l.id});">{$API->LANG->_('Report broken link')}</a>]{/if}
                                    </div>
                                {/foreach}
                            {/if}
                        </div>

                        <div id="left-stack">

                            {if !is_premium()}
                                {literal}
                                    <iframe scrolling="no" style="border: 0; width: 160px; height: 600px;" src="//coinurl.com/get.php?id=26497&SSL=1"></iframe>

                                {/literal}
                            {/if}

                        </div>
                    </div>


                </div>
            </div>


        </div>






        {include file="footer.tpl"}