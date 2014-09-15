{*<!--<div id="facebook">
<div class="fbfan">
<a href="https://www.facebook.com/pages/App-Addict/527638493924285" target="_blank"><img src="/images/facebook.png" alt="{$API->LANG->_('Like us on')} facebook"/></a>
<div class="fbfan-content">

<div class="fb-like" data-href="https://www.facebook.com/pages/App-Addict/527638493924285" data-send="true" data-layout="button_count" data-width="60" data-show-faces="false" data-colorscheme="light" data-action="like"></div></div>
</div>
<div class="fbfan last">
<a href="https://twitter.com/add1cted2apps" target="_blank"><img src="/images/twitter.png" alt="{$API->LANG->_('Follow us on')} twitter"/></a>
{literal}
<div class="fbfan-content">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://www.appaddict.org" data-text="Free Cracked iOS and OS X Apps! @Add1cted2Apps - World of Cracked Apps awaits you!" data-related="Add1cted2Apps" data-hashtags="Add1cted2apps">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
{/literal}
</div>
</div>
<p>{$API->LANG->_('Follow AppAddict on Twitter and like it on Facebook')}!</p>
</div>-->*}
{$API->LANG->_('Follow AppAddict on Twitter and like it on Facebook')}

<div class="social">
    <a class="twitter" href="https://twitter.com/add1cted2apps"><img src="http://images.apple.com/iphone-5s/powerful/images/twitter_icon_2x.png" width="48" height="82" alt="Tweet"></a>
    <a class="facebook" href="https://www.facebook.com/pages/App-Addict/527638493924285"><img src="http://images.apple.com/iphone-5s/powerful/images/facebook_icon_2x.png" width="40" height="82" alt="Share on Facebook"></a>
</div>

