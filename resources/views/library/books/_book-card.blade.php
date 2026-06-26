<div style="margin-bottom: 1rem">
    <x-card>
        <div class="flex" style="align-items: flex-start; justify-content: space-between">
            <div style="flex: 1">
                <a x-bind:href="route('books.show', { book: {{ $item }}.id })"
                   x-text="{{ $item }}.title"></a>
                <div class="small" style="margin-top: 0.5rem" x-show="{{ $item }}.authors && {{ $item }}.authors.length > 0">
                    <span class="text-muted">By:</span>
                    <template x-for="(author, n) in ({{ $item }}.authors || [])" :key="author.id">
                        <span x-text="n < {{ $item }}.authors.length - 1
                            ? `${author.first_name} ${author.middle_name} ${author.last_name},`
                            : `${author.first_name} ${author.middle_name} ${author.last_name}`">
                        </span>
                    </template>
                </div>
            </div>
            <div>
                <a x-bind:href="route('books.edit', { book: {{ $item }}.id })">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
        </div>
    </x-card>
</div>
