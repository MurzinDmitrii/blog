{extends file='layouts/base.tpl'}
{block name=content}
<div class="page-home">
{if $blocks|@count == 0}
    <p class="empty">Пока нет статей в категориях.</p>
{else}
    {foreach from=$blocks item=block}
        <section class="home-category">
            <div class="home-category__bar">
                <h2 class="home-category__name">{$block.category.name|escape}</h2>
                <a class="home-category__all" href="{$base_url|escape}/category/{$block.category.id|escape}">Все статьи</a>
            </div>
            {if $block.articles|@count == 0}
                <p class="empty">В этой категории пока нет статей.</p>
            {else}
                <ul class="home-post-grid">
                    {foreach from=$block.articles item=a}
                        <li class="home-post">
                            <article class="home-post__inner">
                                <a class="home-post__media" href="{$base_url|escape}/article/{$a.id|escape}" tabindex="-1" aria-hidden="true">
                                    {if $a.image_path|safe_asset}
                                        <img class="home-post__img" src="{$base_url|escape}/{$a.image_path|safe_asset|escape}" alt="">
                                    {else}
                                        <div class="home-post__img home-post__img--empty" role="img" aria-label=""></div>
                                    {/if}
                                </a>
                                <h3 class="home-post__title">
                                    <a href="{$base_url|escape}/article/{$a.id|escape}">{$a.title|escape}</a>
                                </h3>
                                <time class="home-post__date" datetime="{$a.published_at|escape}">{$a.date_display|escape}</time>
                                {if $a.description}
                                    <p class="home-post__excerpt">{$a.description|escape}</p>
                                {/if}
                                <a class="home-post__more" href="{$base_url|escape}/article/{$a.id|escape}">Читать далее</a>
                            </article>
                        </li>
                    {/foreach}
                </ul>
            {/if}
        </section>
    {/foreach}
{/if}
</div>
{/block}
