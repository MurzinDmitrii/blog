<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$page_title|escape} — Blogy.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{$base_url|escape}/assets/css/main.css">
</head>
<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a class="logo" href="{$base_url|escape}/">Blogy.</a>
        </div>
    </header>
    <main class="main">
        {block name=content}{/block}
    </main>
    <footer class="site-footer">
        <div class="site-footer__inner">Copyright © {$current_year|escape}. All Rights Reserved.</div>
    </footer>
</body>
</html>
