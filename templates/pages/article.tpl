{extends file='layouts/base.tpl'}
{block name=content}
<article class="article">
    <header class="article__header">
        <h1 class="article__title">{$article.title|escape}</h1>
        <div class="article__meta">
            <time datetime="{$article.published_at|escape}">{$article.published_at|escape}</time>
            <span>Просмотры: {$article.view_count|escape}</span>
        </div>
        {if $categories|@count > 0}
            <div class="article__cats">
                {foreach from=$categories item=c}
                    <a class="tag" href="{$base_url|escape}/category/{$c.id|escape}">{$c.name|escape}</a>
                {/foreach}
            </div>
        {/if}
    </header>
    {if $article.image_path|safe_asset}
        <figure class="article__figure">
            <img class="article__img" src="{$base_url|escape}/{$article.image_path|safe_asset|escape}" alt="">
        </figure>
    {/if}
    {if $article.description}
        <p class="article__lead">{$article.description|escape}</p>
    {/if}
    <div class="article__body">{$article.body_formatted nofilter}</div>
</article>

{if $similar|@count > 0}
    <section class="similar">
        <h2 class="similar__title">Похожие статьи</h2>
        <ul class="home-post-grid">
            {foreach from=$similar item=s}
                <li class="home-post">
                    <article class="home-post__inner">
                        <a class="home-post__media" href="{$base_url|escape}/article/{$s.id|escape}" tabindex="-1" aria-hidden="true">
                            {if $s.image_path|safe_asset}
                                <img class="home-post__img" src="{$base_url|escape}/{$s.image_path|safe_asset|escape}" alt="">
                            {else}
                                <div class="home-post__img home-post__img--empty" role="img" aria-label=""></div>
                            {/if}
                        </a>
                        <h3 class="home-post__title">
                            <a href="{$base_url|escape}/article/{$s.id|escape}">{$s.title|escape}</a>
                        </h3>
                    </article>
                </li>
            {/foreach}
        </ul>
    </section>
{/if}
{/block}
