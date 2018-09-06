<footer id="footer">
<div>&copy; {$settings['sitename']} {'Y'|date} | <a href="{$settings['siteurl']}/contact">Contact</a> &middot; <a href="{$settings['siteurl']}/privacy">Privacy</a>
</div></footer></div>
{literal}
<script>
var openMenu=function(){
var e=document.getElementById("menu");
e.classList.toggle("default")
var d=document.getElementById("showMenuBtn");
d.classList.toggle("xbutton")
}
if (top.location!= self.location) {
top.location = self.location.href;
}
</script>
{/literal}
{$settings['analytics']}
</body></html>