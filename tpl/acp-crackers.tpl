{include file='doctype.tpl'}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="./css/demo_table.css"/>

    <script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
    <title>AA admicp CRACKERS</title>


    {literal}
        <script>
            $(document).ready(function() {
                $('#crackerstable').dataTable();
                $('textarea').tinymce({
                    // Location of TinyMCE script
                    script_url: 'js/tiny_mce/tiny_mce.js',
                    // General options
                    theme: "advanced",
                    plugins: "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
                    // Theme options
                    theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                    theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                    theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                    theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
                    theme_advanced_toolbar_location: "top",
                    theme_advanced_toolbar_align: "left",
                    theme_advanced_statusbar_location: "bottom",
                    theme_advanced_resizing: true
                });
            });

            function check_cracker() {
                var cracker = $('#cracker').val();
                $('#crackerdata').text('loading...');
                $.post('acp.php', {'section': '{/literal}{$section}{literal}', 'action': 'crackers', 'mode': 'check', 'name': cracker}, function(data) {
                    $('#crackerdata').text(data);
                });
            }

            function revoke_status(url) {
                var reason = prompt('Enter reason of status revoking');
                if (!reason) {
                    alert('No reason - can not continue');
                    return false;
                }
                else {
                    window.location = url + '&reason=' + reason;
                }
                return true;
            }
            
            function delete_cracker(url) {
                var reason = prompt('Enter reason of deleting');
                if (!reason) {
                    alert('No reason - can not continue');
                    return false;
                }
                else {
                    window.location = url + '&reason=' + reason;
                }
                return true;
            }
        </script>

    {/literal}
</head>
<body>
    <h1>Crackers admincp {if $section=='verified'}[Verified] [<a href="?action=crackers&section=proposed">Proposed</a>]{else}[<a href="?action=crackers">Verified</a>] [Proposed]{/if} | <a href="javascript:history.go(-1);">Go back</a> | <a href="{$API->SEO->make_link('acp')}">Main page</a></h1>

    <h2><a href="javascript://" onclick="$('#crackeradder').slideDown();">add/edit cracker</a></h2>
    <div id="crackeradder" style="{if !$cracker.account_id}display:none;{/if}">
        <strong>PLEASE UPLOAD PICTURES TO SERVER VIA FTP</strong>
        <form method="post">
            <input type="hidden" name="section" value="{$section}"/>
            <input type="hidden" name="id" value="{$cracker.account_id}"/>
            Hacker username: <input name="name" id="cracker" value="{$cracker.name}"/> <span id="crackerdata" style="color:red;"></span> <a href="javascript://" onclick="check_cracker();">Check number of uploads</a><br/>
            Avatar URL 168x130: <input name="avatar" value="{$cracker.avatar}"/><br/>
            Background URL 575x657 with transparent backgroud and fading to transparency on left side: <input name="background" value="{$cracker.background}"/><br/>
            Slogan: <input name="slogan" value="{$cracker.slogan}"/><br/>
            Story: <textarea name="story">{$cracker.story|htmlspecialchars}</textarea><br/>
            <input type="submit" value="add/edit"/>
        </form>
    </div>
    <table id="crackerstable" border="1">
        <thead>
        <th>Nickname</th><th>Avatar URL</th><th>Background URL</th><th>Slogan</th><th>Story exisits?</th><th>Apps Cracked</th><th>Actions</th>
    </thead>
    <tbody>

        {foreach from=$crackers item=c}
            <tr>
                <td>{$c.name}</td>
                <td>{$c.avatar}</td>
                <td>{$c.background}</td>
                <td>{$c.slogan}</td>
                <td>{if $c.story}YES{else}NO{/if}</td>
                <td>{$c.numapps}</td>
                <td>{if $section=='proposed'}<a onclick="return confirm('Plase edit and reupload images to local site after move. okay?\n notification will be sent to cracker');" href="{$API->SEO->make_link('acp','action','crackers','mode','move','from',$section,'to','verified','id',$c.account_id)}">Move to verified</a><br/>
                    {else}<a href="javascript://" onclick="revoke_status('{$API->SEO->make_link('acp','action','crackers','mode','move','from',$section,'to','proposed','id',$c.account_id)}');">Revoke verification</a><br/>
                        {/if}<a href="{$API->SEO->make_link('acp','action','crackers','section',$section,'id',$c.account_id)}">Edit</a><br/><a href="javascript://" onclick="delete_cracker('{$API->SEO->make_link('acp','action','crackers','section',$section,'mode','delete','id',$c.account_id)}');">Delete from {$section}</a></td>
                        </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </body>
            </html>