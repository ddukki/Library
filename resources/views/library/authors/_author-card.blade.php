<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-10">
                    <span x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></span>
                    <br/>
                    <p class="small mb-0 mt-3" x-show="{{ $item }}.books && {{ $item }}.books.length > 0">
                        Books:
                    </p>
                    <template x-for="book in ({{ $item }}.books || [])" :key="book.id">
                        <span class="badge badge-primary mr-1" x-text="book.title"></span>
                    </template>
                </div>
                <div class="col-2">
                    <a x-bind:href="route('authors.edit', { author: {{ $item }}.id })">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
