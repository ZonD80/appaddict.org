{include file='header.tpl'}
<body class="software geo-us lang-en-us no-action">

    {include file='navigation.tpl'}


       <div id="main">
        <div id="desktopContentBlockId" class="platform-content-block display-block">

            <div id="content">

                <div class="padder">

                    <div id="title" class="intro has-gcbadge">
                        <div class="left">
                            <h1>{$API->LANG->_('Donate to us')}</h1>
                        </div>
                        <div class="right">
                            {$API->LANG->_('All donations are going to servers maintenance')}
                        </div>
                    </div>
 
                    <div class="longtext">
<h2>{$API->LANG->_('Donate to us')}</h2>
<p>appaddict.org is free, and always will be free for everyone. We are really appreciate to provide such wonderfull service for you for free.</p>
<p>But, unlike AppAddict itself, servers that AppAddict is using are not free. Count it by yourself:<br/>
    - Main server<br/>
    - Backup server<br/>
    - Torrent tracker server<br/>
    - Couple of cache servers<br/>
    <br/>
    Even if these servers are hosted and maintained by third parties, we must synthronize them and keep AppAddict live, fast as possible and secure.</p>
<p>Total mounthly costs are expanding $500, so if you can, <strong>you may stictly on your wish</strong> donate any amount of money to these credentials:</p>
<h2>Donation credentials</h2>
<h1>BitCoin address: 16LkqztAL2HVyRmYZ5TrcSJX6LtcBRSXsZ <form action="https://money2btc.com" method="post">
  <input type="hidden" value="https://www.appaddict.org/donate.php" name="uri">
<input type="hidden" value="dGhhbmtz" name="data">
 <input type="hidden" value="Donate to appaddict.org" name="title">
   <input type="hidden" value="16LkqztAL2HVyRmYZ5TrcSJX6LtcBRSXsZ" name="address">
$<input type="text" size="3" value="10" name="amount">
   <input type="submit" value="Donate now">
</form></h1>
<br/>
<br/>
<h1>Thanks for donation, or even just using AppAddict!</h1>

                    </div>


                </div>


            </div>
        </div>


    </div>


{include file="footer.tpl"}