@extends('layouts.library')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-offset-2 col-md-8">
			<form class="form" method="POST" action="">
				@csrf
				<div class="form-group">
					<div class="input-group mb-3">
						<input type="text" class="form-control"
								placeholder="Search Books"
								aria-label="Search for books"
								aria-describedby="basic-addon2">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary" type="submit">
								<i class="fas fa-search"></i>
							</button>
						</div>
					</div>
				</div>
			</form>
        </div>

		<shelf-manager>
		</shelf-manager>
    </div>
</div>
@endsection
