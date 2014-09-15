{include file='doctype.tpl'}
<body>
{$body}
<br/><br/>
<small>To unsubscribe from these emails please <a href="{$API->SEO->make_link('unsubscribe','email',$email)}">visit this link</a><br/>
<a href="{$API->SEO->make_link('notification-settings')}">Configure notifications</a></small>
</body>
</html>