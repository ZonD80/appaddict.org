{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


    <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Extend account to premium')}</h1>
                        </div>
                        <div class="right">
                            {$API->LANG->_('Payment status')}: {$payment_status}<br/>
                            <a href="{$API->SEO->make_link('premium')}"/>{$API->LANG->_('Go back to package selection')}</a>
                        </div>
                    </div>

                    <div class="longtext">
                        {if $transaction}
                            {if $transaction.status=='failed_gw'||$transaction.status=='failed_bc'}
                                <h1 style="color:red;">{$API->LANG->_('Your transaction %s failed.',$transaction['uuid'])}</h1>
                                <h1><a href="{$API->SEO->make_link('premium')}">{$API->LANG->_('Try again')}</a></h1>
                                {elseif $transaction.status=='pending_gw'}
                                <h1>{$API->LANG->_('Your transaction %s is pending. Please wait.',$transaction['uuid'])}</h1>
                            {elseif $transaction.status=='pending_bc'||$transaction.status=='ok'}
                                <h1 style="color:green;">{$API->LANG->_('Thank you for your purchase!')}</h1>
                            {/if}
                        {else}
                            <h1>You are purchasing "{$package}", please read our purchase policy and proceed to payment only when you are agreed with this!</h1>
                            <br/>
                            <h2>Subject of Purchasing</h2>
                            <p>You are purchasing Service, which comply with <a href="{$API->SEO->make_link('tos')}"/>Terms Of Service</a> and <a href="{$API->SEO->make_link('privacy')}"/>Privacy Policy</a>.</p>
                            <h2>Supplier Responsibility</h2>
                            <p>AppAddict, as provider of Service, is responsible for Service functionality in purchased period of time. We hope that during the use of the Service you will not have certain problems, the Service will be stable and will be provided in time. If a situation arises in which it will be impossible to provide the Service, you can expect to be compensated in the form of extension Service usage for the same period of time during which the provision of Service was impossible. In the case of total cancellation of Service due to circumstances beyond the supplier reason, you will not be granted any compensation paid for the remaining time of Service provision.</p>
                            <h2>Buyer Responsibility</h2>
                            <p>You, as a buyer, obliged to follow the buying process, as described below. You have no right to claim the money paid by you, if you have already started to use the Service and decided to suspend the service before the end of the period of use of paid Service.</p>
                            <h2>Purchase Process</h2>
                            <p>We are accepting payments in bitcoins, so we are using payment gateway to process your transaction. Using of bitcoin means that you have to wait up to 3 hours once bitcoin network process your transaction.</p>
                            <p>Once you click on purchase button, you will be redirected to payment gateway. Please check that payment gateway is running on secure connection, you will see <span style="color:green;">https://</span> in your browser address bar.</p>
                            <ol style="margin-left: 18px;"><li>Pay as you pay in other internet shops with your credit card or PayPal account.</li>
                                <li>Wait till payment gateway process your transaction.</li>
                                <li>Click on "Back to seller's website" button.</li>
                                <li>System will automatically make your account premium.</li></ol>
                            <br/>
                            <h1>By clicking "Buy {$package}" you agree with purchase policy</h1>
                            <center><div class="checkout_button">{$checkout_button}</div>
                                <div class="checkout_button">{$bitcoin_button}</div></center>
<hr/>
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
                            {/if}
                    </div>


                </div>


            </div>
        </div>


    </div>


    {include file="footer.tpl"}