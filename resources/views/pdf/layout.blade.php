<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		{{-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet"> --}}
		<title>Learner Assessment Datasheet</title>

		<style>
			.flex-container {
				display: flex;
				align-items: center;
			}

			.w-100 {
				width: 100%;
			}

			.flex-img {
				display: flex;
				justify-content: center;
				/* Centers items horizontally */
				align-items: center;
				/* Centers items vertically */
			}

			/* Generate style for heading */
			.heading {
				font-weight: bold;
				text-align: center;
				margin-bottom: 1rem;
				margin-bottom: 1.5rem;
			}

			h1,
			h2,
			h3 {
				font-weight: bold;
				font-size: 18px;
				margin-bottom: 1rem;
			}

			.flex-img img {
				max-width: 20%;
				height: auto;
			}

			.mb-2 {
				margin-bottom: 1rem;
			}

			.mb-4 {
				margin-bottom: 1.1rem;
			}

			.mb-8 {
				margin-bottom: 1.8rem;
			}

			.my-10 {
				margin-top: 3rem;
				margin-bottom: 1rem;
			}

			.ml-10 {
				margin-left: 25%;

			}

			.ml-5 {
				margin-left: 15%;
			}

			.grouped-y {
				margin-bottom: 3rem;
			}

			.grouped-y .mb-2 {
				margin-bottom: 5px;
			}

			.items-center {
				text-align: center;
			}

			.item {
				display: flex;
				align-items: center;
				margin-right: 1rem;
			}

			.label {
				font-weight: 100 !important;
				margin-right: 0.5rem;
				font-size: 15px;
				color: #494848;
			}

			.input-field {
				font-size: 14px;
				color: #000000;
				font-weight: bold;
				/* border-b: 1px solid #ccc;
				border-radius: 0.25rem;
				padding: 0.5rem;
				width: 200px; */
				/* margin-top: 3em !important;
				display: inline-block; */
			}
		</style>
	</head>

	<body class="bg-white font-sans">
		<div class="container">
			<!-- Main Page -->
			@yield('content')
			<!-- End Main Page -->
		</div>
	</body>

</html>
