<div class="quote-form">
    <h5 style="margin-bottom: 0.75rem">Quotes</h5>
    @if(count($edition->quotes) == 0)
        <i>No Quotes Available!</i>
    @else
        @foreach($edition->quotes as $q)
            <div class="quote-list__item">
                <x-card compact>
                    <div class="quote-list__text">
                        {{ $q->quote }}<br/>
                        <span class="badge badge--secondary">{{ $q->location }}</span>
                    </div>
                </x-card>
            </div>
        @endforeach
    @endif
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
                    class="form-input__field">
            </textarea>
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
