{extends file='layouts/base.tpl'}
{block name=content}
<div class="page-head">
    <h1 class="page-head__title">{$category.name|escape}</h1>
    {if $category.description}
        <p class="page-head__lead">{$category.description|escape}</p>
    {/if}
</div>

<div class="toolbar">
    <span class="toolbar__label">Сортировка:</span>
    <a class="toolbar__link{if $sort == 'date'} toolbar__link--active{/if}" href="{$base_url|escape}/category/{$category.id|escape}?sort=date&amp;page=1&amp;per_page={$per_page|escape}">по дате</a>
    <a class="toolbar__link{if $sort == 'views'} toolbar__link--active{/if}" href="{$base_url|escape}/category/{$category.id|escape}?sort=views&amp;page=1&amp;per_page={$per_page|escape}">по просмотрам</a>
</div>

{if $articles|@count == 0}
    <p class="empty">В этой категории пока нет статей.</p>
{else}
    <ul class="article-list">
        {foreach from=$articles item=a}
            <li class="article-list__item">
                <a class="article-list__link" href="{$base_url|escape}/article/{$a.id|escape}">
                    {if $a.image_path|safe_asset}
                        <img class="article-list__img" src="{$base_url|escape}/{$a.image_path|safe_asset|escape}" alt="">
                    {/if}
                    <div class="article-list__body">
                        <h2 class="article-list__title">{$a.title|escape}</h2>
                        {if $a.description}
                            <p class="article-list__desc">{$a.description|escape}</p>
                        {/if}
                        <div class="article-list__meta">
                            <span>{$a.published_at|escape}</span>
                            <span>Просмотры: {$a.view_count|escape}</span>
                        </div>
                    </div>
                </a>
            </li>
        {/foreach}
    </ul>

    <div class="pagination-bar">
        <div class="pagination-bar__row">
            {if $total_pages > 1}
                <nav class="pagination" aria-label="Страницы">
                    {if $page > 1}
                        <a class="pagination__link" href="{$base_url|escape}/category/{$category.id|escape}?sort={$sort|escape}&amp;page={$page-1|escape}&amp;per_page={$per_page|escape}">← Назад</a>
                    {/if}
                    <span class="pagination__info">Страница {$page|escape} из {$total_pages|escape}</span>
                    {if $page < $total_pages}
                        <a class="pagination__link" href="{$base_url|escape}/category/{$category.id|escape}?sort={$sort|escape}&amp;page={$page+1|escape}&amp;per_page={$per_page|escape}">Вперёд →</a>
                    {/if}
                </nav>
            {/if}
            <div class="pagination-bar__sizes" aria-label="Количество на странице">
                <span class="pagination-bar__label">На странице:</span>
                {foreach from=$per_page_options item=n}
                    <a class="pagination-bar__size{if $per_page == $n} pagination-bar__size--active{/if}" href="{$base_url|escape}/category/{$category.id|escape}?sort={$sort|escape}&amp;page=1&amp;per_page={$n|escape}">{$n|escape}</a>
                {/foreach}
            </div>
        </div>
    </div>
{/if}
{/block}
