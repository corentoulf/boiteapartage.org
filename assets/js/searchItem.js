import DataTable from '../vendor/datatables.net/datatables.net.index.js'
import '../vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import '../vendor/datatables.net-bs5/datatables.net-bs5.index.js';
import '../vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';
import '../vendor/datatables.net-responsive-bs5/datatables.net-responsive-bs5.index.js';
import '../vendor/datatables.net-select/datatables.net-select.index.js';

import Filterizr from 'filterizr'
Filterizr.installAsJQueryPlugin($);
//Filterizr options
const options = {
    animationDuration: 0,
    // callbacks: {
    //     onInit: function() { },
    //     onFilteringStart: function() { },
    //     onFilteringEnd: function() { },
    //     onShufflingStart: function() { },
    //     onShufflingEnd: function() { },
    //     onSortingStart: function() { },
    //     onSortingEnd: function() { }
    // },
    // controlsSelector: '',
    // delay: 0,
    // delayMode: 'progressive',
    // easing: 'ease-out',
    // filter: 'all',
    filterOutCss: {
        // opacity: 0,
        // transform: 'scale(0.5)'
    },
    filterInCss: {
        // opacity: 0,
        // transform: 'scale(1)'
    },
    // gridItemsSelector: '.filtr-item',
    // gutterPixels: 0,
    layout: 'sameHeight',
    // multifilterLogicalOperator: 'or',
    // searchTerm: '',
    // setupControls: true,
    // spinner: {
    //     enabled: false,
    //     fillColor: '#2184D0',
    //     styles: {
    //     height: '75px',
    //     margin: '0 auto',
    //     width: '75px',
    //     'z-index': 2,
    //     },
    // },
}
const filterizr = new Filterizr('.filter-container', options);

// DATATABLE
// //table to datatable
// let itemTable = new DataTable('#itemTable', {
//     dom: '<"mb-3"t><"d-flex justify-content-between"ip>',
//     language: {
//         url: 'https://cdn.jsdelivr.net/npm/datatables.net-plugins@2.0.8/i18n/fr-FR.json',
//     },
//     select: {
//         info: false,
//         items: 'row'
//     },
//     pageLength: 20,
//     responsive: true,
//     columnDefs: [
//         { targets: [1], orderable: true},
//         { targets: '_all', orderable: false }
//     ],
//     order: [[1, 'asc']]
// });

//external input filter
$('#filterItemTableInput').on('keyup', function () {
    filterizr.search(this.value);
});

// //init search input on page load
// itemTable.search($('#filterItemTableInput').val()).draw();

//FILTERIZR