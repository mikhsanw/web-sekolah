$(document).ready(function () {
var datatable = $('#datatable').DataTable({
responsive: true,
lengthChange: false,
searchDelay: 500,
processing: true,
serverSide: true,
stateSave: true,
lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "Semua"]],
ajax: "{{ route($url . '.index') }}",
columns: [
{ data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
{ data: 'tahun_ajaran', defaultContent: '-' },
{ data: 'judul', defaultContent: '-' },
{ data: 'tanggal_buka', defaultContent: '-' },
{ data: 'tanggal_tutup', defaultContent: '-' },
{ data: 'kuota', defaultContent: '-' },
{ data: 'dibuka', render: function (data) { return data ? '<span class="badge badge-light-success">Ya</span>' : '<span class="badge badge-light-danger">Tidak</span>'; } },
{ data: 'aktif', render: function (data) { return data ? '<span class="badge badge-light-success">Ya</span>' : '<span class="badge badge-light-danger">Tidak</span>'; } },
{ data: 'action', orderable: false, searchable: false, className: 'text-center' }
],
});

exportButtons();
$('#search').on('keyup', function () {
datatable.search(this.value).draw();
});
});

var exportButtons = () => {
const documentTitle = '{{ $page->title ?? 'Laporan' }}';
new $.fn.dataTable.Buttons('#datatable', {
buttons: [
{ extend: 'copyHtml5', title: documentTitle },
{ extend: 'excelHtml5', title: documentTitle },
{ extend: 'csvHtml5', title: documentTitle },
{ extend: 'pdfHtml5', title: documentTitle }
]
}).container().appendTo($('#kt_datatable_example_buttons'));

const exportButtons = document.querySelectorAll('#kt_datatable_example_export_menu [data-kt-export]');
exportButtons.forEach(exportButton => {
exportButton.addEventListener('click', e => {
e.preventDefault();
const exportValue = e.target.getAttribute('data-kt-export');
const target = document.querySelector('.dt-buttons .buttons-' + exportValue);
target.click();
});
});
}
