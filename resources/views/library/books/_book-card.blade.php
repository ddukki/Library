<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <a x-bind:href="route('books.show', { book: {{ $item }}.id })"
                       x-text="{{ $item }}.title"></a>
                    <br/>
                    <p class="small mb-0 mt-3" x-show="{{ $item }}.authors && {{ $item }}.authors.length > 0">
                        By:
                    </p>
                    <template x-for="(author, n) in ({{ $item }}.authors || [])" :key="author.id">
                        <span class="small"
                              x-text="n < {{ $item }}.authors.length - 1
                                  ? `${author.first_name} ${author.middle_name} ${author.last_name},`
                                  : `${author.first_name} ${author.middle_name} ${author.last_name}`">
                        </span>
                    </template>
                </div>
                <div class="col-2">
                    <a x-bind:href="route('books.edit', { book: {{ $item }}.id })">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
