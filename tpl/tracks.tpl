{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('MY_TRACKS')} - {$API->LANG->_('Content tracked by you')}</h1>
                        </div>
                        <div class="right">
                            {$pagercode}
                        </div>
                    </div>
                    <div class="center-stack">
                        <table id="trackr" width="100%">
                            <tr><th>{$API->LANG->_('App Icon')}</th><th>{$API->LANG->_('Name')}</th><th></th>
                            <tbody>
                                {foreach from=$apps item=a}
                                    <tr><td>
                                            <a href="{$API->SEO->make_link('view','trackid',$a.trackid)}">
                                                <img class="icon" src="{$a.image}" alt="{$a.name}" />
                                            </a></td>
                                        <td><a href="{$API->SEO->make_link('view','trackid',$a.trackid)}">{$a.name}</a></td>
                                        <td class="tracks"><a href="{$API->SEO->make_link('trackapp','trackid',$a.trackid,'untrack','1','returnto',$returnto)}"><img src="/images/untrack-title.png" title="Untrack this app"></a></td>
                                    </tr>
                                {/foreach}

                            </tbody>
                        </table>
                    </div>
                    <div id="left-stack">
                        <b>{$API->LANG->_('More')}</b><br/><br/>
                        <ul>
                            <li><a href="{$API->SEO->make_link('account')}">{$API->LANG->_('Account management')}</a></li>
                            <li><a href="{$API->SEO->make_link('uploads-history')}">{$API->LANG->_('Uploads status/history')}</a></li>
                            <li><a href="{$API->SEO->make_link('notification-settings')}">{$API->LANG->_('Notification settings')}</a></li>
                            <li><a href="{$API->SEO->make_link('iforgot')}">{$API->LANG->_('Set new password')}</a></li>

                        </ul><br/>
                        <b>{$API->LANG->_('Here you can manage your tracks')}</b><br/><br/>
                        {$API->LANG->_('TRACK_PAGE_DESCRIPTION')}
                        <br/><br/>
                        <b>{$API->LANG->_('Info')}:</b><br/><br/>
                        {$API->LANG->_('TRACK_FEATURE_DESCRIPTION')}
                    </div>
                </div>


            </div>
        </div>


    </div>


    {include file="footer.tpl"}