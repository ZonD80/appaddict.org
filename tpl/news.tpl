{include file='header.tpl'}

<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">
                
                {if !is_premium()}
                        <div style="text-align:center;margin-bottom:10px;"><center> 
                                {*<iframe src="https://regmyudid.com/art/iOS8/index.html" frameborder="0" style="margin:0;padding:0;border:none;background-color:transparent" allowtransparency="true" scrolling="no" width="728" height="90"></iframe>
                                *}
                            
<iframe src="https://regmyudid.com/art/iOS8/index.html" frameborder="0" style="margin:0;padding:0;border:none;background-color:transparent" allowtransparency="true" scrolling="no" width="728" height="90"></iframe>
</center></div>{/if}

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Site News')}</h1>
                        </div>
                        <div class="right">{$API->LANG->_('Published on')} {$news.added|date_format:"%d.%m.%Y %H:%M"} GMT</div>
                                          </div>

                    <div class="center-stack">
                    <h1>{$news.title}</h1>
                    {$news.text}
                       {if !is_premium()}
                    {literal}
                    <!-- ads -->
                    <div style="margin-left:-20px;" id="aaa">
                      <center>
<iframe scrolling="no" style="border: 0; width: 728px; height: 90px;" src="//coinurl.com/get.php?id=26491&SSL=1"></iframe>
                      </center>
                     </div>
                    {/literal}
                    {/if}
                    </div>

                    <div id="left-stack">
                                                      <div class="lockup-info" style="max-height: 500px;overflow: auto;"> 
                                                          <ul class="list">
                           {foreach from=$allnews item=n}
<li{if $n.id==$news.id} class="active"{/if}><a href="{$API->SEO->make_link('news','id',$n.id)}" class="name">{$n.title}</a></li>
                               {/foreach}
                                                                       </ul></div>
                                                                       <small>{$API->LANG->_('Scroll down to continue reading')}</small>
                    </div>
                </div>


            </div>
        </div>


    </div>






{include file="footer.tpl"}