@extends('layouts.library')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ $edition->book->title }}</h5>
                        <i>{{ $edition->name }} Edition</i>
                    </div>
                    <div class="card-body">
                        @if(count($allProgress) == 0)
                            No progress made!
                        @else
                            <h5>Progress Tracker</h5>
                            <div class="progress">
                                @php
                                    if($allProgress[0][0] == 1) {
                                        $allProgress[0][0] = 0;
                                    }
                                @endphp
                                @for($i = 0; $i < count($allProgress); $i++)
                                    @if($i == 0 && $allProgress[$i][0] != 0)
                                        <div class="progress-bar bg-light"
                                                role="progressbar"
                                                style="width: {{ $allProgress[$i][0] / $edition->location_size * 100 }}%">
                                        </div>
                                    @endif

                                    @if($i > 0)
                                        <div class="progress-bar bg-light"
                                                role="progressbar"
                                                style="width: {{ ($allProgress[$i][0] - $allProgress[$i - 1][1]) / $edition->location_size * 100 }}%">
                                        </div>
                                    @endif

                                    <div class="progress-bar"
                                            role="progressbar"
                                            data-toggle="tooltip" data-placement="top" title="{{ $allProgress[$i][0] }} to {{ $allProgress[$i][1] }}"
                                            style="width: {{ ($allProgress[$i][1] - $allProgress[$i][0]) / $edition->location_size * 100 }}%">
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

                        <div class="row">
                            <div class="col-12 mt-3">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Start</th>
                                            <th>End</th>
                                            <th>Date/Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($edition->progress as $ep)
                                            <tr>
                                                <td>{{ $ep->location_start }}</td>
                                                <td>{{ $ep->location_end }}</td>
                                                <td>{{ $ep->datetime }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <form method="post" action="{{route('progress.store')}}">
                                    @csrf
                                    <input type="hidden" name="edition_id" value="{{ $edition->id }}"/>
                                    <div class="form-group">
                                        <label for="location_start">Location Start</label>
                                        <input id="location_start"
                                                name="location_start"
                                                class="form-control"
                                                type="text"></input>
                                    </div>
                                    <div class="form-group">
                                        <label for="location_end">Location End</label>
                                        <input id="location_end"
                                                name="location_end"
                                                class="form-control"
                                                type="text"></input>
                                    </div>
                                    <button class="btn btn-primary" type="submit">
                                        Add
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    @parent
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endsection
