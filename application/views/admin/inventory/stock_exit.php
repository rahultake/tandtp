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
                <?php echo form_open('admin/inventory/stock_exit/'.$store_id); ?>
                    <div class="panel-heading">
                    <h3 class="m-10">Stock Exit </h3>
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
                                <div class="form-group">
                                <label for="sub_contractor_id">Sub Contractor:</label>
                                <select name="sub_contractor_id" class="form-control" id="sub_contractor_id" required>
                                    <option value="">Select Sub Contractor</option>
                                    <?php foreach ($sub_contractors as $sub_contractor): ?>
                                        <option value="<?php echo $sub_contractor->sub_id; ?>"><?php echo $sub_contractor->sub_contractor_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                <label for="destination">Destination:</label>
                                <input class="form-control" type="text" name="destination" required>
                            </div>
                            <div class="form-group">   
                                <label for="remarks">Remarks:</label>
                                <textarea class="form-control" name="remarks" rows="5" required></textarea>
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
        var itemLoaQuantities = {};
        $('#sub_contractor_id').on('change', function() {
            var sub_contractor_id = $(this).val();
            var project_id = <?php echo $project_id; ?>;

            if (sub_contractor_id) {
                $.ajax({
                    url: '<?php echo base_url('admin/inventory/get_items_by_sub_contractor'); ?>',
                    type: 'POST',
                    data: { sub_contractor_id: sub_contractor_id, project_id: project_id },
                    success: function(response) {
                        var items = JSON.parse(response);
                        $('.stock-item-select').each(function() {
                            var $dropdown = $(this); // Store reference to the current dropdown
                            $dropdown.html('<option value="0">Select Item</option>'); // Reset dropdown

                            $.each(items, function(index, item) {
                                $dropdown.append('<option value="' + item.id + '">' + item.description + '</option>');
                            });
                        });
                        // Store the items globally for reuse when adding rows
                        window.subContractorItems = items;
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching items: ", error);
                    }
                });
            }
        });

        $('form').on('submit', function(event) {
            var isValid = true;
            $('.stock-table tbody tr').each(function() {
                var item_id = $(this).find('.stock-item-select').val();
                var item_qty = parseFloat($(this).find('.item_qty').val());
                // Check if item_qty exceeds loa_qty
                if (item_id && itemLoaQuantities[item_id] && item_qty > itemLoaQuantities[item_id]) {
                    alert("Item quantity for " + $(this).find('.stock-item-select option:selected').text() + " exceeds LOA Quantity!");
                    isValid = false;
                    return false; // Break out of loop
                }
            });

            if (!isValid) {
                event.preventDefault(); // Prevent form submission
            }
        });

        // Add Item Button - Dynamically populate item options for new rows
        $('.add-item-btn').on('click', function() {
            var newRow = '<tr>' +
                            '<td>' +
                                '<select name="stock_item_id[]" class="form-control stock-item-select">' +
                                    '<option value="0">Select Item</option>';

            // Populate options using globally stored items
            if (window.subContractorItems) {
                $.each(window.subContractorItems, function(index, item) {
                    newRow += '<option value="' + item.id + '">' + item.description + '</option>';
                });
            }

            newRow += '</select>' +
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
                url: '<?php echo base_url('admin/inventory/get_item_details'); ?>',
                type: 'POST',
                data: {item_id: item_id},
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.length > 0) {
                        var loa_qty = parseFloat(data[0].remaining_loa_qty); // Get loa_qty from response
                        // Store loa_qty in the itemLoaQuantities object
                        itemLoaQuantities[item_id] = loa_qty;
                        var item_price = parseFloat(data[0].rate).toFixed(2);
                        var item_unit = data[0].unit;
                        $row.find('.stock-item-price').val(item_price);
                        $row.find('.amount').text(item_price);
                        $row.find('.item_unit').text(item_unit);
                        //console.log(data);
                        // Add new rows for additional records
                        // for (var i = 1; i < data.length; i++) {
                        //     var newRow = '<tr>' +
                        //         '<td>' +
                        //             '<select name="stock_item_id[]" class="form-control stock-item-select">' +
                        //                 '<option value="0">Select Item</option>' +
                        //                 '<?php foreach ($items as $item): ?>';
                        //                     var sel = "";
                        //                     if(data[i].id==<?php echo $item->id; ?>){
                        //                         sel = "selected";
                        //                     }
                        //     newRow = newRow+                 
                        //                     '<option value="<?php echo $item->id; ?>" '+sel+' ><?php echo $item->description; ?></option>' +
                        //                 '<?php endforeach; ?>' +
                        //             '</select>' +
                        //         '</td>' +
                        //         '<td><input type="number" class="form-control item_qty" name="stock_item_qty[]" value="1" ></td>' +
                        //         '<td><p class="item_unit"></p></td>' +
                        //         '<td><button type="button" class="btn btn-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>' +
                        //     '</tr>';
                        //     $('.stock-table tbody').append(newRow);
                            
                        // }
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>