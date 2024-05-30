/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
// const $ = require( "../node_modules/jquery/src/jquery" );

//dismiss alerts automatically when not manually dismissable
import 'bootstrap';
import './vendor/bootstrap-icons/font/bootstrap-icons.min.css';
import $ from 'jquery';
import DataTable from './vendor/datatables.net/datatables.net.index.js'
import './vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import './vendor/datatables.net-bs5/datatables.net-bs5.index.js';
import './vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';
import './vendor/datatables.net-responsive-bs5/datatables.net-responsive-bs5.index.js';
import './vendor/datatables.net-select/datatables.net-select.index.js';
 
setTimeout(() => {
    $('.alert-success').fadeOut(500, function(){
        this.remove()
    })
}, "5000");

let itemTable = new DataTable('#itemTable', {
    dom: '<"mb-3"t><"d-flex justify-content-between"ip>',
    language: {
        url: 'https://cdn.jsdelivr.net/npm/datatables.net-plugins@2.0.8/i18n/fr-FR.json',
    },
    select: {
        info: false,
        items: 'row'
    },
    pageLength: 20,
    responsive: true,
    columnDefs: [
        { targets: [1], orderable: true},
        { targets: '_all', orderable: false }
    ],
    order: [[1, 'asc']]
});

//external input filter
$('#filterItemTableInput').on('keyup', function () {
    itemTable.search(this.value).draw();
});