<div id="globalfooter">
    <div id="breadory">
        <ol id="breadcrumbs">
            <li><a href="/">{$API->LANG->_('Home')}</a></li>
            <li>{$footername}</li>
        </ol>
        <!--googleoff: all-->
        <div id="directorynav" class="itunes">
            <div id="dn-cola" class="column first">
                <h3>appaddict.org</h3>
                <ul>
                    <li><a href="//app.appaddict.org">{$API->LANG->_('Download iOS App')}</a></li>
                    <li><a href="/forum/">{$API->LANG->_('Forum')}</a></li>
                    <li><a href="{$API->SEO->make_link('signservice')}">iSignCloud</a></li>
                    <li><a href="{$API->SEO->make_link('tools')}">{$API->LANG->_('Tools')}</a></li>
                    <li><a href="{$API->SEO->make_link('news')}">{$API->LANG->_('Site News')}</a></li>
                    <li><a href="{$API->SEO->make_link('about')}">{$API->LANG->_('About us')}</a></li>
                </ul>
            </div>
            {if $API->account['access_acp']}
                <div id="dn-colc" class="column">
                    <h3>Administration</h3>
                    <ul>
                        <li><a href="{$API->SEO->make_link('acp','action','news')}">News admicp</a></li>
                        <li><a href="{$API->SEO->make_link('acp')}">Apps/Links</a></li>
                        <li><a href="{$API->SEO->make_link('acp','action','push')}">Send push notification</a></li>
                        <li><a href="http://analytics.appaddict.org">AppAddict Analytics</a></li>

                    </ul>
                </div>
            {/if}
            <div id="dn-colb" class="column">
                <h3>{$API->LANG->_('Free Applications')}</h3>
                <ul>
                    <li><a href="{$API->SEO->make_link('top100')}">{$API->LANG->_('Top 100')}</a></li>
                    <li><a href="{$API->SEO->make_link('hof')}">{$API->LANG->_('Hall Of Fame')}</a></li>
                    <li><a href="{$API->SEO->make_link('search')}">{$API->LANG->_('Search Apps')}</a></li>
                    {if $API->account}<li><a href="{$API->SEO->make_link('tracks')}">{$API->LANG->_('MY_TRACKS')}</a></li>
                        {/if}
                </ul>

            </div>
            <div id="dn-colc" class="column">
                <h3>{$API->LANG->_('Other')}</h3>
                <ul>
                    <li><a href="{$API->SEO->make_link('donate')}">{$API->LANG->_('Donate')}</a></li>
                    <li><a href="{$API->SEO->make_link('tos')}">{$API->LANG->_('Terms Of Service')}</a></li>
                    <li><a href="{$API->SEO->make_link('privacy')}">{$API->LANG->_('Privacy Policy')}</a></li>

                    <li><a href="{$API->SEO->make_link('apis')}">{$API->LANG->_('Application Interfaces')}</a></li>
                    <li><a href="//status.appaddict.org">{$API->LANG->_('System Status')}</a></li>
                </ul>
            </div>
            <div id="dn-cold" class="column last">
                <h3>{$API->LANG->_('My account')}</h3>
                <ul>
                    {if !$API->account}
                        <li><a href="{$API->SEO->make_link('signup')}">{$API->LANG->_('Signup')}</a></li>
                        <li><a href="{$API->SEO->make_link('login')}">{$API->LANG->_('Login')}</a></li>
                        <li><a href="{$API->SEO->make_link('iforgot')}">{$API->LANG->_('Recover password')}</a></li>
                        {else}
                        <li><a href="{$API->SEO->make_link('account')}">{$API->LANG->_('Account management')}</a></li>
                        <li><a href="{$API->SEO->make_link('premium')}">{$API->LANG->_('Premium')}</a></li> 
                        <li><a href="{$API->SEO->make_link('upload')}">{$API->LANG->_('Upload new Content')}</a></li>
                        <li><a href="{$API->SEO->make_link('alternate-upload')}">{$API->LANG->_('Alternate uploading')}</a></li>
                        <li><a href="{$API->SEO->make_link('uploads-history')}">{$API->LANG->_('Uploads status/history')}</a></li>
                        <li><a href="{$API->SEO->make_link('logout')}">{$API->LANG->_('Logout')}</a></li>
                        {/if}
                </ul>
            </div>
            <div class="capbottom"></div>
        </div>

    </div><!--/breadory-->
    <p class="gf-buy"><a href="/">AppAddict</a> {$API->LANG->_('is not affiliated with')} <a href="http://www.apple.com/">Apple, Inc</a>.</p>

    <ul class="gf-links piped">

        <li><a href="{$API->SEO->make_link('rss')}" class="first">{$API->LANG->_('RSS Feeds')}</a></li>
        <li><a href="mailto:{$CONFIG['adminemail']}" class="contact_us">{$API->LANG->_('Contact us')}</a></li>
        <li><a href="{$API->SEO->make_link('setlang')}" class="choose"><img src="./itunes_files2/flags/{$COUNTRY_FLAGS[$API->LANG->getlang()]}.png" alt="{$API->LANG->_('Choose your country or region')}" width="22" height="22" data-hires="true"></a></li>
    </ul>

    <div class="gf-sosumi">
        <p><span style="-moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1); transform: scaleX(-1); display: inline-block;">&copy;</span> AppAddict, Inc. {date('Y')} {$API->LANG->_('DO_NOT_HOST_NOTICE')}. {$API->LANG->_('ITUNES_API_NOTICE')}.</p>
        <ul class="piped">
            <li><a href="https://regmyudid.com" class="first">iOS 8 UDID Registration</a></li>
            <li><a href="http://ibetacloud.com">Download iOS 8</a></li>
        </ul>
    </div>

</div><!--/globalfooter-->
{literal}
    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-37627831-1', 'appaddict.org');
        ga('send', 'pageview');

    </script>
{/literal}

{if $API->account['show_debug']}
    <pre>
        {$total = 0}
        {foreach from=$API->DB->query item=q}
            {$total = $total+$q['seconds']}
Time: {$q['seconds']}; Query: <input type="text" size="100" value="{$q['query']|htmlspecialchars}"/>
        {/foreach}
TOTAL SQL: {$total}
TOTAL PHP: {microtime(true)-$smarty.const.MICROTIME-$total}
    </pre>
{/if}
<script type="text/javascript" src="/js/retina.js"></script>
</body></html>