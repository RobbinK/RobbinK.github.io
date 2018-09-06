{$settings['siteurl']}/
{$settings['siteurl']}/category/recent
{$settings['siteurl']}/category/popular
{$settings['siteurl']}/awards
{foreach $res as $r}
{$settings['siteurl']}/g/{$r.url_key}
{/foreach}
{foreach $tags as $r}
{$settings['siteurl']}/tag/{$r.name|lower}
{/foreach}
{foreach $users as $u}
{$settings['siteurl']}/profile/{$u.id}
{/foreach}
{$settings['siteurl']}/menu
{$settings['siteurl']}/login
{$settings['siteurl']}/signup
{$settings['siteurl']}/contact
{$settings['siteurl']}/privacy