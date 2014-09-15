{include file='header.tpl'}

<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Become a verified cracker')}</h1>
                        </div>
                        <div class="right">
                            <!--<div class="gc-badge app-badge"><span>Game Center</span></div>-->

                            <a href="javascript:history.go(-1);">{$API->LANG->_('Go back')}</a>
                        </div>
                                          </div>

                    <div class="center-stack">
                        <h1>{$API->LANG->_('Did you do the following')}?</h1><br/>
                        <form method="POST" onsubmit="return confirm('{$API->LANG->_('Are images format and sizes match what we require from you?')}');">
                        <h2><label><input type="checkbox" required="required">&nbsp;{$API->LANG->_('I uploaded at least 50 apps')}</label></h2>
                        <h2><label><input type="checkbox" required="required">&nbsp;{$API->LANG->_('These 50 apps were cracked entirely by me')}</label></h2>
                        <h2><label><input type="checkbox" required="required">&nbsp;{$API->LANG->_('I filled "cracker" field with "%s" during upload',$API->account['name'])}</label></h2>
                        <h2><label><input type="checkbox" required="required" onclick="$('#required_data').slideDown();">&nbsp;{$API->LANG->_("I'm ready to provide required data")}</label></h2>
                        <div id="required_data" style="display:none;">
                        <h3>{$API->LANG->_('Please provide your non-standard sized (%s) avatar URL','168x130')} ({$API->LANG->_('png image')}):</h3>
                        <input type="url" name="avatar" size="100" required="required">
                        <h3>{$API->LANG->_('Please provide background for your profile (%s) URL','575x657')} ({$API->LANG->_('png image')}):</h3>
                        <input type="url" name="background" size="100" required="required">
                        <h3>{$API->LANG->_('Please provide slogan, describing your beliefs')}:</h3>
                        <input type="text" name="slogan" size="100" required="required">
                        <h3>{$API->LANG->_('Please provide a short story about you')}:</h3>
                        <textarea name="story" rows="5" cols="71" required="required"></textarea><br/>
                        
                        <input type="submit" value="{$API->LANG->_('Submit proposal')}"/>
                        </div>
                        <h1>{$API->LANG->_('Your proposal will be verified. You will get email about verification status.')}</h1>
                        </form>
                    </div>

                    <div id="left-stack">
                    <b>{$API->LANG->_('Info')}:</b><br/><br/>
                    {$API->LANG->_('BECOME_VERIFIED_INFO',$API->SEO->make_link('crackers'))}
                    </div>
                </div>


            </div>
        </div>


    </div>






{include file="footer.tpl"}