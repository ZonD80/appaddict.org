{include file='header.tpl'}

<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}
    <br>
    <h2><img src="itunes_files2/choose_title@2x.png" alt="{$API->LANG->_('Choose your country or region')}" height="43" width="570"></h2>


    <div id="main">
        <div class="countrycontent selfclear" id="countrycontent">
            <div class="section selfclear" id="africa_mideast">
                <h2>{$API->LANG->_('Select site interface language')}</h2>
                <ul>
                    <li><a href="{$API->SEO->make_link('setlang','l','en')}">
                            <img src="itunes_files2/flags/usa.png" alt="US" height="30" width="30">
                            <span><span>{$API->LANG->_('USA')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','de')}">
                            <img src="itunes_files2/flags/germany.png" alt="DE" height="30" width="30">
                            <span><span>{$API->LANG->_('Germany')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','fr')}">
                            <img src="itunes_files2/flags/france.png" alt="FR" height="30" width="30">
                            <span><span>{$API->LANG->_('France')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','ru')}">
                            <img src="itunes_files2/flags/russia.png" alt="RU" height="30" width="30">
                            <span><span>{$API->LANG->_('Russia')}</span></span>
                        </a></li>

                    <li><a href="{$API->SEO->make_link('setlang','l','pt')}">
                            <img src="itunes_files2/flags/portugal.png" alt="PT" height="30" width="30">
                            <span><span>{$API->LANG->_('Portugal')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','it')}">
                            <img src="itunes_files2/flags/italy.png" alt="IT" height="30" width="30">
                            <span><span>{$API->LANG->_('Italy')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','ar')}">
                            <img src="itunes_files2/flags/saudi_arabia.png" alt="AR" height="30" width="30">
                            <span><span>{$API->LANG->_('Arabic')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','sr')}">
                            <img src="itunes_files2/flags/serbia.png" alt="SR" height="30" width="30">
                            <span><span>{$API->LANG->_('Serbia')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','ro')}">
                            <img src="itunes_files2/flags/romania.png" alt="RO" height="30" width="30">
                            <span><span>{$API->LANG->_('Romania')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','es')}">
                            <img src="itunes_files2/flags/spain.png" alt="ES" height="30" width="30">
                            <span><span>{$API->LANG->_('Spain')}</span></span>
                        </a></li>
                    <li><a href="{$API->SEO->make_link('setlang','l','tr')}">
                            <img src="itunes_files2/flags/turkey.png" alt="TR" height="30" width="30">
                            <span><span>{$API->LANG->_('Turkey')}</span></span>
                        </a></li>
                        <li><a href="{$API->SEO->make_link('setlang','l','hg')}">
                            <img src="itunes_files2/flags/hungary.png" alt="HG" height="30" width="30">
                            <span><span>{$API->LANG->_('Hungary')}</span></span>
                        </a></li>

                </ul>
            </div>

            <p>
                {$API->LANG->_('Thank these people for their contributions')}: Mucles, DblD, Kalenji, Nawaf_NM, -ST@K€RS-, Sheiben, hawaiisun2004, ZonD80, khsora, Pepito, The BroOfTheCentury Inc.
            </p>
        </div>
    </div>


    {include file="footer.tpl"}