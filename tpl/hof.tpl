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
                <h1><img src="itunes_files2/hof_title.png" alt="{$API->LANG->_('Hall Of Fame')}" height="46" width="287"></h1>

                <p class="intro">{$API->LANG->_('Hall Of Fame collecting info about Top Crackers and about registered Cracking Teams')}
                    .</p>

                <div class="clear"></div>
                <p></p>

                <div class="view_boxes_container">
                    <div class="view_boxes">
                        <a href="{$API->SEO->make_link('teams')}">
                            <h2>{$API->LANG->_('view by teams')}</h2>
                            <img src="itunes_files/view_by_teams.png">
                        </a>
                    </div>
                    <div class="o_r">{$API->LANG->_('or')}</div>
                    <div class="view_boxes right">
                        <a href="{$API->SEO->make_link('crackers','price',1)}">
                            <h2>{$API->LANG->_('view by crackers')}</h2>
                            <img src="itunes_files/view_by_crackers.png">
                        </a>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="contact rounded clear">
                    <span class="title">{$API->LANG->_('Want to be here?')}</span>
                    <span class="phone">{$API->LANG->_('Become a verified cracker')}!</span>
                    <span class="email"><a
                            href="{$API->SEO->make_link('become-verified')}">{$API->LANG->_('Submit proposal')}</a></span>
                </div>
                <div class="section directors clear">
                    <h2>{$API->LANG->_('Site Staff')}</h2>
                    <ul>
                        <li>
                            <strong>iChr0niX</strong>

                            <p>
                                Founder<br>Team management, Design
                                <br>appaddict.org
                                <br></p>
                        </li>
                        <li>
                            <strong>Zorro</strong>

                            <p>
                                iOS App Developer<br>Design
                                <br>appaddict.org
                                <br></p>
                        </li>

                        <li>
                            <strong>Freddy</strong>

                            <p>
                                Web Services &amp; APIs<br>Infrastructure operations
                                <br>appaddict.org
                                <br></p>
                        </li>
                        <li>
                            <strong>dkbame</strong>

                            <p>
                                Administrator<br>Apps Mod, Cracker
                                <br>appaddict.org
                                <br></p>
                        </li>
                        <li>
                            <strong>DblD</strong>

                            <p>
                                Administrator<br>Apps and Forum Mod
                                <br>appaddict.org
                                <br></p>
                        </li>
                        <li>
                            <strong>urhacked</strong>

                            <p>
                                Staff Member<br>Forum Mod
                                <br>appaddict.org
                                <br></p>
                        </li>
                        <li>
                            <strong>kakarot1925</strong>

                            <p>
                                Staff Member<br>Apps Mod, Cracker
                                <br>appaddict.org
                                <br></p>
                        </li>
                        <li>
                            <strong>chikipata94</strong>

                            <p>
                                Staff Member<br>Apps Mod, Cracker
                                <br>appaddict.org
                                <br></p>
                        </li>
                        <li>
                            <strong>Steve Jobs - RIP</strong>

                            <p>
                                Why join the navy if you<br><strong>can be a pirate?</strong>
                                <br>Apple Founder
                                <br></p>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>


        {include file="footer.tpl"}