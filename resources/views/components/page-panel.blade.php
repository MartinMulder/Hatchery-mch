@props(['actions'])

<div class="comtainer">
	<div class="row">
		<div class="col text-center">
			<div class="title">
			<h2 class="title-font">{{ $title }}</h2>
			</div>
		</div>
	</div>
	<div class="row content-row mx-3 p-lg-2">
		<div class="col-12 col-md-3">
			{{ $actions ?? ''}}
		</div>

		<div class="col">
			{{ $slot }}
		</div>
	</div>
</div>
