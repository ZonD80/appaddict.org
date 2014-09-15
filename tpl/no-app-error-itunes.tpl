{include file='doctype.tpl'}<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>AppAddict - {$API->LANG->_('Error')}</title>
    <style type="text/css">

    body {
        text-align: center;
    }

    img {
        border-width: 0px; /* need this for firefox */
    }

    div.loadingbox {
        border: 3px solid #ccc;
        width: 390px;
        margin: auto;
        margin-top: 50px;
        padding: 0px;
        text-align: left;
    }

    table.info {
        width: 270px;
    }

    div.cover {
        width: 100px;
        text-align: right;
    }

    div.loadingbox div.cover img {
        margin-top: 40px;
    }

    div.loadingbox p {
        color: #999;
        font: 14px 'Lucida Grande', LucidaGrande, Lucida, Helvetica, Arial, sans-serif;
        margin: 0;
        padding: 1px 10px 0px 10px;
    }

    div.loadingbox p.title {
        color: #333;
        font-size: 26px;
        font-weight: bold;
        padding: 0px 20px 3px 20px;

    }

    div.loadingbox p.subtitle {
        color: #666;
        font-size: 15px;
        padding: 0px 20px;
    }

    div.loadingbox p.heading {
        color: #666;
        font-size: 15px;
        padding-top: 15px;
        padding-bottom: 5px;
        font-weight: bold;
    }

    div.loadingbox p.footer {
        color: #666;
        font-size: 12px;
        text-align: center;
        padding: 20px 20px 0px 20px;
    }

    div.roundtop { 
        background: url('/images/htmlcorners/tr.jpg') no-repeat top right;
        position: relative;
        right: -3px;
        top: -3px;
    }

    div.roundbot { 
        background: url('/images/htmlcorners/br.jpg') no-repeat top right;
        position: relative;
        right: -3px;
        bottom: -3px;
    }

    img.corner {
        width: 13px;
        height: 13px;
        border: none;
        display: block !important;
        position: relative;
        left: -6px;
    }

    div.clear {
        clear: both;
    }

    p.dark {
        color: #333;
    }

    a {
        text-decoration: underline;
        border-width: 0px;
    }

    </style>
  </head><style type="text/css"></style>

  <body>

   <div class="loadingbox">
     <div class="roundtop"><img width="13" height="13" alt="" class="corner" style="display: none" src="./message_files/tl.jpg"></div>
      <p class="title">{$API->LANG->_('Oops')}!</p>
      <p class="subtitle">{$API->LANG->_('An error occured. Here are some details')}:</p>

        <center><p class="heading">{$API->LANG->_('No Content with ID')} {$trackid}</p></center><br>

       <div class="clear"></div>

         
         <div id="itunes-client-required" >
          <center><br>
           <p style="color: red;">
            {$API->LANG->_('We are unable to find such Content in our database and on iTunes or Mac App Store')}.<br/><br/>
            {$API->LANG->_('SEARCH_ARCHIVE_NOTICE',$API->SEO->make_link('archive','trackid',$trackid))}.
           </p></center>
         </div>
         

         <p class="footer"><a href="javascript:history.go(-1);">{$API->LANG->_('Go back')}</a> | <a href="/tos.php">{$API->LANG->_('Terms Of Service')}</a>.</p>
      <div class="roundbot"><img width="13" height="13" alt="" class="corner" style="display: none" src="./message_files/bl.jpg"></div>
   </div>
</body></html>