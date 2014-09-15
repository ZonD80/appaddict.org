{include file='doctype.tpl'}
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <script type="text/javascript" src="js/jquery.min.js"></script>
            <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
            <link rel="stylesheet" type="text/css" href="./css/jquery.dataTables.css"/>
            <link rel="stylesheet" type="text/css" href="./css/demo_table.css"/>
            <title>AA admicp NEWS</title>
            <style>
            #newseditor {
                border: 1px solid red;
                padding-left: 10px;
            }
            </style>
            
        <script type="text/javascript" src="js/tiny_mce/jquery.tinymce.js"></script>
            
                {literal}
            <script>
    $(document).ready(function(){
    $('#newstable').dataTable();

		$('textarea').tinymce({
			// Location of TinyMCE script
			script_url : 'js/tiny_mce/tiny_mce.js',
                            
			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true
		});
                    });
    </script>
            
    {/literal}
    </head>
    <body>
        <h1>News admincp | <a href="javascript:history.go(-1);">Go back</a> | <a href="/">Main page</a></h1>
        <div id="newseditor">
        <form method="post">
        <h2>add/edit news</h2>
        <input type="hidden" name="id" value="{$news.id}"/>
        Title: <input name="title" value="{$news.title}" size="100"/><br/>
        Content: <textarea name="text">{$news.text|htmlentities}</textarea><br/>
        <input type="submit" value="add/edit"/>
        </form></div>
        <table id="newstable" border="1">
            <thead>
                <th>Added at</th><th>Title</th><th>Text</th><th>Actions</th>
            </thead>
            <tbody>

                {foreach from=$newsdata item=n}
                <tr>
                    <td>{$n.added}<br/>{$n.added|date_format:"%d.%m.%Y %H:%M"} GMT</td>
                    <td>{$n.title}</td>
                    <td>{$n.text}</td>
                    <td><a href="{$API->SEO->make_link('acp','action','news','id',$n.id)}">Edit</a><br/><br/><a onclick="return confirm('are you sure?');" href="{$API->SEO->make_link('acp','action','delete-news','id',$n.id)}">Delete</a></td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </body>
</html>