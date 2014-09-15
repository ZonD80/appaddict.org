{include file='doctype.tpl'}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="./css/demo_table.css"/>

    <title>AA admicp PRIVILEGES</title>


    {literal}
        <script>
            $(document).ready(function() {
                $('#privstable').dataTable();
            });

            function add_privelege(elm) {
                var div = $('.priv:last').clone();
                div.children().val('');
                $(elm).before(div);
                return false;
            }

            function delete_all_privs(id) {
                var agree = confirm('Are you sure to revoke all priveleges from user?');

                if (!agree) {
                    alert('okay');
                    return false;
                } else {
                    window.location = 'acp.php?action=privs&mode=delete&id=' + id;
                }
            }

            function save_privs(id) {
                var request = [];
                $('#account-' + id).find('input.pname').each(
                        function() {
                        if (!$(this).val()) { $(this).parent().slideUp(); } else
                            request.push({name: $(this).val(), value: $(this).next().val()});
                        }
                );
                if (!request.length) return false;
                $.post('acp.php', {action: 'privs', mode: 'save', id: id, data: JSON.stringify(request)}, function(data) {
                    alert(data);
                });
            }
        </script>

    {/literal}
</head>
<body>
    <h1>Privileges admicp | <a href="javascript:history.go(-1);">Go back</a> | <a href="{$API->SEO->make_link('acp')}">Main page</a></h1>

    <h2><a href="javascript://" onclick="$('#privadder').slideDown();">add privilege</a> | <a href="javascript://" onclick="$('#privlist').slideDown();">show privileges list</a></h2>
    <div id="privadder" style="display:none;">
        <form method="post">
            <input type="hidden" name="mode" value="add"/>
            Username: <input name="aname"/><br/>
            Privilege name: <input name="name"/><br/>
            Privilege value: <input name="value" maxlength="1" size="1"/> integer, 1=yes,0=no<br/>
            <input type="submit" value="add/edit"/>
        </form>
    </div>
    <div id="privlist" style="display: none;"><pre>access_acp - access control panel
upload_auto_moderate - allow auto moderation of non-conflicted apps (e.g. mass uploads going directly to live)
manage_privs - manage privilege sets
manage_langs - use language helper tools
view_bans - can view bans in admicp
can_ban - can ban people
can_unban - can unban people
can_loginas - can login as someone
manage_passwords - ability to reset user passwords
manage_filehostings - ability to add/change banned and required file hostings
view_logs - view logs
protected_account - this user can not be banned or used for password reset
reparse_itunes_errors - use itunes errors mass reparser
manage_crackers - manage officially registered crackers
clear_cache - can clear site cache
manage_news - manage news
send_pushes - can send push messages from site
bitcoin - ability to manage bitcoin payment GW
is_moderator - general ability to move/edit apps and delete apps, reset reports, send moderator message,  (MODERATOR)
show_debug - show debug information or not?
=========
MODERATOR SET: access_acp, is_moderator, reparse_itunes_errors, can_ban
ADMIN SET: ALL
UPLOADER SET: NONE

</pre></div>
    <table id="privstable" border="1">
        <thead>
        <th>Nickname</th><th>Email</th><th>Registered privileges</th><th>Actions</th>
    </thead>
    <tbody>

        {foreach from=$privs item=p key=k}
            <tr id="account-{$k}">
                <td>{$p.name}</td>
                <td>{$p.email}</td>
                <td>{foreach from=$p.privs item=pd}
                    <div class="priv">Name:<input type="text" class="pname" value="{$pd.name}"/> Value:<input type="text" class="pvalue" value="{$pd.value}"  maxlength="1" size="1"/></div>
                    {/foreach}
                <a href="javascript://" onclick="add_privelege(this);">Add privilege</a> (empty will be deleted)</td>
            <td>
                <a href="javascript://" onclick="save_privs({$k});">Save privileges</a>
                <br/><br/>
                <a href="javascript://" onclick="delete_all_privs({$k});">Delete all privileges</a>
            </td>

        </tr>
        {/foreach}
        </tbody>
    </table>
</body>
</html>