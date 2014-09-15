{include file='header.tpl'}
<h1>Bans admicp | <a href="javascript:history.go(-1);">Go back</a> | <a href="{$API->SEO->make_link('acp')}">Main page</a></h1>
<h1>Login as (not affected in http plain auth/acp):</h1>
    <form method="post" onsubmit="return confirm('Are you sure?');">
            Username or email: <input name="aname" required="required"/><br/>
            <input type="submit" value="Login as this user"/>
        </form>
    {include file='footer.tpl'}
    