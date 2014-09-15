{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}

<div id="overview">
      <div id="main" class="content">
		<div id="content">
		{if !is_premium()}
                        <div style="text-align:center;margin-bottom:10px;"><center> 
                                {*<iframe src="https://regmyudid.com/art/iOS8/index.html" frameborder="0" style="margin:0;padding:0;border:none;background-color:transparent" allowtransparency="true" scrolling="no" width="728" height="90"></iframe>
                                *}
                            
<iframe src="https://regmyudid.com/art/iOS8/index.html" frameborder="0" style="margin:0;padding:0;border:none;background-color:transparent" allowtransparency="true" scrolling="no" width="728" height="90"></iframe>
</center></div>{/if}
<br>
			<h1><img src="itunes_files/overview_title_20100525.gif" alt="Join the crowd." height="46" width="287"></h1>
			<p class="intro">{$API->LANG->_('Overview Cracking Teams working for you every day')}.</p>

			<ul>
                            {if $teams}
                                {foreach from=$teams item=t}
				<li>
                                    <div>
						<img src="{if $t.image}{$t.image}{else}img/teams/noimage.png{/if}" alt="{$t.name}" height="254" width="397">
						<h3>{$t.name}</h3>
						<h3 class="sub">{$API->LANG->_('Established in')} {$t.established|date_format:"%B %Y"}</h3>
						<p>{$t.description}</p>
                                                    <a class="more" href="{$API->SEO->make_link('crackers','team',$t.id)}">{$API->LANG->_('View members')}</a>{if $t.website}&nbsp;&nbsp;&nbsp;&nbsp;<a class="more" target="_blank" href="{$t.website}">{$API->LANG->_('Visit Website')}</a>{/if}</p>
                                                </div>
				</li>
                                {/foreach}
				{/if}
			</ul>
                        <div class="contact rounded clear">    
                        <span class="title">{$API->LANG->_('Want to be here?')}</span>
                        <span class="phone">{$API->LANG->_('Register your team')}!</span>
                        <span class="email"><a href="javascript://" onclick="alert('{$API->LANG->_('Email us')}!');" title="{$API->LANG->_('Register your team')}">relations at appaddict dot org</a></span>
            </div>
		</div>
	</div>
</div>

{include file="footer.tpl"}