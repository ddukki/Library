<span class="badge badge--primary" style="margin-right: 0.5rem">
    <span x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></span>
    <a href="#" x-on:click.prevent="unselectAuthor( {{ $item }} )">
        <i class="fas fa-times-circle"></i>
    </a>
</span>
