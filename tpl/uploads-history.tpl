{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Status of your uploads')}</h1>
                        </div>
                        <div class="right">
                            {$pagercode}
                        </div>
                    </div>
                    <div class="center-stack">
                        <table id="trackr" width="100%">
                            <th>{$API->LANG->_('Submitted URL')}</th><th>{$API->LANG->_('Name')}</th><th>{$API->LANG->_('Status')}</th><th>{$API->LANG->_('Last update')}</th><th>{$API->LANG->_('Actions')}</th>
                            <tbody>
                                {foreach from=$apps item=a}
                                    <tr>
                                        <td><input type="text" value="https://itunes.apple.com/{$a.store}/app/id{$a.trackid}" size="45" readonly="readonly" onclick="this.select()"/></td>
                                        <td><a href="{$API->SEO->make_link('view','trackid',$a.trackid)}">{$a.name}</a></td>
                                        <td>{$a.state} {if $a.state_reason} ({$a.state_reason}){/if}</td>
                                        <td>{$a.added|date_format:"%d.%m.%Y %H:%M"} GMT</td>
                                        <td><a href="{$API->SEO->make_link('upload','url',urlencode("https://itunes.apple.com/{$a.store}/app/id{$a.trackid}"))}">{$API->LANG->_('Upload this')}</a></td>
                                    </tr>
                                {/foreach}
                            </tbody>	
                        </table>
                        {$pagercode}
                    </div>
                    <div id="left-stack">
                        <b>{$API->LANG->_('More')}</b><br/><br/>
                        <ul>
                            <li><a href="{$API->SEO->make_link('account')}">{$API->LANG->_('Account management')}</a></li>
                            <li><a href="{$API->SEO->make_link('tracks')}">{$API->LANG->_('MY_TRACKS')}</a></li>
                            <li><a href="{$API->SEO->make_link('notification-settings')}">{$API->LANG->_('Notification settings')}</a></li>
                            <li><a href="{$API->SEO->make_link('iforgot')}">{$API->LANG->_('Set new password')}</a></li>

                        </ul><br/>
                        <b>{$API->LANG->_('Upload history')}</b><br/><br/>
                        <a href="{$API->SEO->make_link('upload')}">{$API->LANG->_('Upload an app')}</a><br/><br/>
                        {$API->LANG->_('UPLOADS_HISTORY_PAGE_DESCRIPTION')}
                    </div>
                </div>


            </div>
        </div>


    </div>


    {include file="footer.tpl"}