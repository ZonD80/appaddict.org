{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('DirectInstaller')}</h1>
                        </div>
                        <div class="right">
                            <a href="javascript:history.go(-1);">{$API->LANG->_('Go back')}</a>
                        </div>
                    </div>
                    <div class="center-stack">
                        <h2>{$API->LANG->_('Select iOS device for installation')}:</h2>
                        {if $devices}
                            <table id="trackr" width="100%">
                                <tr><th>{$API->LANG->_('Device name')}</th><th>{$API->LANG->_('Model')}</th><th>{$API->LANG->_('Firmware version')}</th><th>{$API->LANG->_('Actions')}</th></tr>
                                <tbody>
                                    {foreach from=$devices item=d}
                                        <tr id="device-{$d.udid}"><td>{if $d.name}{$d.name}{else}({$API->LANG->_('No data provided. Update to last version of iOS app')}){/if}</td>
                                            <td>{if $d.model}{nice_idevice_model($d.model)}{else}({$API->LANG->_('No data provided. Update to last version of iOS app')}){/if}</td>
                                            <td>{if $d.ios_version}iOS {$d.ios_version}{else}({$API->LANG->_('No data provided. Update to last version of iOS app')}){/if}</td>

                                            <td>{if is_device_compatible($appdata['compatibility_string'],$d.model,$d.ios_version)}<a href="javascript://" onclick="install_on_device('{$d.udid}');">{$API->LANG->_('Install to this device')}</a>
                                            {else}<span style="color:red;">{$API->LANG->_('Device is not compatible. Check your device model or/and firmware version.')}</span>{/if}</td>
                                        </tr>
                                    {/foreach}

                                </tbody>
                            </table>
                        {else}
                            <h1 align="center">{$API->LANG->_('No devices linked. Login in our iOS app to link a device and make sure that push for our app is enabled.')}</h1>
                        {/if}
                    </div>
                    <div id="left-stack">

                        <b>{$API->LANG->_('What is DirectInstaller?')}</b><br/><br/>
                        {$API->LANG->_('DIRECTINSTALLER_DESCRIPTION')}
                    </div>
                </div>


            </div>
        </div>


    </div>


    {include file="footer.tpl"}