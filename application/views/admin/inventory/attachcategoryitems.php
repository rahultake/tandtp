<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php echo form_open($this->uri->uri_string(), ['id' => 'project_form']); ?>

            <div class="col-md-8 col-md-offset-2">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    Attach Categories to Items
                </h4>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group select-placeholder">
                                    <label for="category_id">Select Category</label>
                                    <div class="clearfix"></div>
                                    <select name="category_id" class="selectpicker" id="category_id" data-width="100%">
                                        <option value=""></option>
                                        <?php foreach ($project_categories as $project_category) : ?>
                                        <option value="<?php echo $project_category->id; ?>"><?php echo $project_category->category_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group select-placeholder">
                                    <label for="item_id">Select Items</label>
                                    <div class="clearfix"></div>
                                    <select name="item_id[]" class="selectpicker" data-width="100%" multiple data-live-search="true" data-actions-box="true" title="Choose items">
                                        <?php foreach ($project_items as $project_item) : ?>    
                                        <option value="<?php echo $project_item->id; ?>"><?php echo $project_item->description; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" data-form="#project_form" class="btn btn-primary" autocomplete="off"
                            data-loading-text="<?php echo _l('wait_text'); ?>">
                            <?php echo _l('submit'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>