{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


       <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Application Interfaces')}</h1>
                        </div>
                    </div>
 
                    <div class="longtext">
<p><strong>appaddict.org</strong> is providing couple of Application Interfaces (aka APIs) for 3rd party projects and applications.
</p>
<p>Our APIs are following:</p>
<ul><li><a href="https://forum.appaddict.org/index.php?/topic/2120-appaddict-api/">Our general API where you can receive content, do accounts related stuff, etc</a></li>
<li><a href="https://forum.appaddict.org/index.php?/topic/468-appaddict-alternate-uploading-api/">Alternate uploading API, for bulk uploads or uploads via automated applications aka Bots</a></li>
<li><a href="https://forum.appaddict.org/index.php?/topic/2411-implementing-directinstaller-support-di-api/">DirectInstaller API for file hosting owners</a> [ <a href="{$API->SEO->make_link('apis','action','di_test')}">Test your DI integration</a> ]</li>
<li><a href="https://forum.appaddict.org/index.php?/topic/2597-voucher-api/">Dynamic Voucher API (to resell AppAddict Premium membership)</a></li>

</ul>

                    </div>


                </div>


            </div>
        </div>


    </div>


{include file="footer.tpl"}