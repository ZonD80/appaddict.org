{include file='doctype.tpl'}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" type="text/css" href="./itunes_files/web-storefront-base.css"/> 
    <link rel="stylesheet" type="text/css" href="./css/jquery.dataTables.css"/>
    <link rel="stylesheet" type="text/css" href="./css/demo_table.css"/>
    <title>AA admicp simple - Language helper tools</title>
    {literal}
        <script type="text/javascript">
            $(document).ready(function() {
                $('#langtable').dataTable();
            });
            function ajaxdel(key, lang, el) {

                $(el).text('Deleting...');
                $.get("acp.php", {action: 'lang', mode: 'editor', do: 'delete', key: key, language: lang}, function(data) {
                    $(el).text('Deleted');
                }
                );
                return false;
            }

        </script>
    {/literal}
</head>
<body>
    <h1>Language helper tools | <a href="{$API->SEO->make_link('acp')}">ACP main page</a> | <a href="javascript:history.go(-1);">Go back</a> </h1>
    {if $API->CONFIG['static_language_dir']}<h2>WARNING! Static language folder enabled. Export as PHP and reupload all language files to "{$API->CONFIG['static_language_dir']}" folder after every change</h2>{/if}
    <table width="100%" border="1">
        <tr>
            <td><a href="{$API->SEO->make_link('acp','action','lang','mode','import')}">Import langfile(utf-8 w/o bom)</a></td>
            <td><a href="{$API->SEO->make_link('acp','action','lang','mode','export')}">Export langfile</a></td>
            <td><a href="{$API->SEO->make_link('acp','action','lang','mode','editor')}">Language strings editor</a></td>
            <td><a href="{$API->SEO->make_link('acp','action','lang','mode','clearcache')}">Clear language cache</a></td>
        </tr>
    </table>
    {if $mode=='export'}
        <h1>Language exporter</h1>
        <form method="post"><input type="hidden" name="mode" value="export"/>
            <table>
                <tr>
                    Select language for export
                    <td></td>
                </tr>
                <tr>
                    <td><select name="lang_export">
                            {foreach from=$langs item=l}
                                <option value="{$l.ltranslate}">{$l.ltranslate}</option>
                            {/foreach}

                        </select> <label style="cursor:pointer;"/><input type="checkbox" value="1" name="as_php"/>Export as PHP array</label></td>
                </tr>
                <tr>
                    <td><input type="submit"
                               value="Export and download"/></td>
                </tr>
            </table>
        </form>
    {elseif $mode=='import'}
        {if $result}
            <h1>Import status:</h1>
            OK:<hr/>
            {implode('<br/>',$result['words'])}
            <hr/>
            Errors:<br/>
            {implode('<br/>',$result['errors'])}
        {/if}
        <h1>Language importer</h1>
        <form
            method="post" enctype="multipart/form-data">
            <input type="hidden" name="mode" value="import"/>
            <table>
                <tr>
                    <td><input type="text" name="language" maxlength="2" required="required"
                               size="2"/>Language code to be imported to e.g. "en", "ua", "de"</td>
                    <td><input type="file" name="langfile" required="required"/>Language file</td>
                    <td><label><input type="checkbox" name="override" value="1"/>Override current data</label>            </td>
                    <td><input type="submit"
                               value="Continue"/></td>
                </tr>
            </table>
        </form>
    {elseif $mode=='editor'}
        <h1>Language editor</h1>
        <form 
            method="get">
            <input type="hidden" name="mode" value="editor"/>
            <input type="hidden" name="action" value="lang"/>
            <table>
                <tr>
                    <td> Search by key or value -             Key:<input type="text" name="searchkey" value="{$searchkey}"/>&nbsp;Value:<input type="text" name="searchvalue" value="{$searchvalue}"/></td>
                    <td><input type="submit"
                               value="Search"/></td>
                </tr>
            </table>
        </form>
        <p><a href="javascript://" onclick="$('#adder').slideDown();"><b>Add new word</b></a></p>
        <div id="adder" style="display: none;">
            <form
                method="post">
                <input type="hidden" name="mode" value="editor"/>
                <input type="hidden" name="do" value="saveadd"/>
                <table border="1">
                    <tr>
                        <td>Key<br/>
                            <input type="text" name="key"></td>
                        <td>Word<br/>
                            {foreach from=$langs item=l}
                                Translation for <b>{$l.ltranslate}</b><textarea name="word[{$l.ltranslate}]" rows="2" cols="100"></textarea><br/>
                            {/foreach}
                        </td>
                        <td><input type="submit"
                                   value="Continue"/></td>
                    </tr>
                </table>
            </form>
        </div>
        <form method="POST"><input type="hidden" name="do" value="gensave"/>
            {$pagercode}
            <table border="1" width="100%" id="langtable">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Language</th>
                        <th>Value</th>
                        <th>Delete</th>
                    </tr></thead>

                {foreach from=$data item=d}
                    <tr id="{$d.lkey}-{$d.ltranslate}"><td><input type="text" required="required" name="key[{$d.lkey|htmlspecialchars}][{$d.ltranslate|htmlspecialchars}]" value="{$d.lkey|htmlspecialchars}" maxlength="255"/></td><td>{$d.ltranslate}</td><td><textarea rows="2" cols="100" name="val[{$d.lkey|htmlspecialchars}][{$d.ltranslate|htmlspecialchars}]">{$d.lvalue|htmlspecialchars}</textarea></td><td><a onclick="return ajaxdel('{$d.lkey|htmlspecialchars}', '{$d.ltranslate|htmlspecialchars}', this);" href="javascript://">Delete</a></td></tr>
                        {/foreach}
            </table>
            {$pagercode}
            <input type="submit" value="Save changes"/>
        </form>

    {/if}
</body>
</html>