<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <button type="button" onclick="location.href='<?php echo base_url("add-spareparts-sub");?>'" class="btn btn-custom btn-rounded w-md waves-effect waves-light">
                <i class="fa fa-plus"></i> Add New
            </button>
            <h4 class="header-title m-b-30 pull-right"><?php echo $contentTitle;?></h4><br><hr>
            
            <p class="text-success text-center">
                <?php
                $error = $this->session->flashdata('error');
                if($error)
                {
                ?>
                <div class="alert alert-danger alert-dismissable" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $error; ?>                    
                </div>
                <?php
                }
                $success = $this->session->flashdata('success');
                if($success)
                {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $success; ?>                    
                </div>
                <?php } ?>
            </p>
            
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="data_grid" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Part Number</th>
                                <th>Part Number Subtitute</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Responsive Datatable with Buttons
        var table = $('#data_grid').DataTable({
            dom: "<'row'<'col-sm-12'B><'col-sm-10'l><'col-sm-2'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-9'p><'col-sm-3'i>>",
            destroy: true,
            stateSave: false,
            deferRender: true,
            processing: true,
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i>',
                    titleAttr: 'Copy',
                    exportOptions: {
                        columns: ':visible:not(:last-child)',
                        modifier: {
                            page: 'current'
                        }
                    },
                    footer:false
                }
            ],
            ajax: {                
                url: "<?= base_url('front/cpartsub/get_m_list_datatable'); ?>",
                type: "POST",
                dataType: "JSON",
                contentType: "application/json",
                data: JSON.stringify( {
                    "<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
                } ),
            },
            columns: [
                { "data": 'partno' },
                { "data": 'partnosub' },
                { "data": 'button' },
            ],
        });

        table.buttons().container()
                .appendTo('#data_grid_wrapper .col-md-12:eq(0)');
    });
</script>