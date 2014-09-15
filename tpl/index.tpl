{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}

    {include file='update.tpl'}

    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">
            <div id="content">         
                <div class="padder">
                    <div id="title" class="intro has-gcbadge">
                        <div class="text-center">
                            <center>
                                <h1>{$API->LANG->_('New &amp; Noteworthy')}</h1>
                                <br><br>
                            </center>  

                            {if !is_premium()}
                                <div style="text-align:center;margin-bottom:10px;">

                                    <iframe src="https://regmyudid.com/art/iOS8/index.html" frameborder="0" style="margin:0;padding:0;border:none;background-color:transparent" allowtransparency="true" scrolling="no" width="728" height="90"></iframe>
                                </div>{/if}                            
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
                                <!--{$pagercode}-->
                                <br>
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
                                       {if $a.gtype=='books'}
<li id="aa_books_LI_3">
<a href="{$API->SEO->make_link('view','trackid',$a.trackid)}" id="aa_books_A_5"><img src="{$a.image}" width="113" height="170" alt="{$a.name}" id="aa_books_IMG_6" /></a>
<h3 id="aa_books_H3_7">
<a href="{$API->SEO->make_link('view','trackid',$a.trackid)}" id="aa_books_A_8">{$a.name|truncate:40}</a>
</h3>
<h4 id="aa_app_cellH4_7">
<a href="{$API->SEO->make_link('search','genre',$a.genre_id,'compatibility',$compatibility)}" id="aa_app_cellA_8">{$API->LANG->_($a.gname)}{if $a.gtype=='mas'} (Mac){elseif $a.gtype=='ios'} (iOS){elseif $a.gtype=='books'} ({$API->LANG->_('Books')}){/if}</a>
</h4>
<h4 id="aa_books_H4_9">
<a href="{$API->SEO->make_link('search','dev',$a.artist_id)}" id="aa_books_A_10">{$a.pname}</a>
</h4>
</li>
                                       {else}
                                            <li id="aa_app_cellLI_1">
                                                <a href="{$API->SEO->make_link('view','trackid',$a.trackid)}" id="aa_app_cellA_3"><img src="{$a.image}" width="100" height="100" alt="{$a.name}" {if $a.gtype=='ios'}id="aa_app_cellIMG_4"{/if} /></a>
                                                <h3 id="aa_app_cellH3_5">
                                                    <a href="{$API->SEO->make_link('view','trackid',$a.trackid)}" id="aa_app_cellA_6">{$a.name|truncate:40}</a>
                                                </h3>
                                                <h4 id="aa_app_cellH4_7">
                                                    <a href="{$API->SEO->make_link('search','genre',$a.genre_id,'compatibility',$compatibility)}" id="aa_app_cellA_8">{$API->LANG->_($a.gname)}{if $a.gtype=='mas'} (Mac){elseif $a.gtype=='ios'} (iOS){elseif $a.gtype=='books'} ({$API->LANG->_('Books')}){/if}</a>
                                                </h4>
                                                <h4 id="aa_app_cellH4_7">
                                                    <a href="{$API->SEO->make_link('search','dev',$a.artist_id)}" id="aa_app_cellA_8">{$a.pname}</a>
                                                </h4>
                                            </li>
                                         {/if}
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
                                    <iframe scrolling="no" style="border: 0; width: 728px; height: 90px;" src="//coinurl.com/get.php?id=26489&SSL=1"></iframe>
                                </div> </center>
                            {/literal}
                        {/if}

                    <div class="right">
                        {$pagercode}
                    </div>
                    <br>
                    <center>{literal}
                        <a href="https://twitter.com/AANewNoteworthy" class="twitter-follow-button" data-show-count="false">Follow @AANewNoteworthy</a>
                        <script>!function(d, s, id) {
                                var js, fjs = d.getElementsByTagName(s)[0];
                                if (!d.getElementById(id)) {
                                    js = d.createElement(s);
                                    js.id = id;
                                    js.src = "//platform.twitter.com/widgets.js";
                                    fjs.parentNode.insertBefore(js, fjs);
                                }
                            }(document, "script", "twitter-wjs");</script>{/literal}</center>
                </div>
            </div>


        </div>


        {include file="footer.tpl"}