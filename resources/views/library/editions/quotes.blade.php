<div class="row">
    <div class="col-12">
        <h5>Quotes</h5>
        @if(count($edition->quotes) == 0)
            <i>No Quotes Available!</i>
        @else
            @foreach($edition->quotes as $q)
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-2">
                                {{ $q->quote }}<br/>
                                <span class="badge badge-dark">{{ $q->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="col-12">
        <form action="{{ route('quotes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="edition_id" value="{{ $edition->id }}" />

            <div class="form-group">
                <label for="quoteText">Quote Text</label>
                <textarea id="quoteText"
                        name="quoteText"
                        placeholder="(add a new quote)"
                        class="form-control">
                </textarea>
            </div>
            <div class="form-group">
                <label for="quoteText">Location</label>
                <input type="text" placeholder="(e.g. page number or location index)" class="form-control" id="location" name="location" />
            </div>
            <button class="btn btn-primary" type="submit">
                Add Quote
            </button>
        </form>
    </div>
</div>
