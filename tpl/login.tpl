{include file='doctype.tpl'}
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>AppAddict - {$API->LANG->_('Login')}</title>
    <link rel="stylesheet" href="https://idmsa.apple.com/IDMSWebAuth/views/static/css/App1259_View2/login.css">
    <script src="https://idmsa.apple.com/IDMSWebAuth/views/static/Appjavascripts/jquery-1.8.2.min.js"></script>
</head>

<body onload="javascript:urlStore();fixSafariBackButton();" onunload="function onload(event) {
  javascript:urlStore();fixSafariBackButton();
}">

  <div class="lightseed">
    <div class="content">
      <div class="container">
        <div class="navbar">
        	<div class="navbar-inner">
        		<div class="container">
        			<a href="https://www.appaddict.org/" class="brand">AppAddict</a>
        			<ul class="nav">

        			</ul>
        		</div>
        	</div>
        </div>
        <div class="row-fluid">
        <div class="span12 box">
          <div id="articlecontent" style="position:relative">
          	<div style="float:left; width:35%; border-right:1px solid #ccc;padding:0px 20px;background-color:rgb(250, 250, 250); margin:-20px 0px -20px -25px;border-top-left-radius:0px;border-bottom-left-radius:0px; position:absolute;top:0;bottom:0;">
		
          		<div style="font-size: 18px; line-height:25px;font-weight: 100; margin-top:15px;padding:15px 15px 0px 10px;">
          		  {$API->LANG->_('Login to')} AppAddict
          		</div>
		
          		<div style="color:#888;padding:20px 20px 0px 10px">
          			<a href="{$API->SEO->make_link('signup')}">{$API->LANG->_('Sign up here')}</a> | <a href="{$API->SEO->make_link('iforgot')}">{$API->LANG->_('Reset password')}</a>
          		</div>
          	</div>
          	<div style="float:right; width:50%;margin-right:30px;">





<link rel="stylesheet" type="text/css" href="https://appleid.cdn-apple.com/daw/static/09Apr2013/views/static/css/common/commonLogin.css" charset="utf-8">

<script type="text/javaScript" src="https://appleid.cdn-apple.com/daw/static/09Apr2013/views/static/common.js"></script>
<script type="text/javaScript" src="https://appleid.cdn-apple.com/daw/static/09Apr2013/views/static/Appjavascripts/FDC/dcutil_2_1.js"></script>
<script src="https://appleid.cdn-apple.com/daw/static/09Apr2013/views/static/js/commonLogin.js" type="text/javascript" charset="utf-8"></script>

	<input type="hidden" value="modern" id="modernview">
	<script>
</script>
	<table width="100%">
		<tbody>
			<tr>
				
				<td>

				<div id="ds_container"><form id="command" name="form1" action="{$API->SEO->make_link('login')}" method="POST" >
					<input type="hidden" name="returnto" value="{$returnto}"/>
					
					<!-- show heading -->
					
					
					
						
					
						<h2>{$API->LANG->_('Login')}</h2>
					
					
					
					<div class="formrow" id="formRowDiv">
					
					
					<span class="formwrap" id="formwrapstyle"> 
<div class="field-container" style="position: relative;"><input type="text" maxlength="128" size="30" value="" placeholder="{$API->LANG->_('E-mail')}" autocapitalize="off" class="input-text" autocorrect="off" spellcheck="false" tabindex="1" name="email" id="accountname"><div id="label6" class="label-text" style="display: none; color: rgb(170, 170, 170); position: absolute; top: 0px; left: 0px; width: 300px; padding: 4px 6px; text-align: start; font-size: 12px;" aria-hidden="true">{$API->LANG->_('E-mail')}</div></div></span> 

					<div class="space padder1">

	   <span class="input-msg red show">{if $error=='invalid'}
            {$API->LANG->_('Email or password is invalid')}.
           {elseif $error=='auth'} 
           {$API->LANG->_('You must be logged in to continue')}.
           {elseif $error=='access'}
            {$API->LANG->_('You must have required permissions to access this page')}.
           {/if}</span>	
					</div>
					<div class="space padder2"> <span class="input-msg show">  </span></div></div>
					<div class="formrow">
				
					
					<span id="formwrapstyle1" class="formwrap"> <div class="field-container" style="position: relative;"><input type="password" size="30" value="" placeholder="{$API->LANG->_('Password')}" autocapitalize="off" oncopy="return false;" autocorrect="off" oncut="return false;" tabindex="2" name="password" id="accountpassword"></div> 
					</span><div class="space padder3"><span class="input-msg show"> 
						
						
					 </span></div></div>

					<br> <hr class="login-panel"><div id="bot-nav"><div style="width:450px"><button class="btn bigblue" style="float:right" type="submit">{$API->LANG->_('Login')}</button></div></div>
				</form></div>
				</td>
				<td class="cellspacer" width="30%"></td>
			</tr>
		</tbody>
	</table>
</script>  

			 </div>
            <div style="clear:both"></div>
          </div>
        </div>
      </div>
      <footer>
        <hr>
        <div class="row-fluid">
          <p class="span6">
          
            <span style="-moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1); transform: scaleX(-1); display: inline-block;">&copy;</span> {date('Y')}
          </p>
          <div class="span6">
            <ul class="nav">
             <li>
                <a href="{$API->SEO->make_link('tos')}">{$API->LANG->_('Terms Of Service')}</a>              
                <span class="break">
                  |
                </span>
              </li>
              <li>
                <a href="{$API->SEO->make_link('privacy')}">{$API->LANG->_('Privacy Policy')}</a>
                <span class="break">
                  |
                </span>
              </li>
              <li>
                <a href="{$API->SEO->make_link('donate')}">{$API->LANG->_('Donate')}</a>
              </li>
            </ul>
          </div>
        </div>
        <hr>
      </footer>
  </div>
</div>
</div>
</body>
</html>