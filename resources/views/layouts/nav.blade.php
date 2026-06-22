<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm" x-data="{ navOpen: false }">
	<div class="container">
		<a class="navbar-brand" href="{{ url('/') }}">
			{{ config('app.name', 'Laravel') }}
		</a>
		<button class="navbar-toggler" type="button" @click="navOpen = !navOpen" :aria-expanded="navOpen" aria-controls="navbarSupportedContent" aria-label="{{ __('Toggle navigation') }}">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div x-show="navOpen" x-collapse class="navbar-collapse">
			<!-- Left Side Of Navbar -->
			<ul class="navbar-nav mr-auto">

			</ul>

			<!-- Right Side Of Navbar -->
			<ul class="navbar-nav ml-auto">
				<!-- Authentication Links -->
				@guest
					<li class="nav-item">
						<a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
					</li>
					@if (Route::has('register'))
						<li class="nav-item">
							<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
						</li>
					@endif
				@else
				<li class="nav-item dropdown" x-data="{ userOpen: false }">
					<a class="nav-link dropdown-toggle" href="#" @click.prevent="userOpen = !userOpen" :aria-expanded="userOpen" v-pre>
						{{ Auth::user()->name }} <span class="caret"></span>
					</a>

					<div x-show="userOpen" @click.outside="userOpen = false" class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="{{ route('logout') }}"
						   onclick="event.preventDefault();
										 document.getElementById('logout-form').submit();">
							{{ __('Logout') }}
						</a>

						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>
					</div>
				</li>
				@endguest
			</ul>
		</div>
	</div>
</nav>
