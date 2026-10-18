<div class="quote-form">
    <h5 style="margin-bottom: 0.75rem">Quotes</h5>

    @forelse($edition->quotes as $q)
    <div class="quote-list__item"
         x-data="{
             editing: false,
             quote: '{{ addslashes($q->quote) }}',
             location: '{{ addslashes($q->location ?? '') }}',

             saveEdit(quoteId) {
                 axios.put(route('quotes.update', { quote: quoteId }), {
                     quote: this.quote,
                     location: this.location,
                 }).then(response => {
                     this.editing = false;
                     window.dispatchEvent(new CustomEvent('toast', {
                         detail: { message: 'Quote updated.', variant: 'success', duration: 3000 },
                     }));
                 }).catch(error => {
                     window.dispatchEvent(new CustomEvent('toast', {
                         detail: { message: 'Failed to update quote.', variant: 'danger', duration: 5000 },
                     }));
                 });
             },

             deleteQuote(quoteId) {
                 if (!confirm('Delete this quote?')) return;

                 axios.delete(route('quotes.destroy', { quote: quoteId })).then(response => {
                     this.$el.closest('.quote-list__item').remove();
                     window.dispatchEvent(new CustomEvent('toast', {
                         detail: { message: 'Quote deleted.', variant: 'success', duration: 3000 },
                     }));
                 }).catch(error => {
                     window.dispatchEvent(new CustomEvent('toast', {
                         detail: { message: 'Failed to delete quote.', variant: 'danger', duration: 5000 },
                     }));
                 });
             },
         }">
        <x-card compact>
            <template x-if="!editing">
                <div class="quote-list__text">
                    <span x-text="quote"></span><br/>
                    <span class="badge badge--secondary" x-text="location"></span>
                </div>
            </template>
            <template x-if="editing">
                <div class="quote-form" style="width: 100%">
                    <div class="form-group">
                        <label class="form-group__label">Quote Text</label>
                        <textarea class="form-input__field" x-model="quote"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-group__label">Location</label>
                        <input class="form-input__field" type="text" x-model="location" />
                    </div>
                </div>
            </template>
            <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem">
                <template x-if="!editing">
                    <button class="btn btn--sm" @click="editing = true" aria-label="Edit quote">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                </template>
                <template x-if="editing">
                    <button class="btn btn--success btn--sm" @click="saveEdit({{ $q->id }})" aria-label="Save quote">
                        <i class="fas fa-check"></i>
                    </button>
                </template>
                <template x-if="!editing">
                    <button class="btn btn--danger btn--sm" @click="deleteQuote({{ $q->id }})" aria-label="Delete quote">
                        <i class="fas fa-trash"></i>
                    </button>
                </template>
                <template x-if="editing">
                    <button class="btn btn--sm" @click="editing = false" aria-label="Cancel edit">
                        <i class="fas fa-times"></i>
                    </button>
                </template>
            </div>
        </x-card>
    </div>
    @empty
        <i>No Quotes Available!</i>
    @endforelse
</div>

<div class="quote-form">
    <form action="{{ route('quotes.store') }}" method="POST">
        @csrf
        <input type="hidden" name="edition_id" value="{{ $edition->id }}" />

        <div class="form-group">
            <label class="form-group__label" for="quoteText">Quote Text</label>
            <textarea id="quoteText"
                    name="quoteText"
                    placeholder="(add a new quote)"
                    class="form-input__field"></textarea>
        </div>
        <div class="form-group">
            <label class="form-group__label" for="location">Location</label>
            <input type="text" placeholder="(e.g. page number or location index)" class="form-input__field" id="location" name="location" />
        </div>
        <x-button type="submit">
            Add Quote
        </x-button>
    </form>
</div>
