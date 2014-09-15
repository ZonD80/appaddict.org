{include file='doctype.tpl'}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <title>AA admicp simple - PUSH SAFARI</title>
    <script>
        function add_more() {
            var el = $('.aname:last').clone();
            el.val('');
            $('.aname:last').after(el);
        }
    </script>
</head>
<body>
    <h1>Sending push message to SAFARI | <a href="javascript:history.go(-1);">Go back</a> </h1>
    <form method="POST">
        Title:<input name="title" size="100" required/><br/>
        Type for website (moderator,tracks,news:<input name="notification_type" size="100"/><br/>
        Body:<input name="body" size="100" required/><br/>
        URL:<input name="url" size="100" required value="{$CONFIG['defaultbaseurl']}"/><br/>
        To Names or EMAILS (empty - ALL):
        <div class="aname"><input type="text" size="40" maxlength="40" name="anames[]"/></div><a href="javascript://" onclick="add_more();">Add more</a>
        <hr/>
        <input type="submit"/>
    </form>
</body>
</html>