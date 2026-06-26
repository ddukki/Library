<nav x-show="page && page.last_page > 1" style="margin-top: 1rem">
    <ul class="pagination" role="navigation" style="justify-content: center">
        <template x-for="n in page.last_page" :key="n">
            <li>
                <a class="pagination__link" :class="{ 'pagination__link--active': isCurrentPage(n) }"
                   href="#" x-on:click.prevent="getPage(n)"
                   x-text="n">
                </a>
            </li>
        </template>
    </ul>
</nav>
