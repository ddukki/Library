<div>
    <x-card>
        <div class="flex" style="align-items: flex-start; justify-content: space-between">
            <div style="flex: 1">
                <span x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></span>
                <br/>
                <p class="small" style="margin-top: 0.75rem; margin-bottom: 0" x-show="{{ $item }}.books && {{ $item }}.books.length > 0">
                    Books:
                </p>
                <template x-for="book in ({{ $item }}.books || [])" :key="book.id">
                    <span class="badge badge--primary" x-text="book.title"></span>
                </template>
            </div>
            <div>
                <a x-bind:href="route('authors.edit', { author: {{ $item }}.id })">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
    </x-card>
</div>
