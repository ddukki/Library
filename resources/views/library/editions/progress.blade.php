<div class="row">
    <div class="col-6">
        <h5>Reading Progress Tracker</h5>
        <div class="row no-gutters text-center">
            <div class="col-1">0</div>
            <div class="col mt-1">
                @if(count($allProgress) == 0)
                    <div class="progress">
                        <div class="progress-bar bg-light text-dark" role="progressbar" style="width: 100%">No Progress Made!</div>
                    </div>
                @else
                    <div class="progress">
                        @for($i = 0; $i < count($allProgress); $i++)
                            @if($i == 0 && $allProgress[$i][0] != 1)
                                <div class="progress-bar bg-light"
                                        role="progressbar"
                                        style="width: {{ ($allProgress[$i][0] - 1) / $edition->location_size * 100 }}%">
                                </div>
                            @endif

                            @if($i > 0)
                                <div class="progress-bar bg-light"
                                        role="progressbar"
                                        style="width: {{ ($allProgress[$i][0] - 1 - $allProgress[$i - 1][1]) / $edition->location_size * 100 }}%">
                                </div>
                            @endif

                            <div class="progress-bar"
                                    role="progressbar"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="@if($allProgress[$i][0] == $allProgress[$i][1]) {{ $allProgress[$i][0] }} @else {{ $allProgress[$i][0] }} to {{ $allProgress[$i][1] }} @endif"
                                    style="width: {{ ($allProgress[$i][1] - $allProgress[$i][0] + 1) / $edition->location_size * 100 }}%">
                            </div>
                        @endfor

                        @if($allProgress[count($allProgress) - 1][1] < $edition->location_size)
                            <div class="progress-bar bg-light"
                                    role="progressbar"
                                    style="width: {{ ($edition->location_size - $allProgress[count($allProgress) - 1][1]) / $edition->location_size * 100 }}%">
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-md-auto ml-2">{{ $edition->location_size }}</div>
        </div>
        <form method="post" action="{{route('progress.store')}}">
            @csrf
            <input type="hidden" name="edition_id" value="{{ $edition->id }}"/>
            <div class="form-group mt-3">
                <label for="location_start">Reading Start</label>
                <input id="location_start"
                        name="location_start"
                        class="form-control"
                        title="Where did you start reading?"
                        type="text"></input>
            </div>
            <div class="form-group">
                <label for="location_end">Reading End</label>
                <input id="location_end"
                        name="location_end"
                        class="form-control"
                        title="Where did you stop reading?"
                        type="text"></input>
            </div>
            <button class="btn btn-primary" type="submit">
                Add Reading
            </button>
        </form>
    </div>
    <div class="col-6">
        <h5>Reading Log</h5>
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th>Start</th>
                    <th>End</th>
                    <th>Length</th>
                    <th>Date/Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($edition->progress as $ep)
                    <tr>
                        <td>{{ $ep->location_start }}</td>
                        <td>{{ $ep->location_end }}</td>
                        <td>{{ $ep->location_end - $ep->location_start + 1 }}</td>
                        <td>{{ $ep->datetime }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
