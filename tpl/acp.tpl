{include file='doctype.tpl'}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./itunes_files/web-storefront-base.css"/> 
    <title>AA admicp simple</title>
    <script>
        {literal}
            function reset_reports(link) {
                var id = link.match(/(trackid|id)=[0-9]+/);
                id = id[0].replace('trackid=', '').replace('id=', '');
                $('#reportarea-' + id).text('');
                $('#loading').slideDown('slow');
                $.get(link, function(data) {
                    $('#loading').text(data);
                });
                $('#loading').slideUp('slow');
                return false;
            }
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

            function ban_him(email) {
                var reason = prompt("Please enter ban reason:");
                if (!reason) {
                    alert('Can not ban - no reason provided');
                    return false
                }
                $.post('acp.php', {'action': 'bans', 'mode': 'ban', 'aname': email, 'reason': reason}, function(data) {
                    alert(data);
                });
                return false;

            }

            function save_link(id) {
                var state = $('.state-' + id).val();
                if (state == 'rejected') {
                    var reason = prompt('Enter reason of rejecting');
                    if (!reason) {
                        alert('Can not reject - no reason');
                        return false;
                    }
                }
                $.post('acp.php', {'action': 'edit', 'id': id, 'link': $('.linkarea-' + id).val(),'cracker': $('.cracker-' + id).val(),'version': $('.version-' + id).val(), 'state': state, 'reason': reason,'protected':$('.protection-' + id).val()}, function(data) {
                    if (data.indexOf('Error') === -1) {
                        $('.link-' + id).fadeOut();
                        $('.placeholder-' + id).fadeIn();
                    } else {
                        alert(data);
                    }
                })
            }

        {/literal}
    </script>
    <style>
        img {
            max-width:50px; max-height:50px;
        }
        td {
            font-size: 10px;
        }
        .reports {
            border: 2px dashed red;  
        }

        textarea {
            height:100px;
        }
        #loading {
            background-color: red; color:white; display:none; 
            position:fixed;
            top:0px;
            width:100%;
            align:center;
        }
        .actions {
            width:230px;
        }
        #apptable {
            width:100%;
        }
        .red {
            color:red;
        }
        .green {
            color:green;
        }
        .orage {
            color:orange;
        }
    </style>
