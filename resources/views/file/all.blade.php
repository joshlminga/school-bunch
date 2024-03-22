<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			{{ __('Uploaded Files') }}
		</h2>
		<p class="text-white mt-2">Manage Uploaed files, You can Import or Delete all records under the file</p>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
			<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

				<div class="mb-8 space-y-6">
					<!-- Notification -->
					{!! $notify !!}
				</div>

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
									File Name
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									Uploaded At
								</th>
								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									Status
								</th>

								<th
									class="sticky top
                                        bg-white dark:bg-gray-800 dark:text-gray-200 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
									Actions
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($mydocuments as $this_file)
								<tr class="bg-white dark:bg-gray-800 even:bg-gray-50 dark:even:bg-gray-700">
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">{{ $this_file->id }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">{{ $this_file->name }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<div class="text-sm text-gray-900 dark:text-gray-200">{{ $this_file->created_at }}</div>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<!-- if this_file->imported == 1 then show imported else show not imported -->
										@if ($this_file->imported == 1)
											<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
												Imported
											</span>
										@else
											<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
												Not Imported
											</span>
										@endif
									</td>
									<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
										<div class="flex space-x-5">
											@if ($this_file->imported == 0)
												<a href="{{ url('/upfile/file') }}?id=${{ $this_file->id }}&action=import"
													class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
													Import
												</a>
											@endif
											@if ($this_file->imported == 1)
												<a href="{{ url('/upfile/file') }}?id=${{ $this_file->id }}&action=view"
													class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
													View Students
												</a>
											@endif
											<button onclick="deleteData('{{ $this_file->id }}')"
												class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
												Delete
											</button>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
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
