<tr>
    <td>
        <button aria-label="Select author" x-show="!isSelected( {{ $item }} )"
                class="btn btn--outline-primary btn--sm"
                x-on:click.prevent="selectAuthor( {{ $item }} )">
            <i class="fas fa-check"></i>
        </button>
        <button aria-label="Unselect author" x-show="isSelected( {{ $item }} )"
                class="btn btn--primary btn--sm"
                x-on:click.prevent="unselectAuthor( {{ $item }} )">
            <i class="fas fa-check"></i>
        </button>
    </td>
    <td x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></td>
    <td>
        <template x-for="(abook, n) in ( {{ $item }}.books || [])" :key="n">
            <span class="badge badge--primary" style="margin-right: 0.25rem" x-text="abook.title"></span>
        </template>
    </td>
</tr>
