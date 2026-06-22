<nav x-show="page && page.last_page > 1" class="mt-3">
    <ul class="pagination justify-content-center" role="navigation">
        <template x-for="n in page.last_page" :key="n">
            <li x-bind:class="isCurrentPage(n) ? 'page-item active' : 'page-item'">
                <a class="page-link" href="#" x-on:click.prevent="getPage(n)"
                   x-text="n">
                </a>
            </li>
        </template>
    </ul>
</nav>