</head>
<body>
    {*<h1 style="background-color: red; color:white;">Accept apps successively as they appear in Pending section, do not change order of links. Violators will be banned.</h1>
    *}<h1 id="loading">Loading...</h1>
    <h3><a href="acp.php">AAA</a> with <a href="?action=reparse_itunes_errors" onclick="return confirm('are you sure? it may take much time');">Mass reparse iTunes errors</a>, <a href="?action=loginas">Login as someone</a>, <a href="?action=filehostings">File hostings</a>, <a href="?action=bans">Bans</a>, <a href="?action=logs">Logs</a>, <a href="?action=resetpassword">Reset pwds</a>, <a href="?action=privs">Privs</a>, <a href="?action=crackers">Crackers</a>,<br/><a href="?action=news">News</a>,Push <a href="?action=push">iOS</a>|<a href="?action=push-safari">Safari</a>, <a href="?action=lang">Translation</a>, <a href="?action=clearcache">Clear caches</a>, <a href="?action=bitcoin">Bitcoin</a>, <a href="/">Home</a>

        <br/><br/>{if $reports}<span class="reports"><a href="acp.php?state=reported">{$reports} reports!</a></span>{/if} {if $itunes_parse_errors}<span class="reports">{$itunes_parse_errors} iTunes Parse Errors!</span>{/if}{if $cracker_proposals}<span class="reports"><a href="acp.php?action=crackers&section=proposed">{$cracker_proposals} Cracker proposals!</a></span>{/if}</h3>

    <br/>
    <form>
        Search: <input name="q[name]" placeholder="App/Book name" value="{$s.name}"/><input name="q[bid]" placeholder="Bundle_id" value="{$s.bid}"/><input name="q[uploader]" placeholder="Uploader" value="{$s.uploader}"/><input name="q[cracker]" placeholder="Cracker" value="{$s.cracker}"/><input name="q[link]" placeholder="Link" value="{$s.link}"/><input name="q[editor]" placeholder="Editor" value="{$s.editor}"/>
        in:  <select name="state">
            <option value="accepted"{if $state=='accepted'} selected{/if}>Accepted</option>
            <option value="pending"{if $state=='pending'} selected{/if}>Pending</option>
            <option value="rejected"{if $state=='rejected'} selected{/if}>Rejected</option>
            <option value="archived"{if $state=='archived'} selected{/if}>Archived</option>
            <option value="reported"{if $state=='reported'} selected{/if}>Reported</option>
            <option value="dead"{if $state=='dead'} selected{/if}>Dead</option>
        </select>

        <input type="submit" value="Search"/>
        <a href="acp.php"><input type="button" value="Reset"/></a>
    </form>
    {$pagercode}
    <br/>
    <table id="apptable" border="1">
        <thead>
        <th>Added at</th><th>Image</th><th>Genre</th><th>Version</th><th>Developer</th><th>Track ID</th><th>Name</th><th>Uploader / Cracker</th><th>State reason</th><th>State</th>
    </thead>
    <tbody>
        {if $links}
            {foreach from=$links item=l}
                <tr class="placeholder-{$l.id}" style="display:none;"><td colspan="11">Saved. <a href="javascript://" onclick="$('.placeholder-{$l.id}').fadeOut();
                        $('.link-{$l.id}').fadeIn();">Open again</a></td></tr>
                <tr class="link-{$l.id}">
                    <td>{$l.added|date_format:"%d.%m.%Y %H:%M"}</td>
                    <td><img src="{$l.image}" width="60px;" title="{$l.name}"/></td>
                    <td>{$l.gname}</td>
                    <td>iTunes: {$l.itversion}, Link: <input class="version-{$l.id}" value="{$l.version}"/>{if $l.version!=$l.itversion}<br/><span class="red">VERSION MISMATCH</span>{/if}</td>
                    <td>{$l.aname}</td>
                    <td><a target="_blank" href="{$API->SEO->make_link('view','trackid',$l.trackid)}">{$l.trackid}</a><br/><a target="_blank" href="https://itunes.apple.com/{$l.store}/app/id{$l.trackid}">iTunes</a></td>
                    <td>{$l.name}</td>
                    <td>{$l.uname} ({$l.uemail}) [<a href="javascript://" onclick="message_to({$l.uploader_id});">Send email/push to him</a>][<a href="javascript://" onclick="ban_him('{$l.uemail}');">Ban him</a>]) / <input class="cracker-{$l.id}" value="{$l.cracker}"/> [{if $l.cverified}<span class="green">VERIFIED</span>{else}Not verified{/if}]</td>
                    <td>{$l.state_reason}</td>
                    <td><select name="state" class="state-{$l.id}">
                            <option value="accepted"{if $l.state=='accepted'} selected{/if}>Accepted</option>
                            <option value="pending"{if $l.state=='pending'} selected{/if}>Pending</option>
                            <option value="rejected"{if $l.state=='rejected'} selected{/if}>Rejected</option>
                            <option value="archived"{if $l.state=='archived'} selected{/if}>Archived</option>
                            <option value="reported"{if $state=='reported'} selected{/if}>Reported</option>
                            <option value="dead"{if $state=='dead'} selected{/if}>Dead</option>
                        </select><br/>
                        set by: <span class="setby-{$l.id}">{if $l.ename}{$l.ename} ({$l.eemail})<br/>[<a href="javascript://" onclick="message_to({$l.eid});">Send email/push to him</a>][<a href="javascript://" onclick="ban_him('{$l.uemail}');">Ban him</a>]{else}System{/if}</span></td>
                </tr>
                <tr class="link-{$l.id}"><td colspan="11">Link (click select, dbclick open): <input type="text" size="100" class="linkarea-{$l.id}" value="{$l.link|htmlspecialchars}" onclick="this.select();" ondblclick="window.open(this.value);"/> Protected from report: <select class="protection-{$l.id}"><option value="1"{if $l.protected} selected{/if}>Yes</option><option value="0"{if !$l.protected} selected{/if}>No</option></select> [<a href="javascript://" onclick="save_link({$l.id});">SAVE</a>]</td></tr>
                    {/foreach}
                {/if}


    </tbody>
</table>
{$pagercode}
</body>
</html>