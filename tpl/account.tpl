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
                        <div class="center-stack">
                            <h2>{$API->LANG->_('Linked iOS devices')}:</h2>
                            {if $devices}
                        <table id="trackr" width="100%">
                            <tr><th>{$API->LANG->_('Device name')}</th><th>{$API->LANG->_('Model')}</th><th>{$API->LANG->_('Firmware version')}</th><th>{$API->LANG->_('Actions')}</th></tr>
                            <tbody>
                                                    {foreach from=$devices item=d}
                                                        <tr id="device-{$d.udid}"><td>{if $d.name}{$d.name}{else}({$API->LANG->_('No data provided. Update to last version of iOS app')}){/if}</td>
                                                            <td>{if $d.model}{nice_idevice_model($d.model)}{else}({$API->LANG->_('No data provided. Update to last version of iOS app')}){/if}</td>
                                                            <td>{if $d.ios_version}iOS {$d.ios_version}{else}({$API->LANG->_('No data provided. Update to last version of iOS app')}){/if}</td>
                                                            <td><a href="javascript://" onclick="delete_device('{$d.udid}');">{$API->LANG->_('Unlink')}</a></td>
                                                        </tr>
                                                        {/foreach}
					
</tbody>
</table>
                                                        {else}
                                                            <h1 align="center">{$API->LANG->_('No devices linked. Login in our iOS app to link a device and make sure that push for our app is enabled.')}</h1>
                            {/if}
                            <br/><h3 align="center">{$API->LANG->_('You can link up to %s devices',5)}.</h3>
                                                        </div>
                                                        <div id="left-stack">
                        
                                                            <b>{$API->LANG->_('More')}</b><br/><br/>
                                                            <ul>
                                                                <li><a href="{$API->SEO->make_link('tracks')}">{$API->LANG->_('MY_TRACKS')}</a></li>
                                                                <li><a href="{$API->SEO->make_link('uploads-history')}">{$API->LANG->_('Uploads status/history')}</a></li>
                                                                <li><a href="{$API->SEO->make_link('notification-settings')}">{$API->LANG->_('Notification settings')}</a></li>
                                                                <li><a href="{$API->SEO->make_link('iforgot')}">{$API->LANG->_('Set new password')}</a></li>

                                                            </ul>
                                                            <br/><br/>
                           <b>{$API->LANG->_('Here you can manage your account')}</b><br/><br/>
                           {$API->LANG->_('ACCOUNT_PAGE_DESCRIPTION')}
                    </div>
                </div>


            </div>
        </div>


    </div>


{include file="footer.tpl"}