<div class="container-fluid pt-3 bg-wallpaper">
	&nbsp;
</div>
<nav class="navbar navbar-expand-md navbar-static-top">
    <div class="container-fluid bg-palette0">
	<div class="navbar-header">

	    <!-- Collapsed Hamburger -->
	    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#app-navbar-collapse" aria-controls="#app-navbar-collapse" aria-expanded="false">
		<span class="navbar-togglers-icon"></span>
	    </button>

	    <!-- Branding Image -->
	    <a class="navbar-brand mch-font-color" href="{{ url('/') }}">
		{{ config('app.name', 'Hatchery') }}
	    </a>
	</div>

	<div class="collapse navbar-collapse" id="app-navbar-collapse">
	    <!-- Left Side Of Navbar -->
	    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		@if(isset($file))
		    <li class="nav-item"><a href="{{ route('projects.index') }}" class="nav-link">Eggs</a></li>
		    @if(Auth::check())
			@can('update', $file->version->project)
			    <li class="nav-item"><a href="{{ route('projects.edit', ['project' => $file->version->project->slug]) }}" class="nav-link">{{ $file->version->project->name }}</a></li>
			@else
			    <li class="nav-item"><a href="{{ route('projects.show', ['project' => $file->version->project->slug]) }}" class="nav-link">{{ $file->version->project->name }}</a></li>
			@endcan
		    @else
		    <li class="nav-item"><a href="{{ route('projects.show', ['project' => $file->version->project->slug]) }}" class="nav-link">{{ $file->version->project->name }}</a></li>
		    @endif
		    <li class="nav-item"><a class="nav-link">{{ $file->name }}</a></li>
		@elseif(isset($project) && !isset($projects))
		    <li class="nav-item"><a href="{{ route('projects.index') }}" class="nav-link">Eggs</a></li>
		    <li class="nav-item"><a class="nav-link">{{ $project->name }}</a></li>
		@else
		    <li class="nav-item">
			<a href="{{ route('projects.index') }}" class="nav-link">Eggs</a>
		    </li>
		@endif
		    <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link">Users</a></li>
		    <li class="nav-item"><a href="{{ route('badges.index') }}" class="nav-link">Badges</a></li>
	    </ul>

	    <!-- Right Side Of Navbar -->
	    <div class="collape navbar-collapse justify-content-end">
	    <ul class="navbar-nav">
		<!-- Authentication Links -->
		@if (Auth::guest())
		    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
		    <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
		@else
		    <li class="dropdown">
			<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown" role="button" aria-expanded="false">
			    {{ Auth::user()->name }} <span class="caret"></span>
			</a>
			<ul class="dropdown-menu dropdown-menu-end" role="menu">
			    <li class="dropdown-item">
				<a href="{{ route('users.edit', Auth::user()->id) }}" class="nav-link">Profile</a>
			    </li>
			    <li class="dropdown-item">
				<a href="{{ route('logout') }}" class="nav-link"
				    onclick="event.preventDefault();
					     document.getElementById('logout-form').submit();">
				    Logout
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				    {{ csrf_field() }}
				</form>
			    </li>
			</ul>
		    </li>
		@endif
	    </ul>
		</div>
	</div>
    </div>
</nav>
