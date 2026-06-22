<tr>
    <td>
        <button x-show="!isSelected( {{ $item }} )"
                class="btn btn-sm btn-outline-primary"
                x-on:click.prevent="selectAuthor( {{ $item }} )">
            <i class="fas fa-check"></i>
        </button>
        <button x-show="isSelected( {{ $item }} )"
                class="btn btn-sm btn-primary"
                x-on:click.prevent="unselectAuthor( {{ $item }} )">
            <i class="fas fa-check"></i>
        </button>
    </td>
    <td x-text="`${ {{ $item }}.first_name } ${ {{ $item }}.middle_name } ${ {{ $item }}.last_name }`"></td>
    <td>
        <template x-for="(abook, n) in ( {{ $item }}.books || [])" :key="n">
            <span class="badge badge-primary mr-1" x-text="abook.title"></span>
        </template>
    </td>
</tr>
