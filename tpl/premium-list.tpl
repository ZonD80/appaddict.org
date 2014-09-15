{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Extend account')}</h1>
                        </div>
                        <div class="right">
                            <h1><img src="https://www.appaddict.org/images/aa-uberheader-premium.png" title="{$API->LANG->_('Premium')}"/></h1>
                        </div>
                    </div>
                    <div class="center-stack">
                        {if is_premium()}
                            <h2>{$API->LANG->_('Premium services for your account available till %s',$API->account['premium_expired']|date_format:"%d.%m.%Y %H:%M")} GMT</h2><br/>
                        {else}
                            <h1>{$API->LANG->_('Your account type is not premium')}</h1>
                        {/if}
                        <h2>{$API->LANG->_('Please select services package')}:</h2>
                        <table id="trackr" width="100%">
                            <tr><th>{$API->LANG->_('Name')}</th><th>{$API->LANG->_('Description')}</th><th>{$API->LANG->_('Price')}</th><th>{$API->LANG->_('Actions')}</th></tr>
                            <tbody>
                                <tr><td>Premium 1 day</td><td>Access to AppAddict's premium features for one day</td><td>$1.00</td><td><a href="{$API->SEO->make_link('premium','days','1')}"/>{$API->LANG->_('Proceed to checkout')}</a></td></tr>
                                <tr><td>Premium 7 days</td><td>Access to AppAddict's premium features for one week</td><td>$3.99</td><td><a href="{$API->SEO->make_link('premium','days','7')}"/>{$API->LANG->_('Proceed to checkout')}</a></td></tr>
                                <tr><td>Premium 30 days</td><td>Access to AppAddict's premium features for month</td><td>$7.99</td><td><a href="{$API->SEO->make_link('premium','days','30')}"/>{$API->LANG->_('Proceed to checkout')}</a></td></tr>
                                <tr><td>Premium 90 days</td><td>Access to AppAddict's premium features for 3 months</td><td>$14.99</td><td><a href="{$API->SEO->make_link('premium','days','90')}"/>{$API->LANG->_('Proceed to checkout')}</a></td></tr>
                                <tr><td>Premium 180 days</td><td>Access to AppAddict's premium features for half of year</td><td>$26.99</td><td><a href="{$API->SEO->make_link('premium','days','180')}"/>{$API->LANG->_('Proceed to checkout')}</a></td></tr>
                                <tr><td>Premium 365 days</td><td>Access to AppAddict's premium features for one year<br/><br/>Also available as free add-on to RegMyUDID premium<br/><a style="color:red;text-decoration: underline;" href="https://regmyudid.com/#Section-2"/>Click here for more info</a></td><td>$45.99</td><td><a href="{$API->SEO->make_link('premium','days','365')}"/>{$API->LANG->_('Proceed to checkout')}</a></td></tr>
                                <tr><td>Ultimate premium</td><td>Access to AppAddict's premium features forever</td><td>$149.99</td><td><a href="{$API->SEO->make_link('premium','days','3650')}"/>{$API->LANG->_('Proceed to checkout')}</a></td></tr>
                            </tbody>
                        </table>
                        <br/>
                        <h2>{$API->LANG->_('Or apply voucher from one of our resellers')}:</h2>
                        <div class="resellers">
                            <p>
                            <ul>
                                <li>
                                    <a href="http://regmyudid.ru/voucher.php" target="_blank">RegMyUDID.ru</a> - Official RegMyUDID Reseller in Russia
                                </li>
                            </ul>
                            </p>
                        </div>
                        <center><form method="POST">
                                <input type="text" placeholder="{$API->LANG->_('Voucher Code')}" size="50" name="voucher" value="{$voucher}"/>
                                <input type="hidden" name="from" value="voucher"/>
                                <input type="submit" value="{$API->LANG->_('Apply')}"/>
                            </form></center>
                    </div>
                    <div id="left-stack">

                        <b>{$API->LANG->_('What is premium account?')}</b><br/><br/>
                        {$API->LANG->_('PREMIUM_PAGE_DESCRIPTION')}
                    </div>
                </div>


            </div>
        </div>


    </div>


    {include file="footer.tpl"}