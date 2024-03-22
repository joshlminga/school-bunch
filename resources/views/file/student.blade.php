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
			{{ __('Student Info') }}
		</h2>
		<p class="text-white mt-2">You can print records in bunch of 60's or One at a time</p>
	</x-slot>

	@if (!empty($notify) && !is_null($notify))
		<div class="py-3">
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
				<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
					<div class="mb-8 space-y-6">
						<!-- Notification -->
						{!! $notify !!}
					</div>
				</div>
			</div>
		</div>
	@endif

	<div class="py-3">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
			<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
				<div class="overflow-x-auto">
					<div class="max-w-xl">
						<a href="{{ url('/pdf/export') }}?doc={{ $this_doc }}"
							class="inline-flex items-center w-100 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
							Print -PDF(s) In Group
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
			<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

				<!-- Tailwind css table, with sticky header, fist td as checkbox, allow check all, last column with three action button 'import','view students', 'delete' -->
				<div class="overflow-x-auto">
					<table class="table-auto w-full">
						<thead class="border-b-2 shadow-lg">
							<tr>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									#
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									County
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									School
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									Learners
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									Status
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									Printed At
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									Uploaded At
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									Actions
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($results as $this_student)
								<tr class="bg-white dark:bg-gray-800 even:bg-gray-50 dark:even:bg-gray-700">
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">{{ $this_student->id }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">{{ $this_student->county }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">
											@php
												$school = json_decode($this_student->school, true);
											@endphp
											{{ $school['school'] }}
										</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">{{ $this_student->learner }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<!-- if this_file->imported == 1 then show imported else show not imported -->
										@if ($this_student->printed == 1)
											<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
												Printed
											</span>
										@else
											<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
												Not Printed
											</span>
										@endif
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">{{ $this_student->last_printed }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">{{ $this_student->created_at }}</div>
									</td>

									<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
										<div class="flex space-x-5">
											<a href="{{ url('/pdf/export') }}?std={{ $this_student->id }}"
												class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
												Export PDF
											</a>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					<!-- Pagination -->
					<div class="mt-4">

						{{ $results->links() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
<script>
	// ?  Delete data
	const deleteData = (userId) => {
		// ? Are you sure
		Swal.fire({
			title: 'Are you sure you want to delete?',
			text: "This can't be undone!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, delete it!',
			cancelButtonText: 'No, cancel!',
		}).then((result) => {
			if (result.isConfirmed) {
				// If confirmed, send a request to the Laravel route for deletion
				let base_url = `{{ url('/upfile/file') }}`;
				window.location.href = `${base_url}?id=${userId}&action=delete`;
			} else {
				// If canceled, close the SweetAlert dialog
				showConfirm = false;
			}
		});
	}
</script>
