dtFnc = {
    initConfirm: function(){
        $('table a[data-method="post"]').on('click', function (event) {
            event.preventDefault();
            var elm = this;
            var url = $(elm).attr('href');
            var table = $('#'+ $(this).closest('table').attr('id')).DataTable();
            var message = $(elm).attr('data-confirm');
            if (typeof url !== typeof undefined && url !== false || typeof url !== typeof undefined && url !== false) {
                swal({
                    title: '' + message + '',
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: $(elm).attr('data-method'),
                            url: $(elm).attr('href'),
                            success: function(data, textStatus, jqXHR){
                                console.log(data);
                                table.ajax.reload();
                                swal({
                                    type: "success",
                                    title: "Deleted!",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                //self.initConfirm(api);
                            },
                            error: function( jqXHR, textStatus, errorThrown){
                                swal({
                                    type: "error",
                                    title: errorThrown,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            },
                            dataType: "json"
                        });
                    }
                });
            }
            return false;
        });
    },
    initSelect2: function(api,col){
        $.each(col, function( index, value ) {
            api.columns(value).every( function () {
                var column = this;
                var id = 'select2-'+column.index() + '-' +api.table().node().id;
                var colheader = this.header();
                var placeholder = $(colheader).text().trim();
                $('<p></p>').appendTo( colheader );
                var select = $('<select id="'+id+'" class="dt-select2"><option value="" >All</option></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column.search( val ? '^'+val+'$' : '', true, false ).draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
                var select2Options = {"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":placeholder,"language":"th","sizeCss":"input-sm"};
                if (jQuery('#'+id).data('select2')) { 
                    jQuery('#'+id).select2('destroy'); 
                }
                jQuery.when(jQuery('#'+id).select2(select2Options)).done(initS2Loading(id,'select2Options'));
                $("#"+id + ",span.select2-container--bootstrap").addClass("input-sm");
            } );
        });
    },
    initColumnIndex: function(api){
        api.on( 'order.dt search.dt draw.dt', function () {
            api.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    },
    initResponsive: function(api){
        new $.fn.dataTable.Responsive( api );
    },
    footerSummary :function (api,col,decimal){
        if(!decimal){
            decimal = 2;
        }
        // Remove the formatting to get integer data for summation
        var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
        };

        var addCommas = function(value){
            value += '';
            x = value.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        };

        $.each(col, function(i,n) {
            // Total over all pages
            total = api
                .column( n )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( n, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Update footer
            total = addCommas(total.toFixed(decimal));
            $( api.column( n ).footer() ).html(
                total.toString()
            );
        });
    },
};