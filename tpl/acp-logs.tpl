{include file='doctype.tpl'}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <title>AA admicp logs viewer</title>
    <link rel="stylesheet" type="text/css" href="./itunes_files/web-storefront-base.css"/> 
    {* <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="./css/demo_table.css"/>
    <title>AA admicp LOGS VIEVER</title>

    *}
    {literal}
        <script>
            function message_to(id) {
                var msg = prompt("Enter short push/email moderator message for uploader:");
                if (!msg) {
                    alert('No message provided. Can not send.');
                    return false;
                }
                $.post('acp.php', {'action': 'message', 'id': id, 'msg': msg}, function(data) {
                    alert(data);
                });
                return false;
            }
        </script>

    {/literal}

</head>
<body>
    <h1>Logs viewer | <a href="javascript:history.go(-1);">Go back</a> | <a href="{$API->SEO->make_link('acp')}">Main page</a></h1>

    <h2>Select section: [ <a href="?action=logs">All</a> ] {foreach from=$log_types item=lt} [ <a href="?action=logs&type={$lt['type']}">{$lt['type']}</a> ] {/foreach}</h2>
            Search: <form method="get"><select name="type">
                    <option value="">Type: All</option>
                    {foreach from=$log_types item=lt}<option value="{$lt['type']}">{$lt['type']}</option> {/foreach}
        </select>
                <input type="hidden" name="action" value="logs"><input type="text" name="object_id" value="{if $object_id}{$object_id}{/if}"/><input type="submit" value="search"/></form>
    {$pagercode}
    <small><table id="logstable" border="1">
            <thead>
            <th>Date</th><th>Username/email</th><th>Action</th><th>Type</th><th>Related object id</th><th>Data before</th><th>Data after</th><th>IP</th><th>User Agent</th><th>URL</th><th>POST</th><th>GET</th>
            </thead>
            <tbody>

                {foreach from=$logs item=l}
                    <tr>
                        <td>{*{$l.added} *}{$l.added|date_format:"%d.%m.%Y %H:%M"} GMT</td>
                        <td>{$l.name}<br/>{$l.email} [&nbsp;<a href="javascript://" onclick="message_to({$l.account_id});">Email/Push</a>&nbsp;]</td>
                        <td width="300px;">{$l.action}</td>
                        <td>{$l.type}</td>
                        <td>{$l.object_id}</td>
                        <td><a href="javascript://" onclick="$(this).next().slideDown();">Show</a><div style="display:none;">{$l.data_before|htmlspecialchars}</div></td>
                        <td><a href="javascript://" onclick="$(this).next().slideDown();">Show</a><div style="display:none;">{$l.data_after|htmlspecialchars}</div></td>
                        <td>{$l.ip}</td>
                        <td><a href="javascript://" onclick="$(this).next().slideDown();">Show</a><div style="display:none;">{$l.user_agent|htmlspecialchars}</div></td>
                        <td>{$l.url|htmlspecialchars}</td>
                        <td><a href="javascript://" onclick="$(this).next().slideDown();">Show</a><div style="display:none;">{$l.post|htmlspecialchars}</div></td>
                        <td><a href="javascript://" onclick="$(this).next().slideDown();">Show</a><div style="display:none;">{$l.get|htmlspecialchars}</div></td>

                    </tr>
                {/foreach}
            </tbody>
        </table></small>
        {$pagercode}
</body>
</html>