function datatablebase(tableid, ffoot, scroll, order, search, arrayOrder, arrayExport) {
    /*  
     ejemplo
     var arrayOrder = [14,'asc'],[0,'asc'],[3,'asc'],[5,'asc'];
     var arrayExport = ['excel'];
*/
    if (ffoot) {
        $('#' + tableid + ' tfoot th').each(function () {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Buscar ' + title + '" class="filtrof" />');
        });
    }

    var ordenar = true;

    if (typeof arrayOrder === 'undefined') {
        ordenar = false;
        arrayOrder = [];
    }

    if (typeof arrayExport === 'undefined') {
        arrayExport = [];
    }

    var table = $('#' + tableid).DataTable({
        "scrollY": scroll + "px",
        "scrollX": true,
        "scrollCollapse": true,
        "paging": false,
        "ordering": order,
        "searching": search,
        "sort": ordenar,
        "order": arrayOrder,
        buttons: arrayExport,
        language: {
            processing: "En proceso...",
            search: "Buscar&nbsp;:",
            lengthMenu: "Ver _MENU_ Registros",
            info: "Visualizando _START_ a _END_ de _TOTAL_ Registros",
            infoEmpty: "No hay Registros en la Tabla",
            infoFiltered: "(Filtrado de _MAX_ registros totales)",
            infoPostFix: "",
            loadingRecords: "Cargando...",
            zeroRecords: "No hay Registros en la Tabla",
            emptyTable: "No hay Registros en la Tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Final"
            },
            aria: {
                sortAscending: ":habilitado para ordenar la columna en orden ascendente",
                sortDescending: ":habilitado para ordenar la columna en orden ascendente"
            },
            buttons: {
                copy: "Copiar",
                colvis: "Ver Columnas"
            }
        }
    });

    if (arrayExport.length != 0) {
        table.buttons().container().appendTo('#' + tableid + '_wrapper .col-sm-6:eq(0)');
    }

    if (ffoot) {
        table.columns().every(function () {
            var that = this;

            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that.search(this.value).draw();
                }
            });
        });
    }
    /*
    $('#' + tableid + ' tbody').on('click', 'tr', function () {
        table.$('tr.success').removeClass('success');
        $(this).addClass('success');
    });
    */
}
