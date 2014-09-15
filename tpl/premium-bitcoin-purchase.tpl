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
                            {$API->LANG->_('Payment status')}: {$API->LANG->_('Verification')}<br/>
                            <a href="{$API->SEO->make_link('premium')}"/>{$API->LANG->_('Go back to package selection')}</a>
                        </div>
                    </div>
 
                    <div class="longtext">
                       <p>{$API->LANG->_('You are buying premium account for %s days with bitcoins',$days)}.<br/><span style="color:red;">{$API->LANG->_('BITCOIN_EXPIRATION_NOTICE')} </span><span class="aa-expiration-time"></span></span></p>
                        <center>{$API->LANG->_('SEND_BITCOIN_NOTICE',$amount,$address)}.<br/>
{$API->LANG->_('Enter transaction id here')}:<br/>
                        <form method="POST" target="_blank">
                            <input type="hidden" name="payment_check" value="1"/>
                            <input name="txid" type="text" required="required" size="64" maxlength="64"/>
                            <input type="hidden" name="purchase_ticket" value="{$purchase_ticket}"/>
                            <input type="submit" value="{$API->LANG->_('Check')} ({$API->LANG->_('new window will be opened')})"/>
                        </form> </center>
                    </div>


                </div>


            </div>
        </div>


    </div>

<script>

    function timer() {
        SECONDS_LEFT=SECONDS_LEFT-1;
        $('.aa-expiration-time').text(nice_time(SECONDS_LEFT));
    }

    $(document).ready(function(){
    SECONDS_LEFT={$expires_time};
    window.setInterval(timer,1000);
    });
    </script>


{include file="footer.tpl"}