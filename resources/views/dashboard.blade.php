<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Dashboard') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					{{ __('Welcome:') }}

					<p>Start by checking the uploading format</p>


					<!-- Tailwind download button with icon -->
					<a href="{!! asset('media/template-school-2024.xlsx') !!}" target="_blank" download="template-school-2024.xlsx"
						class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 me-2" viewBox="0 0 20 20" fill="currentColor">
							<path fill-rule="evenodd"
								d="M10 2a1 1 0 0 1 .707.293l3 3a1 1 0 0 1-1.414 1.414L11 5.414V10a1 1 0 1 1-2 0V5.414L7.707 6.707a1 1 0 0 1-1.414-1.414l3-3A1 1 0 0 1 10 2zm-1 16a1 1 0 0 1-.707-.293l-3-3a1 1 0 0 1 1.414-1.414L9 14.586V10a1 1 0 1 1 2 0v4.586l1.293-1.293a1 1 0 0 1 1.414 1.414l-3 3A1 1 0 0 1 9 18z" />
						</svg>
						Download Sample File
					</a>

				</div>
			</div>
		</div>
	</div>
</x-app-layout>
