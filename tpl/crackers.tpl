{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


      <div id="main" class="content">
      <br>
      {if !is_premium()}
                        <div style="text-align:center;margin-bottom:10px;"><center> 
                                {*<iframe src="https://regmyudid.com/art/iOS8/index.html" frameborder="0" style="margin:0;padding:0;border:none;background-color:transparent" allowtransparency="true" scrolling="no" width="728" height="90"></iframe>
                                *}
                            
<iframe src="https://regmyudid.com/art/iOS8/index.html" frameborder="0" style="margin:0;padding:0;border:none;background-color:transparent" allowtransparency="true" scrolling="no" width="728" height="90"></iframe>
</center></div>{/if}
        <div id="crackerscontent" class="rounded" data-hires="true">
        <div id="profiles" class="section">    
            <h2><a href="{$API->SEO->make_link('hof')}">{$API->LANG->_('Hall Of Fame')}</a> - {if $team}{$API->LANG->_('Of team')} {$teamdata.name}{else}{$API->LANG->_('Top Verified Crackers')}{/if}
                
                {*<br/>{$API->LANG->_('Apps')}: 
                                {if !$price}[<a href="{$API->SEO->make_link('crackers','price','1','team',$team)}">{$API->LANG->_('Paid')}</a>] [<a href="{$API->SEO->make_link('crackers','price','2','team',$team)}">{$API->LANG->_('Free')}</a>] [{$API->LANG->_('All')}]
                                {elseif $price==1}[{$API->LANG->_('Paid')}] [<a href="{$API->SEO->make_link('crackers','price','2','team',$team)}">{$API->LANG->_('Free')}</a>] [<a href="{$API->SEO->make_link('crackers','price','0','team',$team)}">{$API->LANG->_('All')}</a>]
                                {elseif $price==2}[<a href="{$API->SEO->make_link('crackers','price','1','team',$team)}">{$API->LANG->_('Paid')}</a>] [{$API->LANG->_('Free')}] [<a href="{$API->SEO->make_link('crackers','price','0','team',$team)}">{$API->LANG->_('All')}</a>]
                                {/if}*}
            </h2>
            
            {if $crackers}
        <ul>
            {foreach from=$crackers item=c}
            <li>    
                <a href="{$API->SEO->make_link('crackers','id',$c.id)}">
                <img src="{if $c.avatar}{$c.avatar}{else}img/crackers/no_avatar.jpg{/if}" alt="{$c.name}" height="130" width="168">
                             <h3>{$c.name}</h3>
                             {$API->LANG->_('CRACKER_CRACKED_APPS',$c.numapps,$c.clicks)}
                </a>
                <p>
                            "{$c.slogan}"
                </p>
            </li>
            {/foreach}
            
            {/if}
        </ul>
        </div>
                <div class="contact rounded clear">    
                        <span class="title">{$API->LANG->_('Want to be here?')}</span>
                        <span class="phone">{$API->LANG->_('Become a verified cracker')}!</span>
                        <span class="email"><a href="{$API->SEO->make_link('become-verified')}">{$API->LANG->_('Submit proposal')}</a></span>
            </div>
        </div>
    </div>


{include file="footer.tpl"}