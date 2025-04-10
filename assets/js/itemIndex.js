import DataTable from '../vendor/datatables.net/datatables.net.index.js'
import '../vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import '../vendor/datatables.net-bs5/datatables.net-bs5.index.js';
import '../vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';
import '../vendor/datatables.net-responsive-bs5/datatables.net-responsive-bs5.index.js';
import '../vendor/datatables.net-select/datatables.net-select.index.js';
//table to datatable
let itemTable = new DataTable('#itemTable', {
    dom: '<"mb-3"t><"d-flex justify-content-between"ip>',
    language: {
        url: 'https://cdn.jsdelivr.net/npm/datatables.net-plugins@2.0.8/i18n/fr-FR.json',
    },
    // select: {
    //     info: false,
    //     items: 'row'
    // },
    pageLength: 20,
    responsive: true,
    columnDefs: [
        { targets: [0,1], orderable: true},
        { targets: '_all', orderable: false }
    ],
    order: [[1, 'asc'], [0,'asc']]
});

//external input filter
$('#filterItemTableInput').on('keyup', function () {
    itemTable.search(this.value).draw();
});

//init search input on page load
itemTable.search($('#filterItemTableInput').val()).draw();



