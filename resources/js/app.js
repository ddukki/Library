import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import '@fortawesome/fontawesome-free/js/all.js';
import shelfForm from './alpine/shelf-form';
import shelfManager from './alpine/shelf-manager';
import authorForm from './alpine/author-form';
import locationTypes from './alpine/location-types';
import bookEditions from './alpine/book-editions';
import bookForm from './alpine/book-form';
import allAuthors from './alpine/all-authors';
import allBooks from './alpine/all-books';

Alpine.plugin(collapse);

import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

document.addEventListener('alpine:init', () => {
    Alpine.data('shelfForm', shelfForm);
    Alpine.data('shelfManager', shelfManager);
    Alpine.data('authorForm', authorForm);
    Alpine.data('locationTypes', locationTypes);
    Alpine.data('bookEditions', bookEditions);
    Alpine.data('bookForm', bookForm);
    Alpine.data('allAuthors', allAuthors);
    Alpine.data('allBooks', allBooks);
});

Alpine.start();
