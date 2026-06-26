<div style="margin-bottom: 1rem">
    <x-card style="min-height: 8rem">
        <div class="flex" style="align-items: flex-start; justify-content: space-between">
            <div style="flex: 1; overflow: hidden">
                <span x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></span>
                <div class="small" style="margin-top: 0.5rem; max-height: 2.5rem; overflow-y: auto"
                     x-show="{{ $item }}.books && {{ $item }}.books.length > 0">
                    <span class="text-muted">Books:</span>
                    <template x-for="book in ({{ $item }}.books || [])" :key="book.id">
                        <span class="badge badge--primary" x-text="book.title"></span>
                    </template>
                </div>
            </div>
            <div>
                <a x-bind:href="route('authors.edit', { author: {{ $item }}.id })">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
    </x-card>
</div>
