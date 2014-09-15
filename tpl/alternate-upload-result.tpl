{include file='doctype.tpl'}
<head>
    <style>
        img {
            /*border: 1px dashed green;*/
            width: 500px;
            height: 20px;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <script type="text/javascript" src="js/jquery.min.js"></script>
    <title>{$API->LANG->_('Alternate uploading panel')}</title>
    <script>


        function upload_app(el_class, count, total) {
            if (count >= total) {
                alert('All apps uploaded');
                return false;
            }
            var element = $('.' + el_class + ':eq(' + count + ')');
            $.get(element.data('link'), function(response) {
                if (response.status=='ok') {
                element.html("<span style=\"color:green;\">SUCCESS: "+response.message+"</span>");
                }
                else if (response.status=='error') {
                    element.html("<span style=\"color:red;\">ERROR: "+response.message+"</span>");
                }
                if (response.link) {
                    $.get(response.link, function(mod_response) {
                        element.html(mod_response);
                    });
                }
                upload_app(el_class, count + 1, total);
            });
        }

        $(document).ready(function() {
            upload_app('aa-upload-status', 0, $('.aa-upload-status').length);
        }
        );
    </script>
</head>
<body>
    <h1>{$API->LANG->_('We are uploading your Apps')}...</h1>
    <h2>{$API->LANG->_('Please wait for popup about completed upload')}!</h2>
    {if $API->account['upload_auto_moderate']}<h2>{$API->LANG->_('Your alternate upload will be auto moderated')}</h2>{/if}
    <h3>{$API->LANG->_('Amount of apps you uploaded')}: {count($progress_image)}. {$API->LANG->_('You will receive emails if some of apps will be denied. Below you can see status of uploading')}:</h3><hr/>
<center><table border="1">
        <tr><th>{$API->LANG->_('App')} #</th><th>{$API->LANG->_('Status')}</th>
                {foreach from=$progress_image key=k item=i}
            <tr><td>{$API->LANG->_('Upload status of')} #{($k+1)}</td><td><div class="aa-upload-status" data-link="{$i}">{$API->LANG->_('Loading')}...</div></td></tr>
        {/foreach}
    </table></center>
<h1><a href="/" onclick="return confirm('{$API->LANG->_('Did you seen popup?')}');">{$API->LANG->_('Main page')} ({$API->LANG->_('only if page has been loaded completely')})</a>
</body>
</html>