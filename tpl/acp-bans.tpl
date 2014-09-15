{include file='doctype.tpl'}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="./css/demo_table.css"/>

    <title>AA admicp BANS</title>


    {literal}
        <script>
            $(document).ready(function() {
                $('#banstable').dataTable();
            });
        </script>

    {/literal}
</head>
<body>
    <h1>Bans admicp | <a href="javascript:history.go(-1);">Go back</a> | <a href="{$API->SEO->make_link('acp')}">Main page</a></h1>

    <h2><a href="javascript://" onclick="$('#banadder').slideDown();">Apply Inquisition and expulsion of demons</a></h2>
    <div id="banadder" style="display:none;">
        <form method="post" onsubmit="return confirm('Are you sure?');">
            <input type="hidden" name="mode" value="ban"/>
            Username or email: <input name="aname" required="required"/> or id <input name="id"/><br/>
            Ban reason (required): <input name="reason" required="required"/><br/>
            <input type="submit" value="Apply holy fire and ban hammer"/>
        </form>
    </div>
    <table id="banstable" border="1">
        <thead>
        <th>Nickname</th><th>Email</th><th>Reason</th><th>Actions</th>
    </thead>
    <tbody>

        {foreach from=$bans item=b}
            <tr id="account-{$b.id}">
                <td>{$b.name}</td>
                <td>{$b.email}</td>
                <td>{$b.ban_reason}</td>
            <td>
                <a href="acp.php?action=bans&mode=unban&id={$b.id}">Unban</a>
            </td>

        </tr>
        {/foreach}
        </tbody>
    </table>
</body>
</html>