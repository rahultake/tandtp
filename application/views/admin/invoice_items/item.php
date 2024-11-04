<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="sales_item_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <span class="edit-title"><?php echo _l('invoice_item_edit_heading'); ?></span>
                    <span class="add-title"><?php echo _l('invoice_item_add_heading'); ?></span>
                </h4>
            </div>
            <?php echo form_open('admin/invoice_items/manage', ['id' => 'invoice_item_form']); ?>
            <?php echo form_hidden('itemid'); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning affect-warning hide">
                            <?php echo _l('changing_items_affect_warning'); ?>
                        </div>
                        <div class="form-group">
                            <label> Item Type</label>
                            <label><input type="radio" value="Item" name="item_type" class="item_type item"  > Item</label>
                            <label><input type="radio" value="Accessory" name="item_type" class="item_type accessory"> Accessory</label>
                        </div>
                        <div class="form-group" id="parent_item_group">
                            <span>
                                <?php echo render_select('accessory_for', $items, ['id', 'description'], 'Parent Item', $itemid); ?>
                            </span>
                        </div>
                        <?php echo render_input('description', 'invoice_item_add_edit_description'); ?>
                        <?php echo render_textarea('long_description', 'invoice_item_long_description'); ?>
                        <div class="form-group">
                        <label for="item_rate" class="control-label">
                            <?php echo _l('invoice_item_add_edit_rate_currency', $base_currency->symbol); ?>
                        </label>
                        <input type="number" id="item_rate" name="item_rate" class="form-control" value="">
                        </div>
                        <h5>Customer Group Rates <?php echo _l('invoice_item_add_edit_rate_currency_new', $base_currency->symbol); ?></h5>
                        <hr class="clearfix">
                        <div class="clearfix">
                            <?php
                                foreach($customer_groups as $key=>$customer_group){
                            ?>
                            <div class="form-group col-lg-4">
                            <label for="item_group_rate_<?php echo $customer_group['id']?>" class="control-label">
                                [<span class="text-info"><?php echo $customer_group['name'];?></span>]
                                
                            </label>
                                <input type="number" id="item_group_rate_<?php echo $customer_group['id']?>" name="rate[<?php echo $customer_group['id']?>]" class="form-control" value="">
                            </div>
                                
                                <?php
                            }
                            ?>
                        </div>
                       <hr class="clearfix">
                       <div class="form-group">
                        <label for="item_duration" class="control-label">
                            <?php echo _l('invoice_item_add_edit_duration', $base_currency->symbol); ?>
                        </label>
                        <input type="number" id="item_duration" name="item_duration" class="form-control" value="">
                        </div>
                        <?php
                            foreach ($currencies as $currency) {
                                if ($currency['isdefault'] == 0 && total_rows(db_prefix() . 'clients', ['default_currency' => $currency['id']]) > 0) { ?>
                                <div class="form-group">
                                    <label for="rate_currency_<?php echo $currency['id']; ?>" class="control-label">
                                        <?php echo _l('invoice_item_add_edit_rate_currency', $currency['name']); ?></label>
                                        <input type="number" id="rate_currency_<?php echo $currency['id']; ?>" name="rate_currency_<?php echo $currency['id']; ?>" class="form-control" value="">
                                    </div>
                             <?php   }
                            }
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                             <div class="form-group">
                                <label class="control-label" for="tax"><?php echo "GST (%)" ?></label>
                                <select class="selectpicker display-block" data-width="100%" name="tax" data-none-selected-text="<?php echo _l('no_tax'); ?>">
                                    <option value=""></option>
                                    <?php foreach ($taxes as $tax) { ?>
                                    <option value="<?php echo $tax['id']; ?>" data-subtext="<?php echo $tax['name']; ?>"><?php echo $tax['taxrate']; ?>%</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                         <div class="form-group">
                            <!-- <label class="control-label" for="tax2"><?php echo _l('tax_2'); ?></label> -->
                            <?php echo render_input('hsn', 'HSN Code'); ?>
                            <?php //ALTER TABLE `tblitems` ADD `hsn` VARCHAR(100) NULL DEFAULT NULL AFTER `accessory_for`; ?>
                            <span style="display: none;"><select class="selectpicker display-block"  disabled data-width="100%" name="tax2" data-none-selected-text="<?php echo _l('no_tax'); ?>">
                                <option value=""></option>
                                <?php foreach ($taxes as $tax) { ?>
                                <option value="<?php echo $tax['id']; ?>" data-subtext="<?php echo $tax['name']; ?>"><?php echo $tax['taxrate']; ?>%</option>
                                <?php } ?>
                            </select></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix mbot15"></div>
                <?php echo render_input('unit', 'unit'); ?>
                <div id="custom_fields_items">
                    <?php echo render_custom_fields('items'); ?>
                </div>
                <?php echo render_select('group_id', $items_groups, ['id', 'name'], 'item_group'); ?>
                <?php hooks()->do_action('before_invoice_item_modal_form_close'); ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
        <?php echo form_close(); ?>
    </div>
</div>
</div>
</div>
<script>
    // Maybe in modal? Eq convert to invoice or convert proposal to estimate/invoice
    if(typeof(jQuery) != 'undefined'){
        init_item_js();
    } else {
     window.addEventListener('load', function () {
       var initItemsJsInterval = setInterval(function(){
            if(typeof(jQuery) != 'undefined') {
                init_item_js();
                clearInterval(initItemsJsInterval);
            }
         }, 1000);
     });
  }
// Items add/edit
function manage_invoice_items(form) {
    var data = $(form).serialize();

    var url = form.action;
    $.post(url, data).done(function (response) {
        response = JSON.parse(response);
        if (response.success == true) {
            var item_select = $('#item_select');
            if ($("body").find('.accounting-template').length > 0) {
                if (!item_select.hasClass('ajax-search')) {
                    var group = item_select.find('[data-group-id="' + response.item.group_id + '"]');
                    if (group.length == 0) {
                        var _option = '<optgroup label="' + (response.item.group_name == null ? '' : response.item.group_name) + '" data-group-id="' + response.item.group_id + '">' + _option + '</optgroup>';
                        if (item_select.find('[data-group-id="0"]').length == 0) {
                            item_select.find('option:first-child').after(_option);
                        } else {
                            item_select.find('[data-group-id="0"]').after(_option);
                        }
                    } else {
                        group.prepend('<option data-subtext="' + response.item.long_description + '" value="' + response.item.itemid + '">(' + accounting.formatNumber(response.item.rate) + ') ' + response.item.description + '</option>');
                    }
                }
                if (!item_select.hasClass('ajax-search')) {
                    item_select.selectpicker('refresh');
                } else {

                    item_select.contents().filter(function () {
                        return !$(this).is('.newitem') && !$(this).is('.newitem-divider');
                    }).remove();

                    var clonedItemsAjaxSearchSelect = item_select.clone();
                    item_select.selectpicker('destroy').remove();
                    $("body").find('.items-select-wrapper').append(clonedItemsAjaxSearchSelect);
                    init_ajax_search('items', '#item_select.ajax-search', undefined, admin_url + 'items/search');
                }

                add_item_to_preview(response.item.itemid);
            } else {
                // Is general items view
                $('.table-invoice-items').DataTable().ajax.reload(null, false);
            }
            alert_float('success', response.message);
        }
        $('#sales_item_modal').modal('hide');
    }).fail(function (data) {
        alert_float('danger', data.responseText);
    });
    return false;
}
function init_item_js() {
     // Add item to preview from the dropdown for invoices estimates
    $("body").on('change', 'select[name="item_select"]', function () {
        var itemid = $(this).selectpicker('val');
        if (itemid != '') {
            add_item_to_preview(itemid);
        }
    });
    $("body").on('change', '.item_type', function (event) {
        if($(this).hasClass('item')){
            $(this).val('Item');
        }
        else{
            $(this).val('Accessory');
        }
        var itemType = $(this).val();
        var item = $('.item_type:checked');
        console.log(itemType);
        // console.log($(this));
        // console.log(item);
        if (itemType == 'Accessory') {
            $('#parent_item_group').show();
        } else {
            $('#parent_item_group').hide();
            $('#accessory_for').val($('#accessory_for option:first').val());
        }
    });
    // Items modal show action
    $("body").on('show.bs.modal', '#sales_item_modal', function (event) {

        $('.affect-warning').addClass('hide');

        var $itemModal = $('#sales_item_modal');
        $('input[name="itemid"]').val('');
        $itemModal.find('input').not('input[type="hidden"]').val('');
        $itemModal.find('textarea').val('');
        $itemModal.find('select').selectpicker('val', '').selectpicker('refresh');
        $('select[name="tax2"]').selectpicker('val', '').change();
        $('select[name="tax"]').selectpicker('val', '').change();
        $itemModal.find('.add-title').removeClass('hide');
        $itemModal.find('.edit-title').addClass('hide');

        var id = $(event.relatedTarget).data('id');
        // If id found get the text from the datatable
        if (typeof (id) !== 'undefined') {
            $('.affect-warning').removeClass('hide');
            $('input[name="itemid"]').val(id);
            requestGetJSON('invoice_items/get_item_by_id/' + id).done(function (response) {
                // alert(response.accessory_for);
                $("#accessory_for").val(response.accessory_for).trigger('change');
                if(response.item_type=="Accessory"){
                    $(".accessory").prop("checked", true);
                }
                else{
                    $(".item").prop("checked", true);
                }
                $itemModal.find('input[name="description"]').val(response.description);
                $itemModal.find('textarea[name="long_description"]').val(response.long_description.replace(/(<|<)br\s*\/*(>|>)/g, " "));
                $itemModal.find('input[name="rate"]').val(response.rate);
                $itemModal.find('input[name="item_rate"]').val(response.item_rate);
                $itemModal.find('input[name="item_duration"]').val(response.item_duration);
                $itemModal.find('input[name="unit"]').val(response.unit);
                $itemModal.find('input[name="hsn"]').val(response.hsn);
                // console.log(response.customer_group_items);
                var customer_group_items = response.customer_group_items;
                $.each(customer_group_items, function (column, value) {
                    var group = $(this); 
                    //console.log($(group)[0]);
                    // console.log($(group)[0]);
                    $("#item_group_rate_"+$(group)[0].group_id).val($(group)[0].group_price);
                    // if (column.indexOf('rate_currency_') > -1) {
                    //     // $itemModal.find('input[name="' + column + '"]').val(value);
                    //     $("#item_group_rate_1").val(value);
                    // }
                });
                // $("#item_group_rate_1").val(100);
                // $("#item_group_rate_2").val(300);
                $('select[name="tax"]').selectpicker('val', response.taxid).change();
                $('select[name="tax2"]').selectpicker('val', response.taxid_2).change();
                $itemModal.find('#group_id').selectpicker('val', response.group_id);
                $.each(response, function (column, value) {
                    if (column.indexOf('rate_currency_') > -1) {
                        $itemModal.find('input[name="' + column + '"]').val(value);
                    }
                });

                $('#custom_fields_items').html(response.custom_fields_html);

                init_selectpicker();
                init_color_pickers();
                init_datepicker();

                $itemModal.find('.add-title').addClass('hide');
                $itemModal.find('.edit-title').removeClass('hide');
                validate_item_form();
            });

        }
    });

    $("body").on("hidden.bs.modal", '#sales_item_modal', function (event) {
        $('#item_select').selectpicker('val', '');
    });

   validate_item_form();
}
function validate_item_form(){
    // Set validation for invoice item form
    appValidateForm($('#invoice_item_form'), {
        description: 'required',
        rate: {
            required: true,
        }
    }, manage_invoice_items);
}
</script>
