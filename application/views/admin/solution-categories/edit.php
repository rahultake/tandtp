<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
    init_head(); 
?>
<div id="wrapper" class="solution_category">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($solution_categories) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <?php echo form_open('admin/solution-categories/update/'.$solution_categories[0]->sol_cat_id); ?>
                <div class="panel_s">
                    <div class="panel-body">
                                <div class="form-group" app-field-wrapper="sol_cat_title"><label for="sol_cat_title" class="control-label">
                                    <small class="req text-danger">* </small>Solution Category Title</label>
                                    <input type="text" id="sol_cat_title" value="<?php echo $solution_categories[0]->sol_cat_title?>" name="sol_cat_title" class="form-control" autofocus="1" placeholder="Solution Category Title">
                                </div>
                            </div>
                            <div class="panel-footer text-right tw-space-x-1" id="profile-save-section">     
                                <button class="btn btn-primary only-save customer-form-submiter">
                                <i class="fa fa-save"></i> <?php echo _l('submit'); ?>
                                </button>
                            </div>
                            <?php echo form_close(); ?>
                </div>
                
            </div>
        </div>

    </div>
</div>
<?php init_tail(); ?>
<script>
    function confirmDelete(sol_cat_id) {
        if (confirm('<?php echo _l('confirm_delete'); ?>')) {
            window.location.href = '<?php echo base_url('admin/solution-categories/delete/'); ?>' + sol_cat_id;
        }
    }
</script>
</body>
</html>