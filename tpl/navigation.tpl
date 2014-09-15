<nav id="globalheader" class="navbar">
        <!--googleoff: all-->
        <ul id="globalnav" role="navigation">
            <li id="gn-apple"><a href="/" class=""><span>{$API->LANG->_('New Content')}</span></a></li>
            <li id="gn-store"><a href="//app.appaddict.org" class=""><span>{$API->LANG->_('iOS App')}</span></a></li>
            <li id="gn-ipod"><a href="{$API->SEO->make_link('top100')}"><span>{$API->LANG->_('Top 100')}</span></a></li>
            <li id="gn-itunes"><a href="https://regmyudid.com" target="_blank"><span>iOS 8</span></a></li>
            <li id="gn-mac"><a href="{$API->SEO->make_link('hof')}"><span>{$API->LANG->_('Hall Of Fame')}</span></a></li>
            <li id="gn-support" class="gn-last"><a href="//forum.appaddict.org"><span>{$API->LANG->_('Forum')}</span></a></li>
            {if !$API->account}
            <li id="gn-iphone"><a href="{$API->SEO->make_link('signup')}"><span>{$API->LANG->_('Signup')}</span></a></li>
            <li id="gn-ipad"><a href="{$API->SEO->make_link('login')}" class=""><span>{$API->LANG->_('Login')}</span></a></li>
            {else}
            <li id="gn-iphone"><a href="{$API->SEO->make_link('upload')}"><span>{$API->LANG->_('Upload New')}</span></a></li>
            <li id="gn-ipad"><a href="{$API->SEO->make_link('logout')}" class=""><span>{$API->LANG->_('Logout')}</span></a></li>   
                {/if}
        </ul>
        <!--googleon: all-->
        <div id="globalsearch">
            <form action="{$API->SEO->make_link('search')}" method="get" class="search empty" id="g-search"><div class="sp-label">
                    <label for="sp-searchtext">{$API->LANG->_('Search')}</label>
                    <input autocomplete="off" type="text" name="q" id="sp-searchtext"><div class="reset"></div>
                    <div class="spinner hide"></div></div></form>
            <div id="sp-magnify"><div class="magnify-searchmode"></div><div class="magnify"></div></div>
            <div id="sp-results" style="display: none;"><div class="sp-shadow"></div><div><ul class="noresults"><li><center>{$API->LANG->_('Search Apps, Developers, Genres or Crackers')}</center></li></ul></div></div>
        </div>
    </nav>
             {if $API->account&&$API->account['premium_expired']&&(($API->account['premium_expired']-$CONFIG['TIME'])<259200&&$API->account['premium_expired']-$CONFIG['TIME']>0)}
                 <center><span style="color:red;">{$API->LANG->_('Your premium service is about to expire in less than 3 days')}.</span> <a href="{$API->SEO->make_link('premium')}">{$API->LANG->_('Extend account')}</a></center>
                 {/if}
      {if $pagetitle!='Search'}  {*dirty fix*}                
    <div id="globalheader-loaded-test"></div>
    <div id="productheader" data-hires="true">
        <h2><img src="{if is_premium()}images/aa-uberheader-premium.png" alt="iTunes/MAS" height="40" {else}images/aa-uberheader.png" alt="iTunes/MAS" height="40"{/if} hspace="10"></h2>
        <ul>
            <li id="pn-ss"><a href="{$API->SEO->make_link('signservice')}">{$API->LANG->_('Not jailbroken?')}</a></li>
            <li id="pn-premium"><a href="{$API->SEO->make_link('premium')}"><font color="#FF0000">{$API->LANG->_('Premium')}</font></a></li>
            <li id="pn-tools"><a href="{$API->SEO->make_link('tools')}">{$API->LANG->_('Tools')}</a></li>
            <li id="pn-news"><a href="{$API->SEO->make_link('news')}">{$API->LANG->_('Site News')}</a></li>
            {*<li id="pn-aboutus"><a href="{$API->SEO->make_link('about')}">{$API->LANG->_('About us')}</a></li>*}
            <li id="pn-donate"><a href="{$API->SEO->make_link('donate')}">{$API->LANG->_('Donate')}</a></li>
        </ul>
    </div>
<div class="divider line"></div>
        {/if}