<x-app-layout>
	<!-- if trial is active then show the trial message -->
	@if ($trial)
		<div class="bg-red-500 border-l-4 border-red-700 text-white p-4 text-center font-bold" role="alert">
			<p>Trial Account</p>
			<p>{{ $trial }}</p>
		</div>
	@endif

	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Upload Excel File') }}
		</h2>
		<p class="text-white mt-2">If you have multiple worksheet in your document please remove.</p>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
			<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

				<div class="mb-8 space-y-6">
					<!-- Notification -->
					{!! $notify !!}
				</div>

				<div class="max-w-xl">
					<form action="{!! url('upfile/upload') !!}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
						@csrf
						<div class="flex space-x-10 mb-8">
							<div class="col-span-5">
								<x-input-label for="file" value="{{ __('File') }}" class="sr-only" />

								<x-text-input id="file" name="school_file[]" type="file" class="block w-full"
									placeholder="{{ __('File') }}" />

								<x-input-error :messages="$errors->first('school_file')" class="mt-2" />
							</div>

							<div class="col-span-5 flex justify-end">
								<x-submit-button class="ms-3">
									{{ __('Click To Upload') }}
								</x-submit-button>
							</div>
						</div>

						<hr />

					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
