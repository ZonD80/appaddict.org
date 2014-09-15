{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">
                    {if !is_premium()}
                        <div style="text-align:center;margin-bottom:10px;">{literal}
                            <center>
<iframe scrolling="no" style="border: 0; width: 728px; height: 90px;" src="//coinurl.com/get.php?id=26492&SSL=1"></iframe>
                            </center>
                            <!-- ads -->
                            {/literal}</div>{/if}
                                <div id="title" class="intro has-gcbadge">
                                    <div class="text-center">
                                    <center>
                                        <h1>{$API->LANG->_('Top 100')}
                                        <br> 
                                            {if $period=='day'}[{$API->LANG->_('Day')}] [<a href="{$API->SEO->make_link('top100','period','week')}">{$API->LANG->_('Week')}</a>] [<a href="{$API->SEO->make_link('top100','period','month')}">{$API->LANG->_('Month')}</a>] [<a href="{$API->SEO->make_link('top100','period','year')}">{$API->LANG->_('Year')}</a>] [<a href="{$API->SEO->make_link('top100','period','all')}">{$API->LANG->_('All')}</a>]
                                            {elseif $period=='week'}[<a href="{$API->SEO->make_link('top100','period','day')}">{$API->LANG->_('Day')}</a>] [{$API->LANG->_('Week')}] [<a href="{$API->SEO->make_link('top100','period','month')}">{$API->LANG->_('Month')}</a>] [<a href="{$API->SEO->make_link('top100','period','year')}">{$API->LANG->_('Year')}</a>] [<a href="{$API->SEO->make_link('top100','period','all')}">{$API->LANG->_('All')}</a>]
                                            {elseif $period=='month'}[<a href="{$API->SEO->make_link('top100','period','day')}">{$API->LANG->_('Day')}</a>] [<a href="{$API->SEO->make_link('top100','period','week')}">{$API->LANG->_('Week')}</a>] [{$API->LANG->_('Month')}] [<a href="{$API->SEO->make_link('top100','period','year')}">{$API->LANG->_('Year')}</a>] [<a href="{$API->SEO->make_link('top100','period','all')}">{$API->LANG->_('All')}</a>]
                                            {elseif $period=='year'}[<a href="{$API->SEO->make_link('top100','period','day')}">{$API->LANG->_('Day')}</a>] [<a href="{$API->SEO->make_link('top100','period','week')}">{$API->LANG->_('Week')}</a>] [<a href="{$API->SEO->make_link('top100','period','month')}">{$API->LANG->_('Month')}</a>] [{$API->LANG->_('Year')}] [<a href="{$API->SEO->make_link('top100','period','all')}">{$API->LANG->_('All')}</a>]
                                            {else}[<a href="{$API->SEO->make_link('top100','period','day')}">{$API->LANG->_('Day')}</a>] [<a href="{$API->SEO->make_link('top100','period','week')}">{$API->LANG->_('Week')}</a>] [<a href="{$API->SEO->make_link('top100','period','month')}">{$API->LANG->_('Month')}</a>] [<a href="{$API->SEO->make_link('top100','period','year')}">{$API->LANG->_('Year')}</a>] [{$API->LANG->_('All')}]

                                            {/if}</h1>
                                            </center>
                                            <br>
                                            <center>
                                            {if !is_premium()}
                        <div style="text-align:center;margin-bottom:10px;"><center> 
                               <iframe scrolling="no" style="border: 0; width: 728px; height: 90px;" src="//coinurl.com/get.php?id=26492&SSL=1"></iframe>
                            </center></div>{/if}
                            
                                                                    <form>
                            
                            	<div class="select_dropdown" style="float: left">
                                <select id="1" name="type" onchange="$('#aa-formsubmit').click();">
									<option value="" {if !$type} selected="selected"{/if}>{$API->LANG->_('Type')}: {$API->LANG->_('All')}</option>
                                    <option value="app" {if $type=='app'} selected="selected"{/if}>{$API->LANG->_('Apps')}</option>
                                    <option value="book" {if $type=='book'} selected="selected"{/if}>{$API->LANG->_('Books')}</option>
                                </select>
                                </div>
                                
                                <div class="select_dropdown2" style="float: left">
                                <select id="2" name="compatibility" onchange="$('#aa-formsubmit').click();">
                                    <option value="0" {if !$compatibility} selected="selected"{/if}>{$API->LANG->_('Platform')}: {$API->LANG->_('All')}</option>
                                    <option value="1"{if $compatibility==1} selected="selected"{/if}>iOS</option>
                                    <option value="2"{if $compatibility==2} selected="selected"{/if}>iPhone</option>
                                    <option value="3"{if $compatibility==3} selected="selected"{/if}>iPad</option>
                                    <option value="4"{if $compatibility==4} selected="selected"{/if}>Mac</option>
                                </select>
                                </div>
                                <div class="select_dropdown3" style="float: left">
                                <select id="3" name="price" onchange="$('#aa-formsubmit').click();">
                                    <option value="0" {if !$price} selected="selected"{/if}>{$API->LANG->_('Price')}: {$API->LANG->_('All')}</option>
                                    <option value="1" {if $price==1} selected="selected"{/if}>{$API->LANG->_('Paid')}</option>
                                    <option value="2" {if $price==2} selected="selected"{/if}>{$API->LANG->_('Free')}</option>
                                </select>
                                </div>
                                <div class="select_dropdown4" style="float: left">
                                <select id="4" name="genre" onchange="$('#aa-formsubmit').click();">
                                    <option value="0" {if !$genre} selected="selected"{/if}>{$API->LANG->_('Genre')}: {$API->LANG->_('All')}</option>
                                    {foreach from=$genres item=g}
                                        <option value="{$g.id}" {if $g.id==$genre} selected="selected"{/if}>{$g.name}</option>   
                                    {/foreach}
                                </select>
                                </div>

                                <input id="aa-formsubmit" type="submit" style="display:none;"/>
                                
                                </form> 
                                
                        </div>
                        <br>
                        <br>
                        
                        <div class="text-center">
                        <center>
                            {$pagercode}
                            </center>
                        </div>
                                </div>

                                {if !$apps}
                                    <div style="text-align:center; font-size:40px; padding-bottom: 40px;">{$API->LANG->_('No content yet')}!</div>
                                {else}
<div class="aa_apps_gridDIV_2">
<section id="aa_apps_gridSECTION_1">
		<ul id="aa_app_cellUL_12">
                                        {foreach from=$apps item=a}
                                            <li id="aa_app_cellLI_1">
<a href="{$API->SEO->make_link('view','trackid',$a.trackid)}" id="aa_app_cellA_3"><img src="{$a.image}" width="100" height="100" alt="{$a.name}" {if $a.gtype=='ios'}id="aa_app_cellIMG_4"{/if} /></a>				<h3 id="aa_app_cellH3_5">
					<a href="{$API->SEO->make_link('view','trackid',$a.trackid)}" id="aa_app_cellA_6">{$a.name|truncate:40}</a>
				</h3>
				<h4 id="aa_app_cellH4_7">
					<a href="{$API->SEO->make_link('search','genre',$a.genre_id,'compatibility',$compatibility)}" id="aa_app_cellA_8">{$API->LANG->_($a.gname)}{if $a.gtype=='mas'} (Mac){elseif $a.gtype=='ios'} (iOS){elseif $a.gtype=='books'} ({$API->LANG->_('Books')}){/if}</a>
				</h4>
<h4 id="aa_app_cellH4_7">
					<a href="{$API->SEO->make_link('view','trackid',$a.trackid)}" id="aa_app_cellA_8">{$API->LANG->_('Downloads')}:{(int)$a.clicks}</a>
				</h4>
                                                </li>
                                        {/foreach}


</ul>
                                </section>
</div>
                                {/if}


                            </div>

                            {if !is_premium()}
                                {literal}

                                    <!-- ads -->
                                    <center>  <div style="" id="aaa">
<iframe scrolling="no" style="border: 0; width: 728px; height: 90px;" src="//coinurl.com/get.php?id=26493&SSL=1"></iframe>

                                        </div> </center>
                                    {/literal}
                                {/if}
                            <div class="right">
                                {$pagercode}
                            </div>

                {include file="footer.tpl"}