{include file='header.tpl'}


<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}



    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$itdata.name}</h1>
                            <h2>{$API->LANG->_('By')} {$itdata.artist.name}</h2>
                        </div>
                        <div class="right">
                        {if $itdata.gamecenter}<div class="gc-badge app-badge"><span>Game Center</span></div>{/if}
                        <a href="{$API->SEO->make_link('search','dev',{$itdata.artist.id})}" class="view-more AA-developer-link">{$API->LANG->_('View More By This Developer')}</a>

                    </div>
                    <p>{$API->LANG->_('Click on "Download Now" button under Icon')}{if $itdata['compatibility']!=4&&$appdata.type!='book'} {$API->LANG->_('or')} <a href="appaddict://trackid={$appdata.trackid}">{$API->LANG->_('open AppAddict app')}</a> {$API->LANG->_('on iDevice')}{/if} {$API->LANG->_('to download this content')} <b>{$API->LANG->_('for free')}</b></p>
                </div>

                <div class="center-stack">



                    <div more-text="More" metrics-loc="Titledbox_Description" class="product-review">

                        <h4>

                            {$API->LANG->_('Description')}
                        </h4>



                        <p class="truncate aa-app-description" style="height: 54px;">{$itdata.description}</p>


                        <a href="javascript://" class="more-link">...{$API->LANG->_('More')}</a></div>



                    <div class="app-links">{if $itdata.artist.website}<a rel="nofollow" target="_blank" href="{$itdata.artist.website}" class="see-all aa-developer-website">{$itdata.artist.name} {$API->LANG->_('Web Site')}</a>{/if}{if $itdata.artist.support}<a rel="nofollow" target="_blank" href="{$itdata.artist.support}" class="see-all aa-developer-support">{$itdata.name} {$API->LANG->_('Support')}</a>{/if}</div>
                    {if !is_premium()}
                        {literal}

                            <!-- ads -->
                            <div style="margin-left:-20px;" id="aaa">
                                <center>
                                <iframe src="https://regmyudid.com/art/iOS8/index.html" frameborder="0" style="margin:0;padding:0;border:none;background-color:transparent" allowtransparency="true" scrolling="no" width="728" height="90"></iframe>
                                <br>
                                </center>
                            </div>
                        {/literal}
                    {/if}

                    {if $itdata.whatsnew}
                        <div more-text="More" class="product-review">

                            <h4>

                                {$API->LANG->_("What's New in Version")} <span class="aa-app-version" style="color:black;">{$itdata.version}</span>
                            </h4>



                            <p class="truncate" style="height: 54px;">{$itdata.whatsnew}</p>


                            <a href="javascript://" class="more-link">...{$API->LANG->_('More')}</a></div>
                        {/if}


                    {if $itdata.screenshots}
                        <div metrics-loc="Swoosh_" rows="1" class="swoosh lockup-container application large screenshots">

                            <div class="title">


                                <h2>{$API->LANG->_('Screenshots')}</h2>



                                {if $itdata.compatibility!=4&&$appdata.type!='book'}
                                    <div class="pill">
                                        {if $itdata.compatibility==1}
                                            <a href="javascript://" class="first active aa-screens-button-iphone" onclick="return changescreens('iphone', 'ipad');">iPhone</a>

                                            <a href="javascript://" class="aa-screens-button-ipad" onclick="return changescreens('ipad', 'iphone');">iPad</a>
                                        {elseif $itdata.compatibility==2}
                                            <a href="javascript://" class="aa-screens-button-iphone">iPhone</a>

                                        {else}
                                            <a href="javascript://" class="aa-screens-button-ipad">iPad</a>

                                        {/if}
                                    </div>
                                {/if}



                                <a metrics-loc="Seeall" href="" class="see-all"><span></span></a>
                            </div>

                            <div class="toggle">
                                {if $itdata.compatibility==4||$appdata.type=='book'}                               
                                    <div num-items="5" class="content" style="display: block;">
                                        <div style="width: {$osx_screenshots_width}px;" class="image-wrapper aa-screens-osx">
                                            {foreach from=$itdata.screenshots.osx item=s}
                                                <div class="lockup">
                                                    <img alt="OS X {$API->LANG->_('Screenshot')}" class="{$s.class}" src="{$s.src}">
                                                </div>
                                            {/foreach}
                                        </div></div>{/if}

                                    {if $appdata.type!='book'}
                                        {if $itdata.compatibility==1||$itdata.compatibility==2}
                                            <div metrics-loc="iPhone" num-items="5" class="content iphone-screen-shots items5" style="display: block;">
                                                <div style="min-width: 1000px;" class="image-wrapper aa-screens-iphone">
                                                    {foreach from=$itdata.screenshots.iphone item=s}
                                                        <div class="lockup">
                                                            <img alt="iPhone {$API->LANG->_('Screenshot')}" class="{$s.class}" src="{$s.src}">
                                                        </div>
                                                    {/foreach}
                                                </div></div>{/if}
                                                {if $itdata.compatibility==1||$itdata.compatibility==3}                               
                                                <div metrics-loc="iPad" num-items="5" class="content ipad-screen-shots items5 ipad" style="display: {if $itdata.compatibility==3}block{else}none{/if};">
                                                    <div style="width: {$ipad_screenshots_width}px;" class="image-wrapper aa-screens-ipad">
                                                        {foreach from=$itdata.screenshots.ipad item=s}
                                                            <div class="lockup">
                                                                <img alt="iPad {$API->LANG->_('Screenshot')}" class="{$s.class}" src="{$s.src}">
                                                            </div>
                                                        {/foreach}
                                                    </div></div>{/if}
                                                {/if}
                                        </div>
                                    </div>

                                {/if}

                                <div class="customer-reviews">
                                    <h4>{$API->LANG->_('Customer Reviews')}</h4>
                                {if !$itdata.reviews}<center><h3>{$API->LANG->_('No reviews yet')}</h3></center>{/if}
                                        {foreach from=$itdata.reviews item=r}
                                    <div more-text="{$API->LANG->_('More')}" class="customer-review">





                                        <h5>

                                            <span class="customerReviewTitle">{$r.title}</span>
                                            <div class="rating" role="img" tabindex="-1" aria-label="{$r.rating} stars">
                                                <div>
                                                    {for $i=1 to $r.rating}
                                                        <span class="rating-star">&nbsp;</span>
                                                    {/for}
                                                </div></div>

                                        </h5>

                                        <span class="user-info">{$r.author}</span>




                                        {if strlen($r.text)<=500}
                                            <p class="content" will-truncate-max-height="0" data-text-truncate-lines="5">
                                                {$r.text}
                                            </p>
                                        {else}
                                            <p class="content truncate" will-truncate-max-height="0" data-text-truncate-lines="5" style="height:54px;">
                                                {$r.text}
                                            </p>   
                                            <a href="javascript://" class="more-link">...{$API->LANG->_('More')}</a>


                                        {/if}


                                    </div>
                                {/foreach}                            


                            </div>



                            <div metrics-loc="Swoosh_" rows="1" class="swoosh lockup-container application large">

                                <div class="title">


                                    <h2>{$API->LANG->_('Customers Also Bought')}</h2>





                                </div>



                                <div num-items="5" class="content aa-related-apps"><div>
                                    {if !$itdata.alsobought}<center><h3>{$API->LANG->_('Nothing')}</h3></center>{/if}
                                            {foreach from=$itdata.alsobought item=a}
                                        <div class="lockup small application">

                                            <a href="{$API->SEO->make_link('view','trackid',$a.trackid,'store',$a.store,'type',$a.type)}" class="artwork-link"><div class="artwork"><img height="100" alt="{$a.name}" class="artwork" src="{$a.image}">{if $a.compatibility!='4'&&!preg_match('#newsstand#si',$a.image)&&$a.type!='book'}<span class="mask"></span>{/if}</div></a>

                                            <div class="lockup-info">



                                                <ul class="list"><li><a href="{$API->SEO->make_link('view','trackid',$a.trackid,'store',$a.store,'type',$a.type)}" class="name">{$a.name}</a></li>
                                                {if $a.type=='app'}<li><a href="{$API->SEO->make_link('search','genre',$a.genre.id)}" class="genre">{$API->LANG->_($a.genre.name)}{if $a.genre.id>12000} (Mac){/if}</a></li>
                                            {elseif $a.type=='book'}<li><a href="{$API->SEO->make_link('search','dev',$a.artist.id)}" class="genre">{$a.artist.name}</a></li>{/if}
                                            <li><a href="{$API->SEO->make_link('view','trackid',$a.trackid,'store',$a.store,'type',$a.type)}" class="action view-in-itunes"><span>{$API->LANG->_('View on')} AppAddict</span></a></li></ul>
                                    </div>
                                </div>
                            {/foreach}
                        </div></div>

                </div>
                {if !is_premium()}
                    {literal}

                        <!-- ads -->
                        <div style="margin-left:-20px;" id="aaa">
                            <center>
                                <iframe scrolling="no" style="border: 0; width: 728px; height: 90px;" src="//coinurl.com/get.php?id=26494&SSL=1"></iframe>
                            </center>
                        </div>
                    {/literal}
                {/if}
            </div>

            <div id="left-stack">

                <div rating-software="200,itunes-games" parental-rating="1" class="lockup product application">
                    <a href="{$API->SEO->make_link('download','trackid',$itdata.trackid)}"><div class="artwork"><img width="175" alt="{$itdata.name}" class="artwork aa-app-image" src="{$itdata.image}">{if $appdata.compatibility!='4'&&!preg_match('#newsstand#si',$itdata.image)&&$appdata.type!='book'}<span class="mask"></span>{/if}</div></a>
                    <a href="{$API->SEO->make_link('download','trackid',$itdata.trackid)}" class="downloadnows">{$API->LANG->_('Download For Free')}</a>

                    {if $app_tracked}<a id="trackapp-a" href="javascript://" onclick="return trackapp(1);"><img id="trackapp-img" src="/images/untrack-new.png" title="{$API->LANG->_('Untrack this app')}"/></a>
                {else}<a id="trackapp-a" href="javascript://" onclick="return trackapp(0);"><img id="trackapp-img" src="/images/track-new.png" title="{$API->LANG->_('Track this app')}"/></a>{/if}                       


            {if $archived_count}<a href="{$API->SEO->make_link('archive','trackid',$appdata.trackid)}">{$archived_count} {$API->LANG->_('archived versions available')}</a><br/><br/>{/if}

            <div class="fat-binary-blurb">
                {if $appdata.compatibility==1}
                    <span class="fat-binary-badge aa-plus-badge"></span><span class="aa-plus-text">{$API->LANG->_('This app is designed for both iPhone and iPad')}</span>
                    {elseif $appdata.compatibility==2}
                    <span class="faa-plus-badge"></span><span class="aa-plus-text">{$API->LANG->_('This app is designed for iPhone only')}</span>
                    {elseif $appdata.compatibility==3}
                    <span class="faa-plus-badge"></span><span class="aa-plus-text">{$API->LANG->_('This app is designed for iPad only')}</span>
                    {elseif $appdata.compatibility==4}
                    <span class="faa-plus-badge"></span><span class="aa-plus-text">{$API->LANG->_('This app is designed for OS X')}</span>
                    {/if}
            </div>

            <ul class="list"><li><div class="price aa-price">{if $appdata.compatibility!=4}iTunes{else}Mac App Store{/if} {$API->LANG->_('price')}: {$itdata.price}</div></li>
                <li class="genre"><span class="label">{$API->LANG->_('Category')}: </span><a class="aa-genre-link" href="{$API->SEO->make_link('search','genre',{$itdata.genre.id})}">{$API->LANG->_($itdata.genre.name)}{if $itdata.genre.id>12000} (Mac){/if}</a></li>
                <li class="release-date aa-updated"><span class="label">{$API->LANG->_('Updated')}: </span>{$itdata.published}</li>
                    {if $appdata.type=='app'}
                    <li><span class="label aa-version">{$API->LANG->_('Version')}: </span>{$itdata.version}</li>
                    <li><span class="label aa-size">{$API->LANG->_('Size')}: </span>{$itdata.size}</li>
                    {elseif $appdata.type=='book'}
                    <li><span class="label aa-size">{$API->LANG->_('Print Length')}: </span>{$itdata.printlength}</li>
                    {/if}
                <li class="language"><span class="label aa-language">{$API->LANG->_('Language')}: </span>{$itdata.languages}</li>
                <li><span class="label aa-seller">{$API->LANG->_('Seller')}: </span>{$itdata.seller}</li>
                <li class="copyright aa-copyrighter">{$itdata.publisher}</li></ul><div class="app-rating aa-rating">{$itdata.rating.text}<ul class="list app-rating-reasons aa-rating-description"><li>{$itdata.rating.description}</li></ul></div><p><span class="app-requirements aa-compatible-devices">{$API->LANG->_('Requirements')}: </span>{$appdata.compatibility_string}</p>
        </div>



        <div class="extra-list customer-ratings">
            <h4>{$API->LANG->_('Customer Ratings')}</h4>
            <div>{$API->LANG->_('Current')} AppAddict {$API->LANG->_('Version')}:</div>
            <div class="rating aa-rating-stars-current" role="img" tabindex="-1"><div></div><span class="rating-count">{$appdata.rating} {$API->LANG->_('stars')}, {$appdata.rating_count} {$API->LANG->_('Ratings')}</span>
            </div>
            <div>{$API->LANG->_('Current')} {if $appdata.compatibility!=4}iTunes{else}Mac App Store{/if} {$API->LANG->_('Version')}:</div>
            <div class="rating aa-rating-stars-current" role="img" tabindex="-1"><div></div><span class="rating-count">{$itdata.ratings.current}</span>
            </div>






            <div>{$API->LANG->_('All')} {if $appdata.compatibility!=4}iTunes{else}Mac App Store{/if} {$API->LANG->_('Versions')}:</div>
            <div class="rating" role="img" tabindex="-1"><div><!--<span class="rating-star">&nbsp;</span><span class="rating-star">&nbsp;</span><span class="rating-star">&nbsp;</span><span class="rating-star">&nbsp;</span><span class="rating-star half">&nbsp;</span>--></div><span class="rating-count">{if $itdata.ratings.all}{$itdata.ratings.all}{else}{$itdata.ratings.current}{/if}</span>
            </div>




        </div>


        {if $itdata.inapps}
            <div metrics-loc="Titledbox_Top In-App Purchases
                 " class="extra-list in-app-purchases">

                <h4>

                    {$API->LANG->_('Top In-App Purchases')}

                </h4>



                <ol class="list aa-in-app-purchases">
                    {foreach from=$itdata.inapps item=ia}
                        <li><span class="in-app-title">{$ia.name}</span><span class="in-app-price">{$ia.price}</span></li>
                            {/foreach}
                </ol>


            </div>
        {/if}



        <div class="extra-list more-by">

            <h4>

                {$API->LANG->_('More by this Developer')}
            </h4>



            <ul class="list aa-more-devs-apps">

                {foreach from=$itdata.relatedapps item=a}
                    <li>
                        <div class="lockup small application">

                            <a href="{$API->SEO->make_link('view','trackid',$a.trackid,'store',$a.store,'type',$a.type)}" class="artwork-link"><div class="artwork"><img width="75" alt="{$a.name}" class="artwork" src="{$a.image}">{if $a.compatibility!='4'&&!preg_match('#newsstand#si',$a.image)&&$a.type!='book'}<span class="mask"></span>{/if}</div></a>

                            <div class="lockup-info">



                                <ul role="presentation" class="list"><li><a href="{$API->SEO->make_link('view','trackid',$a.trackid,'store',$a.store,'type',$a.type)}" class="name">{$a.name}</a></li><li><a href="{$API->SEO->make_link('view','trackid',$a.trackid,'store',$a.store,'type',$a.type)}" class="action view-in-itunes"><span>{$API->LANG->_('View on')} AppAddict</span></a></li></ul>
                            </div>
                        </div>
                    </li>
                {/foreach}
            </ul>


        </div>

    </div>
</div>


</div>
</div>


</div>
{include file="footer.tpl"}