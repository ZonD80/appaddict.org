{include file="header.tpl"}
<body class="search">
	<span id="titleFor" class="ACHidden">{$API->LANG->_('Search results')}</span>

{include file="navigation.tpl"}

<div id="globalheader-loaded-test"></div>

	<div id="top">

  
</div>
	
	<div id="container">
		<h1><img src="./itunes_files/title_search_results20090828.png" alt="Search Results" width="191" height="23"></h1>

		<div class="shortcuts" id="shortcut" style="display:none;"></div>
		<div id="main">
			<div class="bar content">
				<form amethod="get" class="search">
                                    <input type="hidden" name="genre" value="{$genre}"/>
                                    <input type="hidden" name="dev" value="{$dev}"/>
                                    <input type="hidden" name="c" value="{$cracker}"/>
					<label for="barsearchapple"><input name="q" value="{$q}" type="text" id="barsearchapple" class="applesearch prettysearch" results="0" placeholder="Search AppAddict"></label>
                                        <div class="moreoption">
                                        <select name="type">
            
            <option value="" {if !$type} selected="selected"{/if}>{$API->LANG->_('Type')}: {$API->LANG->_('All')}</option>
            <option value="app" {if $type=='app'} selected="selected"{/if}>{$API->LANG->_('Type')}: {$API->LANG->_('Apps')}</option>
            <option value="book" {if $type=='book'} selected="selected"{/if}>{$API->LANG->_('Type')}: {$API->LANG->_('Books')}</option>
        </select>
        <select name="compatibility">
            <option value="0" {if !$compatibility} selected="selected"{/if}>{$API->LANG->_('Platform')}: {$API->LANG->_('All')}</option>
            <option value="1"{if $compatibility==1} selected="selected"{/if}>{$API->LANG->_('Platform')}: iOS</option>
            <option value="2"{if $compatibility==2} selected="selected"{/if}>{$API->LANG->_('Platform')}: iPhone</option>
            <option value="3"{if $compatibility==3} selected="selected"{/if}>{$API->LANG->_('Platform')}: iPad</option>
            <option value="4"{if $compatibility==4} selected="selected"{/if}>{$API->LANG->_('Platform')}: Mac</option>
        </select>
        <select name="price">
            <option value="0" {if !$price} selected="selected"{/if}>{$API->LANG->_('Price')}: {$API->LANG->_('All')}</option>
            <option value="1" {if $price==1} selected="selected"{/if}>{$API->LANG->_('Price')}: {$API->LANG->_('Paid')}</option>
            <option value="2" {if $price==2} selected="selected"{/if}>{$API->LANG->_('Price')}: {$API->LANG->_('Free')}</option>
        </select>
                                        <input type="submit" class="prettysearchbutton" value="{$API->LANG->_('Search')}"/>&nbsp;<a href="{$API->SEO->make_link('search')}"/><input type="button" class="prettysearchbutton" value="{$API->LANG->_('Reset')}"/></a>
                                        </div>
                                </form>
                            
			</div>

			<div id="content" class="content box">
				<div class="grid2col">
					<div class="column first">
						<div class="products">
                                                    {if !$noresults}
							<div id="resultsCount" class="resultcount">{$API->LANG->_('ABOUT_RESULTS_FOUND')}{if $genre} {$API->LANG->_('in selected category')}{/if}{if $cracker} {$API->LANG->_('with selected cracker')}{/if}{if $dev} {$API->LANG->_('with selected developer')}{/if}{if $q} {$API->LANG->_('for')} '{$q}'{/if}.</div>{$pagercode}
							{else}
                                                        <p id="www-no-results" class="results">{$API->LANG->_('No results were found. Please try a different search')}.</p>
                                                        {/if}
							<div id="featuredWWW" class="grid3col featured"></div>
          
							<div id="www" class="" style="display: block;">
                                                            <ul id="results-www" class="results">
                                                                {foreach from=$apps item=m}
                                                                <li>
                                                                    <div class="artwork" style="float:left;padding-right:10px;"><img width="100" height="100" alt="{$m.name}" class="artwork" src="{$m.image}"><span class="mask"></span></div>
                                                                    
                                                                    <a href="{$API->SEO->make_link('view','trackid',$m.id)}"><b>{$m.name}</b></a><p class="desc">{if $m.type=='app'}{$API->LANG->_('Version')}: {$m.version}{elseif $m.type=='book'}{$API->LANG->_('Books')}{/if}, {$API->LANG->_('Price')}: {$m.price}, {$API->LANG->_($m.gname)}{if $m.gtype=='mas'} (Mac){elseif $m.gtype=='ios'} (iOS){elseif $m.gtype=='books'} ({$API->LANG->_('Books')}){/if}<br/>{$API->LANG->_('published by')} <a href="{$API->SEO->make_link('search','dev',$m.artist_id)}">{$m.pname}</a></p><span><a href="{$API->SEO->make_link('view','trackid',$m.id)}">{$API->SEO->make_link('view','trackid',$m.id)}</a></span>
                                                                    

                                                                </li>
                                                            {/foreach}
                                                                </ul>
							</div>
						</div>
					</div><!--/column-->
   
					<div class="column last">
						<div class="header" style="display: block;"><h2>{$API->LANG->_('Search by genres')}</h2></div>
                                                <div id="support" class="support" style="display: block;">
                                                        
                                                        {foreach from=$genres item=g}
                                                        <h2><a href="{$API->SEO->make_link('search','genre',$g.id,'compatibility',$compatibility,'q',$q)}">{$API->LANG->_($g.name)}{if $g.type=='mas'} (Mac){elseif $g.type=='ios'} (iOS){elseif $g.type=='books'} ({$API->LANG->_('Books')}){/if}</a></h1><p>{if $section=='apps'}{$API->LANG->_('Applications uploaded')}{else}{$API->LANG->_('Applications archived')}{/if}: {$g.numapps}</p></li><br/>
                                                        {/foreach}
							</div>

						<div class="header" style="display: block;"><h2>{$API->LANG->_('New Content')}</h2></div>
						<div id="itunes" class="itunes" style="display: block;">
                                                     {if !$last}
                                                            <p id="itunes-no-results" class="results">{$API->LANG->_('No content yet')}.</p>
							
                                                       
                                                            {/if}
                                                            
                                                    <ul id="results-itunes" class="results">
                                                        {foreach from=$last item=l}
                                                        <li><a href="{$API->SEO->make_link('view','trackid',$l.id)}" class="album"><img alt="{$l.name}" src="{$l.image}"></a><span class="title"><a href="{$API->SEO->make_link('view','trackid',$l.id)}" title="{$l.name}">{$l.name}</a></span><span class="artist"><a href="{$API->SEO->make_link('view','trackid',$l.id)}">{$API->LANG->_('Download For Free')}</a></span><span class="mediaType">{$API->LANG->_($l.gname)}{if $l.gtype=='mas'} (Mac){elseif $l.gtype=='ios'} (iOS){elseif $l.gtype=='books'} ({$API->LANG->_('Books')}){/if}</span></li>
                                                        {/foreach}
                                                        </ul>
                                                        <p><a id="moresearchtools-itunes" class="more" href="{$API->SEO->make_link('index')}">{$API->LANG->_('New Content')}</a></p>
						</div>

					</div><!--/column-->
				</div><!--/grid2col-->
				{$pagercode}
			</div><!--/#content-->
		</div><!--/#main-->
	</div><!--/#container-->
	{include file="footer.tpl"}