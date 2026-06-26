<div style="padding-top: 1.5rem; border-top: 1px solid var(--color-border, #e2e8f0)">
    <div>
        <h5 style="margin-bottom: 0.75rem">Quotes</h5>
        @if(count($edition->quotes) == 0)
            <i>No Quotes Available!</i>
        @else
            @foreach($edition->quotes as $q)
                <div style="margin-bottom: 0.5rem">
                    <x-card compact>
                        <div style="padding: 0.5rem">
                            {{ $q->quote }}<br/>
                            <span class="badge badge--secondary">{{ $q->location }}</span>
                        </div>
                    </x-card>
                </div>
            @endforeach
        @endif
    </div>
    <div style="margin-top: 1rem">
        <form action="{{ route('quotes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="edition_id" value="{{ $edition->id }}" />

            <div class="form-group">
                <label for="quoteText">Quote Text</label>
                <textarea id="quoteText"
                        name="quoteText"
                        placeholder="(add a new quote)"
                        class="form-input__field">
                </textarea>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" placeholder="(e.g. page number or location index)" class="form-input__field" id="location" name="location" />
            </div>
            <x-button type="submit">
                Add Quote
            </x-button>
        </form>
    </div>
</div>
