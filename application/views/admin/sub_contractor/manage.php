<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    #subcontractorTable_paginate{margin-top: 15px;}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons tw-mb-2 sm:tw-mb-4">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subcontractorModal">
                        <i class="fa-regular fa-plus tw-mr-1"></i> New Sub Contractor
                    </button>
                    <div class="clearfix"></div>                    
                </div>

                <div class="panel_s tw-mt-2 sm:tw-mt-4">
                    <div class="panel-body">                        
                        <div class="panel-table-full">
                        <table id="subcontractorTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sub Contractor Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sub_contractors as $sub_contractor) : ?>
                                    <tr>
                                        <td>#<?php echo sprintf("%04d",$sub_contractor->id); ?></td>
                                        <td><?php echo $sub_contractor->sub_contractor_name; ?></td>
                                        <td><?php echo $sub_contractor->sub_contractor_description; ?></td>
                                        <td>
                                            <a class="btn-sm btn btn-primary" data-toggle="modal" data-target="#subcontractorModal" onclick="editSub_contractor(<?php echo $sub_contractor->id; ?>)" title="Edit sub contractor">Edit</a>
                                            <a class="btn-sm btn btn-success" href="<?php echo admin_url('sub-contractor/boq/' . $sub_contractor->id); ?>" title="BOQ">BOQ</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/projects/copy_settings'); ?>
<?php init_tail(); ?>
<!-- Subcontractor Modal -->
<div id="subcontractorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="subcontractorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('sub-contractor/'), ['id' => 'sub_contractor_form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">
                    <span class="edit-title hide">Edit Subcontractor</span>
                    <span class="add-title">Create Subcontractor</span>
                </h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="sub_contractor_id" id="sub_contractor_id" value="">
                <div class="form-group">
                    <label for="sub_contractor_name">Name</label>
                    <input type="text" class="form-control" id="sub_contractor_name" name="sub_contractor_name" placeholder="Enter subcontractor name">
                </div>
                <div class="form-group">
                    <label for="sub_contractor_description">Description</label>
                    <textarea class="form-control" id="sub_contractor_description" name="sub_contractor_description" rows="3" placeholder="Enter description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="saveSubcontractor">Save</button>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
var table = $('#subcontractorTable').DataTable({
        "paging": true, // Enable pagination
        "lengthChange": true, // Enable table length option
        "searching": true, // Enable search
        "ordering": true, // Enable ordering (sorting)
        "info": true, // Show table info
        "autoWidth": false, // Disable automatic column width adjustment
        "responsive": true, // Make table responsive
        "language": {
            search: "_INPUT_", // Removes the default search label
            searchPlaceholder: "Search..." // Adds a placeholder to the search input
        },
        "initComplete": function() {
            // Remove the 'table-loading' class from the div
            $('#subcontractorTable_wrapper').removeClass('table-loading');

            // Customize the search input by wrapping it with additional HTML
            $('#subcontractorTable_filter').html(`
                <div class="input-group">
                    <span class="input-group-addon"><span class="fa fa-search"></span></span>
                    <input type="search" class="form-control input-sm" placeholder="Search..." aria-controls="subcontractorTable">
                </div>
            `);

            // Ensure the new search input works with DataTables
            $('#subcontractorTable_filter input').on('input', function () {
                table.search(this.value).draw();
            });
        }
    });
    function editSub_contractor(sub_contractorId) {
        $.ajax({
            url: admin_url + 'sub-contractor/get_sub_contractor/' + sub_contractorId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#sub_contractor_id').val(response.id);
                $('#sub_contractor_name').val(response.sub_contractor_name);
                $('#sub_contractor_description').val(response.sub_contractor_description);
                $('.edit-title').removeClass('hide');
                $('.add-title').addClass('hide');
            }
        });
    }
</script>
</body>

</html>