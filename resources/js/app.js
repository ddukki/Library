import Vue from 'vue';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import '@fortawesome/fontawesome-free/js/all.js';
import shelfForm from './alpine/shelf-form';
import shelfManager from './alpine/shelf-manager';

Alpine.plugin(collapse);

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.mixin({
    methods: {
        route: route
    }
});

Vue.component('all-books', () => import('./components/library/books/AllBooks.vue'));
Vue.component('book-card', () => import('./components/library/books/BookCard.vue'));
Vue.component('book-form', () => import('./components/library/books/BookForm.vue'));

Vue.component('all-authors', () => import('./components/library/authors/AllAuthors.vue'));
Vue.component('author-card', () => import('./components/library/authors/AuthorCard.vue'));
Vue.component('select-authors', () => import('./components/library/authors/SelectAuthors.vue'));
Vue.component('author-form', () => import('./components/library/authors/AuthorForm.vue'));

Vue.component('book-editions', () => import('./components/library/editions/Editions.vue'));
Vue.component('edition-row', () => import('./components/library/editions/EditionRow.vue'));
Vue.component('shelve-edition', () => import('./components/library/editions/ShelveEdition.vue'));

Vue.component('all-location-types', () => import('./components/library/locationtypes/LocationTypes.vue'));

Vue.component('pagination-vue', () => import('./components/library/Pagination.vue'));

const vueRoot = document.getElementById('vue-root');
if (vueRoot) {
    new Vue({
        el: '#vue-root',
    });
}

document.addEventListener('alpine:init', () => {
    Alpine.data('shelfForm', shelfForm);
    Alpine.data('shelfManager', shelfManager);
});

Alpine.start();
