$(document).ready(function () {
var parameter = new URLSearchParams(window.location.search);

var datatable = $('#datatable').DataTable({
responsive: true,
lengthChange: false,
searchDelay: 500,
processing: true,
serverSide: true,
lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "Semua"]],
ajax: {
url: "{{ route($url . '.index') }}",
data: function (d) {
d.periode = parameter.get('periode') || '';
}
},
columns: [
{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
{ data: 'nisn' },
{ data: 'nama' },
{ data: 'kelas', defaultContent: '-' },
{ data: 'periode_nama', orderable: false, searchable: false },
{ data: 'status_label', orderable: false, searchable: false },
{ data: 'action', orderable: false, searchable: false, className: 'text-center' }
],
});

$('#search').on('keyup', function () {
datatable.search(this.value).draw();
});
});
