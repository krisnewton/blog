@extends('layouts.app')

@section('page_title', 'Post')

@section('content')
	<x-breadcrumb :links="$breadcrumb" size="lg"/>
	<x-card-large>
		<x-title>Post</x-title>

		@can('access', 'posts.create')
			<div class="text-right">
				<a href="{{ route('posts.create') }}" class="btn btn-primary">Buat Post Baru</a>
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

		@if ($posts)
			<div class="table-responsive p-2">
				<table class="w-100 table table-sm table-bordered table-hover" id="posts_index">
					<thead class="thead-light">
						<tr>
							<th style="width: 50%;">Judul</th>
							<th class="desktop">Status</th>
							<th class="desktop">Penulis</th>
							<th class="desktop">Pmbc.</th>
							<th class="desktop">Waktu</th>
							<th class="desktop">Menu</th>
							<th>Timestamp</th>
						</tr>
					</thead>
				</table>
			</div>
		@endif
	</x-card-large>

	<x-modal id="deleteItem" title="Hapus Post">
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
@endsection

@push('styles')
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">
@endpush

@push('scripts')
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
	<script>
		$(document).ready(function () {
			$("#posts_index").DataTable({
				"responsive" : true,
				"pagingType" : "simple",
				"processing" : true,
				"serverSide" : true,
				"ajax" : "{{ route('posts.datatables') }}",
				"order" : [[4, "desc"]],
				"columnDefs" : [
					{ "targets" : 0, "data" : "title" },
					{ "targets" : 1, "data" : "status", "searchable" : false, "orderable" : false },
					{ "targets" : 2, "data" : "author", "searchable" : false, "orderable" : false },
					{ "targets" : 3, "data" : "views" ,"searchable" : false },
					{ "targets" : 4, "data" : "created_at", "orderData" : 6, "searchable" : false },
					{ "targets" : 5, "data" : "action", "searchable" : false, "orderable" : false },
					{ "targets" : 6, "data" : "timestamp", "visible" : false }
				]
			});

			$(document).on("click", "[data-id]", function () {
				var deleteButton = $(this);

				$("#itemName").text(deleteButton.data("name"));
				$("#formDeleteItem").attr("action", deleteButton.data("action"));

				$("#deleteItem").modal();
			});
		});
	</script>
@endpush