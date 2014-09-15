{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Account management')}</h1>
                        </div>
                        <div class="right">
                            <h1>{$API->account['name']}</h1>
                        </div>
                    </div>
                    <form metho="POST"/>
                    <input type="hidden" name="action" value="configure"/>
                    <div class="center-stack">
                        <h2>Email:</h2>
                        {foreach from=$allowed_notifs['email_notifications'] key=type item=description}
                            <p><label style="cursor:pointer;"><input type="checkbox" name="notifs[email_notifications][{$type}]" value="1"{if in_array($type,$account_notifications['email'])||$account_notifications['email'][0]!='none'} checked="checked"{/if}/> {$description}</label></p>

                        {/foreach}
                        <br/>
                        <h2>{$API->LANG->_('iOS push notifications')}:</h2>
                        {foreach from=$allowed_notifs['push_notifications'] key=type item=description}
                            <p><label style="cursor:pointer;"><input type="checkbox" name="notifs[push_notifications][{$type}]" value="1"{if in_array($type,$account_notifications['push'])||$account_notifications['push'][0]!='none'} checked="checked"{/if}/> {$description}</label></p>

                        {/foreach}
                        <br/>
                        <h2>{$API->LANG->_('Safari push notifications')}:</h2>
                        {foreach from=$allowed_notifs['safari_push_notifications'] key=type item=description}
                            <p><label style="cursor:pointer;"><input type="checkbox" name="notifs[safari_push_notifications][{$type}]" value="1"{if in_array($type,$account_notifications['safari_push'])||$account_notifications['safari_push'][0]!='none'} checked="checked"{/if}/> {$description}</label></p>

                        {/foreach}
                        <input type="submit" value="{$API->LANG->_('Save')}"/>
                    </div>
                    </form>
                    <div id="left-stack">
                        <b>{$API->LANG->_('More')}</b><br/><br/>
                        <ul>
                            <li><a href="{$API->SEO->make_link('account')}">{$API->LANG->_('Account management')}</a></li>
                            <li><a href="{$API->SEO->make_link('tracks')}">{$API->LANG->_('MY_TRACKS')}</a></li>
                            <li><a href="{$API->SEO->make_link('uploads-history')}">{$API->LANG->_('Uploads status/history')}</a></li>
                            <li><a href="{$API->SEO->make_link('iforgot')}">{$API->LANG->_('Set new password')}</a></li>

                        </ul><br/>
                        <b>{$API->LANG->_('Notification settings')}</b><br/><br/>
                        {$API->LANG->_('NOTIFICATION_SETTINGS_DESCRIPTION')}
                    </div>
                </div>


            </div>
        </div>


    </div>


    {include file="footer.tpl"}