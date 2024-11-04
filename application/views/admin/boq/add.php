<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="boq">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 col-md-12">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/boq/boq_tabs'); ?>
                </div>
            <div class="panel_s">
                <?php $boq_query_string = isset($boqs[0]->boq_id)?'/'.$boqs[0]->boq_id:'';?>
                <?php echo form_open('admin/boq/add/'.$project_id.$boq_query_string); ?>
                    <div class="panel-heading">
                    <h3 class="m-10">BOQ <div class="label label-primary"><?php echo $projects[0]->name?></div></h3>
                    </div>
                    <div class="panel-body">
                        
                            <label for="boq_title">Title:</label>
                            <input class="form-control" type="text" name="boq_title" required value="<?php echo isset($boqs[0]->boq_id)?$boqs[0]->boq_title:""; ?>">
                            
                            <label for="remarks">Remarks:</label>
                            <textarea class="form-control" name="remarks" required></textarea>
                        </div>
                        <div class="panel-body">
                            <button type="button" class="btn btn-primary add-item-btn"><i class="fa fa-plus"></i> Add Item</button>
                            <table class="table table-striped boq-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Item</th>
                                        <!-- <th>Location</th> -->
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($boqs)){//IF Copying from an existing BOQ
                                        foreach($boq_items as $boq_item){
                                        ?>
                                    <tr>
                                        <td>
                                            <select name="boq_item_id[]" class="form-control boq-item-select">
                                                <option value="0">Select Item</option>
                                                <?php foreach ($items as $item): ?>
                                                    <option value="<?php echo $item->id; ?>" <?php echo ($item->id==$boq_item->item_id)?"selected":""?>><?php echo $item->description; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <!-- <td>
                                            <select name="boq_item_loc_id[]" class="form-control boq-item-location-select">
                                                <option value="0">Select Location</option>
                                                <?php foreach ($locations as $location): ?>
                                                    <option value="<?php echo $location->loc_id; ?>" <?php echo ($location->loc_id==$boq_item->loc_id)?"selected":""?>><?php echo $location->location_title; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td> -->
                                        <td><input type="number" class="form-control item_qty" name="boq_item_qty[]" value="<?php echo $boq_item->item_qty?>" ></td>
                                        <td><input type="number" class="form-control boq-item-price" name="boq_item_price[]" value="<?php echo $boq_item->item_price?>" ></td>
                                        <td class="text-right amount"><?php echo ($boq_item->item_qty * $boq_item->item_price)?></td>
                                        <td><button type="button" class="btn btn-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                    <?php 
                                        }
                                    }
                                    else{//IF adding a Fresh BOQ ?>
                                    <tr>
                                        <td>
                                        <select name="boq_item_id[]" class="form-control boq-item-select">
                                            <option value="0">Select Item</option>
                                            <?php foreach ($items as $item): ?>
                                                <option value="<?php echo $item->id; ?>"><?php echo $item->description; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        </td>
                                        <td>
                                            <select name="boq_item_loc_id[]" class="form-control boq-item-location-select">
                                                <option value="0">Select Location</option>
                                                <?php foreach ($locations as $location): ?>
                                                    <option value="<?php echo $location->loc_id; ?>" ><?php echo $location->location_title; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control  item_qty" name="boq_item_qty[]" value="1" ></td>
                                        <td><input type="number" class="form-control boq-item-price" name="boq_item_price[]" value="0.00" ></td>
                                        <td class="text-right amount">0.00</td>
                                        <td><button type="button" class="btn btn-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save BOQ</button>
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
                                '<select name="boq_item_id[]" class="form-control boq-item-select">' +
                                    '<option value="0">Select Item</option>' +
                                    '<?php foreach ($items as $item): ?>' +
                                        '<option value="<?php echo $item->id; ?>"><?php echo $item->description; ?></option>' +
                                    '<?php endforeach; ?>' +
                                '</select>' +
                            '</td>' +
                            '<td><input type="number" class="form-control item_qty" name="boq_item_qty[]" value="1" ></td>' +
                            '<td><input type="text" class="form-control boq-item-price" name="boq_item_price[]" value="0.00" ></td>' + 
                            '<td class="text-right amount">0.00</td>' + 
                            '<td><button type="button" class="btn btn-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>' +
                        '</tr>';
            $('.boq-table tbody').append(newRow);
        });

        // Remove Row Button
        $(document).on('click', '.remove-row-btn', function() {
            $(this).closest('tr').remove();
        });
        $(document).on('change', '.boq-item-select', function() {
            var item_id = $(this).val();
            var $row = $(this).closest('tr');

            $.ajax({
                url: '<?php echo base_url('admin/boq/get_item_details'); ?>',
                type: 'POST',
                data: {item_id: item_id, customer_group_id: <?php echo isset($customer_group[0]->customer_group_id) ? $customer_group[0]->customer_group_id : 'null'; ?>},
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.length > 0) {
                        var item_price = parseFloat(data[0].rate).toFixed(2);
                        $row.find('.boq-item-price').val(item_price);
                        $row.find('.amount').text(item_price);
                        //console.log(data);
                        // Add new rows for additional records
                        for (var i = 1; i < data.length; i++) {
                            var newRow = '<tr>' +
                                '<td>' +
                                    '<select name="boq_item_id[]" class="form-control boq-item-select">' +
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
                                '<td>' +
                                    '<select name="boq_item_loc_id[]" class="form-control boq-loc-select">' +
                                        '<option value="0">Select Location</option>' +
                                        '<?php foreach ($locations as $location): ?>' +
                                            '<option value="<?php echo $location->loc_id; ?>"><?php echo $location->location_title; ?></option>' +
                                        '<?php endforeach; ?>' +
                                    '</select>' +
                                '</td>' +
                                '<td><input type="number" class="form-control item_qty" name="boq_item_qty[]" value="1" ></td>' +
                                '<td><input type="number" class="form-control boq-item-price" name="boq_item_price[]" value="'+data[i].group_price+'" ></td>' + 
                                '<td class="text-right amount">0.00</td>' + 
                                '<td><button type="button" class="btn btn-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>' +
                            '</tr>';
                            $('.boq-table tbody').append(newRow);
                            
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
            // $.ajax({
            //     url: '<?php echo base_url('admin/boq/get_item_price'); ?>',
            //     type: 'POST',
            //     data: {item_id: item_id},
            //     success: function(response) {
            //         var item_price = parseFloat(response).toFixed(2);
            //         $row.find('.boq-item-price').val(item_price);
            //     },
            //     error: function(xhr, status, error) {
            //         console.error(error);
            //     }
            // });
        });
        $(document).on('change', '.item_qty', function() {
            var $row = $(this).closest('tr');
            var price = $row.find('.boq-item-price').val();
            var qty = $(this).val();
            var amount = price * qty;
            $row.find('.amount').html(amount);
        });
    });
</script>
