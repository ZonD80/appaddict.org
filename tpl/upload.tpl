{include file='header.tpl'}

<script>
    function one_more_link() {
        $('<br/>').insertAfter($('.aa-more-link:last').after($('.aa-more-link:last').clone().val('')));//.after('<br>');
        return false;
    }
</script>
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Upload new Content')} ({$API->LANG->_('Apps')}, {$API->LANG->_('Books')})</h1>
                        </div>
                        <div class="right"><a href="{$API->SEO->make_link('tos')}#uploaders" style="color:red;">{$API->LANG->_('Terms for uploaders! READ BEFORE UPLOADING')}</a></div>
                    </div>

                    <div class="center-stack"><form method="POST" enctype="multipart/form-data">
                            <h1>Please follow these simple rules to upload!<sup>*</sup>:</h1>
                            <h2>{$API->LANG->_('VERSIONED_NAMES_NOTICE')}</h2>
                            <h2>{$API->LANG->_('FAKE_CRACKER_NOTICE')}</h2>
                            <hr/>
                            <h1>{$API->LANG->_('iTunes/Mac App Store URL')}<br/>(<span style="color:red;">{$API->LANG->_('use us itunes/mac app store if available')}</span>)</h1>
                            <input type="text" name="url" required="required" value="{$url}" class="uploadval"/>
                            <hr/>
                            <h3>{$API->LANG->_('Version')}<br/>({$API->LANG->_('only if version is not latest; the same format as on iTunes/Mac App Store')})</h3>
                            <input type="text" name="version" class="uploadval"/> ({$API->LANG->_('optional')})
                            <hr/>
                            <h1>{$API->LANG->_('Cracker name')}, {$API->LANG->_('Yours is')} <a href="javascript://"onclick="$('#cracker').val($(this).text());">{$API->account['name']}</a></h1>
                            <input type="text" name="cracker" id="cracker" required="required" class="uploadval"/>
                            <hr/>
                            <h3>{$API->LANG->_('.torrent file')}<br/>({$API->LANG->_('you can to not provide download links if you uploading .torrent')})</h3>
                            <br/><span style="color:red;">{$API->LANG->_('Announce URL of our tracker is')}: <strong>http://pixi.appaddict.org:2710/announce</strong></span><br/><br/>
                            <input type="file" name="torrent"/> ({$API->LANG->_('optional')})
                            <h1>{$API->LANG->_('Download Links')}<br/>({$API->LANG->_('to files, not folders')})</h1>
                        <hr/>
                        {if $required_filehostings}
                                    {foreach from=$required_filehostings item=fh}
                                        <input type="url" placeholder="{$API->LANG->_('%s link',{$fh['domains']})}" name="links[]" required="required" class="aa-more-link uploadval"/> <span style="color:red;">{$API->LANG->_('Required')}</span><br/>
                        
                                        {/foreach}
                                    {/if}
                        <input type="url" placeholder="{$API->LANG->_('Full url, starting with http://')}" name="links[]" class="aa-more-link uploadval"/><a href="#" onclick="return one_more_link();">{$API->LANG->_('Add one more link')}</a>
                        <hr/>


                        <input type="submit" value="{$API->LANG->_('Upload')}"/><br/>
                        <sup>*</sup>{$API->LANG->_('This message was used only for your attention. We love and help Nigeria children actually')}
                    </form></div>

                <div id="left-stack">

                    <b>{$API->LANG->_('Please provide the following')}:</b><br/><br/>
                    {$API->LANG->_('iTunes/Mac App Store URL')}<br/>
                    {$API->LANG->_('App Version')} ({$API->LANG->_('optional')})<br/>
                    {$API->LANG->_('Cracker name')}<br/>
                    {$API->LANG->_('.torrent file')} ({$API->LANG->_('optional')})<br/>
                    {$API->LANG->_('Links to allowed file hostings')}<br/>
                    <a href="javascript://" onclick="$('#banned_hosts').slideDown();">{$API->LANG->_('Show banned file hostings')}</a>
                    <p id="banned_hosts" style="display:none; color:blue;"><small>{if $banned_filehostings}
                            {foreach from=$banned_filehostings item=h}
                                {$h.domains} ({$h.reason})<br/>
                            {/foreach}
                            {else}{$API->LANG->_('None defined yet')}{/if}</small></p><br/><br/>
                            <b>{$API->LANG->_('Alternative method')}:</b><br/><br/>
                            <a href="{$API->SEO->make_link('alternate-upload')}">{$API->LANG->_('Go to alternate uploading')}</a><br/><br/>
                            {$API->LANG->_('You can upload CSV file')}<br/>

                            <a href="{$API->SEO->make_link('uploads-history')}">{$API->LANG->_('View uploads status/history')}</a><br/><br/>
                            <div style="color:red;">
                                {$API->LANG->_('Warning')}:<br/>
                                {$API->LANG->_('Your submission will be moderated after uploading')}<br/>
                                <u>{$API->LANG->_('Moderation takes up to 24h, please be patient')}!</u>
                            </div>
                            <br/><br/>
                            {if $archive_warning}
                                <div style="color:red;">
                                    {$API->LANG->_('Warning')}:<br/>
                                    {$API->LANG->_('ANOTHER_VERSION_UPLOADED',$app.version)}<br/>
                                    {$API->LANG->_('If you still want to upload new version, you can continue and current version will be archived')}.
                                </div><br/><br/>
                            {/if}
                        </div>
                    </div>


                </div>
            </div>


        </div>






        {include file="footer.tpl"}