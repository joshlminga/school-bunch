{{-- Extend the Layout --}}
@extends('pdf.layout')

{{-- Content --}}
@section('content')
	<div class="p-6">

		@include('pdf._logo')

		<div class="px-5">
			<div class="heading items-center mb-8">
				<h1 class="text-xl text-center font-bold">MINISTRY OF EDUCATION</h1>
				<h1 class="text-xl text-center font-bold">ASSESSMENT OF LEARNERS FOR PRINT DISABILITY</h1>
				<h2 class="text-xl text-center font-bold">LEARNER ASSESSMENT DATASHEET</h2>
			</div>

			<div class="flex-container w-100 mb-2">
				<div class="item">
					<span class="label">County:</span>
					<span class="input-field"></span>

					<span class="label ml-10">Sub-County:</span>
					<span class="input-field"></span>
				</div>
			</div>

			<div class="flex-container w-100 mb-4">
				<div class="item">
					<span class="label">Name of Assessor:</span>
					<span class="input-field"></span>
				</div>
			</div>

			<div class="flex-container w-100 mb-2">
				<div class="item">
					<span class="label">Name of School:</span>
					<span class="input-field"></span>
				</div>
			</div>

			<div class="flex-container w-100 mb-2">
				<div class="item">
					<span class="label">Electricity:</span>
					<span class="input-field"></span>

					<span class="label ml-5">Internet:</span>
					<span class="input-field"></span>

					<span class="label ml-5">ICT Teacher:</span>
					<span class="input-field"></span>
				</div>
			</div>

			<div class="flex-container w-100 mb-2">
				<div class="item">
					<span class="label">Name of the learner:</span>
					<span class="input-field"></span>
				</div>
			</div>

			<div class="flex-container w-100 mb-2">
				<div class="item">
					<span class="label">Assessment Number:</span>
					<span class="input-field"></span>

					<span class="label ml-5">Year of birth:</span>
					<span class="input-field"></span>

					<span class="label ml-5">Gender:</span>
					<span class="input-field"></span>
				</div>
			</div>

			<div class="flex-container w-100 mb-2">
				<div class="item">
					<span class="label">Name of Parent/Guardian:</span>
					<span class="input-field"></span>

					<span class="label ml-10"> Phone No:</span>
					<span class="input-field"></span>
				</div>
			</div>

			<div class="my-10">
				<div class="grouped-y">
					<div class="flex-container w-100 mb-2">
						<div class="item">
							<span class="label">Visual ability:</span>
							<span class="input-field"></span>
						</div>
					</div>
					<div class="flex-container w-100 mb-2">
						<div class="item">
							<span class="label">Recommendation:</span>
							<span class="input-field"></span>
						</div>
					</div>
				</div>
				<div class="grouped-y">
					<div class="flex-container w-100 mb-2">
						<div class="item">
							<span class="label">Reading ability:</span>
							<span class="input-field"></span>
						</div>
					</div>
					<div class="flex-container w-100 mb-2">
						<div class="item">
							<span class="label">Recommendation:</span>
							<span class="input-field"></span>
						</div>
					</div>
				</div>
				<div class="grouped-y">
					<div class="flex-container w-100 mb-2">
						<div class="item">
							<span class="label">Physical ability:</span>
							<span class="input-field"></span>
						</div>
					</div>
					<div class="flex-container w-100 mb-2">
						<div class="item">
							<span class="label">Recommendation:</span>
							<span class="input-field"></span>
						</div>
					</div>
				</div>
			</div>

			<div class="flex-container mb-4">
				<div class="item">
					<span class="label">Type of Device Delivered:</span>
					<span class="input-field">………………………………</span>

					<span class="label">Serial No:</span>
					<span class="input-field">………………………………</span>
				</div>
			</div>

			<div class="flex-container mb-4">
				<div class="item">
					<span class="label">IMEI No:</span>
					<span class="input-field">………………………………………………………………………………………………</span>
				</div>
			</div>

			<div class="flex-container mb-4">
				<div class="item">
					<span class="label">Device Received By:</span>
					<span class="input-field">……………………</span>

					<span class="label">Designation:</span>
					<span class="input-field">……………………</span>

					<span class="label">Date:</span>
					<span class="input-field">……………………</span>
				</div>
			</div>

			<div class="flex-container mb-4">
				<div class="item">
					<span class="label">Phone No:</span>
					<span class="input-field">……………………</span>

					<span class="label">Signature:</span>
					<span class="input-field">……………………</span>

					<span class="label">School Stamp:</span>
					<span class="input-field">……………………….</span>
				</div>
			</div>

		</div>
	</div>
@endsection
