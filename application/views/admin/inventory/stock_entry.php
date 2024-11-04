<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 col-md-12">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/inventory/inventory_tabs'); ?>
                </div>
            <div class="panel_s">
                <?php echo form_open('admin/inventory/stock_entry/'.$store_id); ?>
                    <div class="panel-heading">
                    <h3 class="m-10">Stock Entry </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="challan_no">Challan No.:</label>
                                <input class="form-control" type="text" name="challan_no" required>
                                </div>
                                <div class="form-group">
                                <label for="vehicle_no">Vehicle No.:</label>
                                <input class="form-control" type="text" name="vehicle_no" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="destination">Destination:</label>
                                <input class="form-control" type="text" name="destination" required>
                            </div>
                            <div class="form-group">   
                                <label for="remarks">Remarks:</label>
                                <textarea class="form-control" name="remarks" required></textarea>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="panel-body">
                            <button type="button" class="btn btn-primary add-item-btn"><i class="fa fa-plus"></i> Add Item</button>
                            <table class="table table-striped stock-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Item</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                        <select name="stock_item_id[]" class="form-control stock-item-select">
                                            <option value="0">Select Item</option>
                                            <?php foreach ($items as $item): ?>
                                                <option value="<?php echo $item->id; ?>"><?php echo $item->description; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        </td>
                                        <td><input type="number" class="form-control item_qty" name="stock_item_qty[]" value="1" ></td>                                        
                                        <td><p class="item_unit"></p> </td>                                        
                                        <td><button type="button" class="btn btn-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>    
                <?php echo form_close();?>
            </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(document).ready(function() {
        // Add Item Button
        $('.add-item-btn').on('click', function() {
            var newRow = '<tr>' +
                            '<td>' +
                                '<select name="stock_item_id[]" class="form-control stock-item-select">' +
                                    '<option value="0">Select Item</option>' +
                                    '<?php foreach ($items as $item): ?>' +
                                        '<option value="<?php echo $item->id; ?>"><?php echo $item->description; ?></option>' +
                                    '<?php endforeach; ?>' +
                                '</select>' +
                            '</td>' +
                            '<td><input type="number" class="form-control item_qty" name="stock_item_qty[]" value="1" ></td>' +
                            '<td><p class="item_unit"></p></td>' +
                            '<td><button type="button" class="btn btn-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>' +
                        '</tr>';
            $('.stock-table tbody').append(newRow);
        });

        // Remove Row Button
        $(document).on('click', '.remove-row-btn', function() {
            $(this).closest('tr').remove();
        });
        $(document).on('change', '.stock-item-select', function() {
            var item_id = $(this).val();
            var $row = $(this).closest('tr');

            $.ajax({
                url: '<?php echo base_url('admin/boq/get_item_details'); ?>',
                type: 'POST',
                data: {item_id: item_id},
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.length > 0) {
                        var item_price = parseFloat(data[0].rate).toFixed(2);
                        var item_unit = data[0].unit;
                        $row.find('.stock-item-price').val(item_price);
                        $row.find('.amount').text(item_price);
                        $row.find('.item_unit').text(item_unit);
                        //console.log(data);
                        // Add new rows for additional records
                        for (var i = 1; i < data.length; i++) {
                            var newRow = '<tr>' +
                                '<td>' +
                                    '<select name="stock_item_id[]" class="form-control stock-item-select">' +
                                        '<option value="0">Select Item</option>' +
                                        '<?php foreach ($items as $item): ?>';
                                            var sel = "";
                                            if(data[i].id==<?php echo $item->id; ?>){
                                                sel = "selected";
                                            }
                            newRow = newRow+                 
                                            '<option value="<?php echo $item->id; ?>" '+sel+' ><?php echo $item->description; ?></option>' +
                                        '<?php endforeach; ?>' +
                                    '</select>' +
                                '</td>' +
                                '<td><input type="number" class="form-control item_qty" name="stock_item_qty[]" value="1" ></td>' +
                                '<td><p class="item_unit"></p></td>' +
                                '<td><button type="button" class="btn btn-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>' +
                            '</tr>';
                            $('.stock-table tbody').append(newRow);
                            
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>