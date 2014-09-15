{include file='doctype.tpl'}
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <script type="text/javascript" src="js/jquery.min.js"></script>
            <title>AA admicp password resetter</title>
           
            
            
                {literal}
            <script>
    </script>
            
    {/literal}
    </head>
    <body>
        <h1>Password resetter admincp | <a href="javascript:history.go(-1);">Go back</a> | <a href="/">Main page</a></h1>
        <div>
        <form method="post" onsubmit="return confirm('Are you sure?');">
        <h2>Enter nickname or email to reset passsword</h2>
        Nickname or email: <input name="aname" size="100"/><br/>
        <input type="submit" value="reset password"/>
        </form></div><br/>
        <h1>{$reset_password}</h1>
    </body>
</html>