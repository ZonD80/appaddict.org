{include file='doctype.tpl'}
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <title>AA admicp simple - PUSH</title>
    <script>
        function add_udid() {
            var el = $('.udid:last').clone();
            el.val('');
            $('.udid:last').after(el);
        }
        function add_cp() {
            var el = $('.cp:last').clone();
            el.val('');
            $('.cp:last').after(el);
        }
    </script>
</head>
<body>
    <h1>Sending push message | <a href="javascript:history.go(-1);">Go back</a> </h1>
    <form method="POST">
        Message:<input name="message" size="100"/>
        Type (default 0): <input name="type" size="1" maxlength="1" value="0"/><hr/>
        To UDIDs (empty=all)
        <div class="udid"><input type="text" size="40" maxlength="40" name="udids[]"/></div><a href="javascript://" onclick="add_udid();">Add more</a>
        <hr/>
        Custom props (empty=none), <u>type</u> is reserved
        <div class="cp">Name: <input type="text" name="custom_prop_names[]"/> Value: <input type="text" name="custom_prop_values[]"/></div><a href="javascript://" onclick="add_cp();">Add more</a>
        <br/>
        <label style="cursor:pointer;"><input type="checkbox" name="content_available" value="1"/> Set content available flag</label>
        <br/>
        Notification type for website (moderator,tracks,news): <input name="notification_type" value=""/><hr/>
        <br/>
        <input type="submit"/>
    </form>
</body>
</html>