{include file='header.tpl'}

<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Alternate uploading panel')}</h1>
                        </div>
                                          </div>

                    <div class="center-stack"><form method="POST" enctype="multipart/form-data">
                            <h1>{$API->LANG->_('Message from poor Nigeria children')}<sup>*</sup>:</h1>
                            <h2>{$API->LANG->_('VERSIONED_NAMES_NOTICE')}</h2>
                            <h2>{$API->LANG->_('FAKE_CRACKER_NOTICE')}</h2>
                            <h1>{$API->LANG->_('CSV file with dot-comma separators')} ({$API->LANG->_('MAX')} 8MB)</h1>
                            <input type="file" name="file" required="required"/>
                            <h1>{$API->LANG->_('Values separator')}</h1>
                            <input type="text" name="separator" required="required" maxlength="1" value="," size="1" class="commaval"/> ({$API->LANG->_('default is comma')})
                            <h1>{$API->LANG->_('Note')}: <span style="color:red;">{$API->LANG->_('use us itunes/mac app store if available')}</span><br/>
                            {$API->LANG->_('Your nickname is')} "{$API->account['name']}"</h1>
                            <p style="color:black;">{$API->LANG->_('Please provide comma-separated CSV file')} <strong>{$API->LANG->_('without table headers')}</strong> {$API->LANG->_('in following format')}:</p>
                            <pre style="font-size: 10px;">{$API->LANG->_('CSV_FILE_FORMAT')}</pre>
                            {$API->LANG->_('Example')}:
                            <pre style="font-size: 10px;">https://itunes.apple.com/us/app/temple-run/id420009108?mt=8,,most_unique,http://test.whatever/test-v.14.ipa</pre>
                            <br/><p style="color:black;">{$API->LANG->_('To save file from Excel select "Save as" and "Windows dot-comma-separated list (.csv)"')}<br/>
                            {$API->LANG->_('After uploading you should see blank page with uploading progress')}</p>
                            
<hr/>
                            <input type="submit" value="{$API->LANG->_('Upload')}"/><br/>
                            <sup>*</sup>{$API->LANG->_('This message was used only for your attention. We love and help Nigeria children actually')}
                    </form></div>

                    <div id="left-stack">
                        
                           <b>{$API->LANG->_('Please provide the following')}:</b><br/><br/>
                           {$API->LANG->_('CSV comma-separated file in requested format')}<br/>
                           <a href="javascript://" onclick="$('#banned_hosts').slideDown();">{$API->LANG->_('Show banned file hostings')}</a>
                           <p id="banned_hosts" style="display:none; color:blue;"><small>{if $banned_filehostings}
                               {foreach from=$banned_filehostings item=h}
                                   {$h.domains} ({$h.reason})<br/>
                                   {/foreach}
                               {else}{$API->LANG->_('None defined yet')}{/if}</small></p><br/><br/>
                           <b>{$API->LANG->_('Alternative method')}:</b><br/><br/>
                           <a href="{$API->SEO->make_link('upload')}">{$API->LANG->_('Go to regular upload page')}</a><br/><br/>
                           
                           <a href="{$API->SEO->make_link('uploads-history')}">{$API->LANG->_('View uploads status/history')}</a>
                           <br/><br/>
                           <div style="color:red;">
                               {$API->LANG->_('Warning')}:<br/>
                               {$API->LANG->_('Your apps will be moderated after uploading')}<br/>
                               <u>{$API->LANG->_('Moderation takes up to 24h, please be patient')}!</u><br/>
                               </div>
                               <br/><br/>
                    </div>
                </div>


            </div>
        </div>


    </div>






{include file="footer.tpl"}