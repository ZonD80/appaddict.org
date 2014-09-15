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
            <title>AA ADMIN reparsing iTunes errors...</title>
                <script>


        function check_itunes(el_class, count, total) {
            if (count >= total) {
                alert('All errors checked');
                return false;
            }
            var element = $('.' + el_class + ':eq(' + count + ')');
            $.get(element.data('link'), function(response) {
                element.html(response);
                
                check_itunes(el_class, count + 1, total);
            });
        }

        $(document).ready(function() {
            check_itunes('aa-check-status', 0, $('.aa-check-status').length);
        }
        );
    </script>
    </head>
    <body>
        <h1>Reparsing iTunes errors...</h1>
        <h2>Please wait for popup about completed check!</h2>
        <h3>Amount of apps need to be reparsed: {count((array)$progress_image)}.</h3><hr/>
        <center><table border="1">
            <tr><th>{$API->LANG->_('App')} Trackid</th><th>{$API->LANG->_('Status')}</th>
                {if $progress_image}
        {foreach from=$progress_image item=i}
            <tr><td>Parse status of {$i.trackid}</td><td><div class="aa-check-status" data-link="acp.php?action=reparse_itunes_errors&trackid={$i.trackid}">{$API->LANG->_('Loading')}...</div></td></tr>
        {/foreach}
                {/if}
        </table></center>
        <h1><a href="acp.php" onclick="return confirm('{$API->LANG->_('Did you seen popup?')}');">{$API->LANG->_('Main page')} of admincp ({$API->LANG->_('only if page has been loaded completely')})</a>
    </body>
</html>