/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.mixin({
    methods: {
        route: route
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// Shelf components
Vue.component('shelf-manager', () => import('./components/library/shelves/ShelfManager.vue'));
Vue.component('shelf-form', () => import('./components/library/shelves/ShelfForm.vue'));
Vue.component('shelf-books', () => import('./components/library/shelves/ShelfBooks.vue'));

// Book components
Vue.component('all-books', () => import('./components/library/books/AllBooks.vue'));
Vue.component('book-card', () => import('./components/library/books/BookCard.vue'));
Vue.component('book-form', () => import('./components/library/books/BookForm.vue'));

//Author components
Vue.component('all-authors', () => import('./components/library/authors/AllAuthors.vue'));
Vue.component('author-card', () => import('./components/library/authors/AuthorCard.vue'));
Vue.component('select-authors', () => import('./components/library/authors/SelectAuthors.vue'));
Vue.component('author-form', () => import('./components/library/authors/AuthorForm.vue'));

Vue.component('book-editions', () => import('./components/library/editions/Editions.vue'));
Vue.component('edition-card', () => import('./components/library/editions/EditionCard.vue'));
Vue.component('shelve-edition', () => import('./components/library/editions/ShelveEdition.vue'));

//Location Type Components
Vue.component('all-location-types', () => import('./components/library/locationtypes/LocationTypes.vue'));

// Miscellaneous Components for the library
Vue.component('pagination-vue', () => import('./components/library/Pagination.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
