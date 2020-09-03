@extends('layouts.app')

@section('page_title', 'Kategori')

@section('content')
	<x-breadcrumb :links="$breadcrumb" size="lg"/>
	<x-card-large>
		<x-title>Kategori</x-title>

		@can('access', 'categories.create')
			<div class="text-right">
				<a href="{{ route('categories.create') }}" class="btn btn-primary">Buat Kategori Baru</a>
			</div>
			<hr>
		@endcan

		@if (session('success'))
			<x-alert type="success">
				{{ session('success') }}
			</x-alert>
		@endif

		@if (session('danger'))
			<x-alert type="danger">
				{{ session('danger') }}
			</x-alert>
		@endif

		@if ($categories)
			<div class="table-responsive p-2">
				<table class="table w-100 table-sm table-bordered table-hover" id="categories_index">
					<thead class="thead-light">
						<tr>
							<th class="w-50">Nama</th>
							<th class="w-50">Menu</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($categories as $category)
							<tr>
								<td>{{ $category->name }}</td>
								<td class="text-right">
									@can('access', 'categories.edit')
										<a href="{{ route('categories.edit', ['category' => $category]) }}" class="btn btn-primary btn-sm">Edit</a>
									@endcan

									@can('access', 'categories.destroy')
										<button class="btn btn-danger btn-sm" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-action="{{ route('categories.destroy', ['category' => $category]) }}">Hapus</button>
									@endcan
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@endif
	</x-card-large>

	@can('access', 'categories.destroy')
		<x-modal id="deleteItem" title="Hapus Kategori">
			<div class="modal-body">
				<p class="mb-0">Hapus <b><span id="itemName"></span></b>?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				<form method="POST" action="#" id="formDeleteItem">
					@csrf
					@method('DELETE')
					<button type="submit" class="btn btn-danger">Hapus</button>
				</form>
			</div>
		</x-modal>
	@endcan
@endsection

@push('styles')
	<link rel="stylesheet" href="{{ asset('vendor/datatables/DataTables-1.10.21/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('scripts')
	<script src="{{ asset('vendor/datatables/DataTables-1.10.21/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('vendor/datatables/DataTables-1.10.21/js/dataTables.bootstrap4.min.js') }}"></script>
	<script>
		$(document).ready(function () {
			$("#categories_index").DataTable({
				"pagingType" : "simple",
				"columnDefs" : [
					{ "targets" : -1, "orderable" : false, "searchable" : false }
				]
			});

			$("[data-id]").click(function () {
				var deleteButton = $(this);

				$("#itemName").text(deleteButton.data("name"));
				$("#formDeleteItem").attr("action", deleteButton.data("action"));

				$("#deleteItem").modal();
			});
		});
	</script>
@endpush