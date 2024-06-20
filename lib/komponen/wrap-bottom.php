</div>
</main>

<!-- footer -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row text-muted">
            <div class="col-6 text-start">
                <p class="mb-0">
                    <a class="text-muted" href="" target="_blank"><strong>Sistem Informasi Pencatatan Kas</strong></a>
                </p>
            </div>
            <div class="col-6 text-end">

            </div>
        </div>
    </div>
</footer>
<!-- end footer -->

</div>
</div>

<script src="../lib/js/app.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI for Datepicker -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Buttons extension JS -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Datepickers
        $("#min-date, #max-date").datepicker({
            dateFormat: 'yy-mm-dd'
        });

        // Custom filtering function for date range
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var min = $('#min-date').val();
                var max = $('#max-date').val();
                var startDate = data[1]; // The start date column

                if (
                    (min === '' && max === '') ||
                    (min === '' && moment(startDate).isSameOrBefore(max)) ||
                    (moment(startDate).isSameOrAfter(min) && max === '') ||
                    (moment(startDate).isSameOrAfter(min) && moment(startDate).isSameOrBefore(max))
                ) {
                    return true;
                }
                return false;
            }
        );

        var table = $('#table1').DataTable({
            // dom: '<"row mb-3"<"col-md-6"lf><"col-md-6"fB>>rt<"bottom"ip>',
            buttons: [{
                extend: 'pdfHtml5',
                text: 'Cetak'
                // exportOptions: {
                //     columns: ':not(:last-child)' // Exclude the last column
                // }
            }]
        });
        var table2 = $('#table2').DataTable({
            // dom: '<"row mb-3"<"col-md-6"lf><"col-md-6"fB>>rt<"bottom"ip>',
            buttons: [{
                extend: 'pdfHtml5',
                text: 'Cetak',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column
                }
            }]
        });

        // Search functionality
        $('#search-bar').on('keyup', function() {
            table.search(this.value).draw();
            table2.search(this.value).draw();
        });

        // Export button functionality
        $('#btn-export').on('click', function() {
            table.button('.buttons-pdf').trigger();
        });
        // Export button functionality
        $('#btn-export').on('click', function() {
            table2.button('.buttons-pdf').trigger();
        });

        // Refilter the table whenever the date range is changed
        $('#min-date, #max-date').on('change', function() {
            table.draw();
            table2.draw();
        });

        // Responsive adjustments for DataTables
        $('#table').DataTable().columns.adjust().responsive.recalc();
        $('#table1').DataTable().columns.adjust().responsive.recalc();
        $('#table2').DataTable().columns.adjust().responsive.recalc();
    });
</script>

</body>

</html>