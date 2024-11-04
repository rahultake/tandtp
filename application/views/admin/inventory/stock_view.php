<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
    }
    table, th, td {
        border: 1px solid #000;
    }
    th, td {
        padding: 3px;
        text-align: center;
    }
    th {
        white-space: nowrap;
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .category-header {
        background-color: #FFFF00; /* Yellow background for category */
        font-weight: bold;
        text-align: left;
    }
    .item-row td {
        background-color: #f9f9f9; /* Light background for items */
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 col-md-12">
                <div class="project-menu-panel tw-my-5">
                    <?php $this->load->view('admin/inventory/inventory_tabs'); ?>
                </div>
                <div class="panel_s">
                    <div class="panel-heading">
                        <h3 class="m-10">Inventory Management</h3>
                    </div>
                    <div class="panel-body">
                        <!-- Bootstrap Tabs -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#view_store">View Store</a></li>
                            <li><a data-toggle="tab" href="#daily_material_receipt">Daily Material Receipt</a></li>
                            <li><a data-toggle="tab" href="#daily_material_issue">Daily Material Issue</a></li>
                        </ul>

                        <div class="tab-content">
                            <!-- View Store Tab Content -->
                            <div id="view_store" class="tab-pane fade in active">
                                <?php echo form_open(admin_url('inventory/export_against_store/' . $stores[0]['id'])); ?>
                                <div class="row" style="margin-bottom:15px;">
                                    <div class="col-md-6">
                                        <h4>View Store: <span class="label label-primary">Store: <?php echo $stores[0]['store_name']; ?></span></h4>
                                    </div>
                                    <div class="col-md-3">
                                    <?php $currentMonth = date('F'); ?>
                                        <select class="form-control" name="filter_month">
                                            <option value="01" <?php echo ($currentMonth == 'January') ? 'selected' : ''; ?>>January</option>
                                            <option value="02" <?php echo ($currentMonth == 'February') ? 'selected' : ''; ?>>February</option>
                                            <option value="03" <?php echo ($currentMonth == 'March') ? 'selected' : ''; ?>>March</option>
                                            <option value="04" <?php echo ($currentMonth == 'April') ? 'selected' : ''; ?>>April</option>
                                            <option value="05" <?php echo ($currentMonth == 'May') ? 'selected' : ''; ?>>May</option>
                                            <option value="06" <?php echo ($currentMonth == 'June') ? 'selected' : ''; ?>>June</option>
                                            <option value="07" <?php echo ($currentMonth == 'July') ? 'selected' : ''; ?>>July</option>
                                            <option value="08" <?php echo ($currentMonth == 'August') ? 'selected' : ''; ?>>August</option>
                                            <option value="09" <?php echo ($currentMonth == 'September') ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?php echo ($currentMonth == 'October') ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?php echo ($currentMonth == 'November') ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?php echo ($currentMonth == 'December') ? 'selected' : ''; ?>>December</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success" style="width: 100%;float: inline-end;margin-right: 4px;">
                                            <i class="fas fa-file-export tw-mr-1"></i> Export Stock
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                <div class="row" style="overflow-x: auto;">
                                    <table>
                                        <thead>
                                        <?php
                                        // Get the current month abbreviation in uppercase and the last day of the current month
                                        $monthAbbreviation = strtoupper(date('M')); // e.g., "SEPT"
                                        $lastDayOfMonth = date('t/m/Y'); // Last day of the month in the format "30/09/24"
                                        ?>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Description of Materials</th>
                                                <th>Unit</th>
                                                <th>Closing Stock of <span id="closing"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                <th>Receipt of <span id="receipt"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                <th>Issue of <span id="issue"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                <th>Returned Material of <span id="return"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                <th>Actual Physical Stock of <span id="actual"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sl_no = 1;
                                            foreach ($categories as $category) {
                                                // Display the category row
                                                echo "<tr class='category-header'><td colspan='2'>{$category['category_name']}</td><td colspan='6'></td></tr>";

                                                // Display items under the category
                                                if (!empty($category['items'])) {
                                                    foreach ($category['items'] as $item) {
                                                        $item_qty = isset($store_item_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                                                        $exititem_qty = isset($store_item_exit_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_exit_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                                                        $returnitem_qty = isset($store_item_return_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_return_qty_map[$stores[0]['id']][$item['item_id']] : 0;

                                                        echo "<tr class='item-row' data-item-id='{$item['item_id']}'>";
                                                        echo "<td>{$sl_no}</td>";
                                                        echo "<td style='white-space:nowrap'>{$item['description']}</td>";
                                                        echo "<td>{$item['unit']}</td>";
                                                        echo "<td class='closing-stock'>" . (($item_qty - $exititem_qty) + $returnitem_qty) . "</td>";  // Closing Stock
                                                        echo "<td class='entry-qty'>{$item_qty}</td>";  // Receipt
                                                        echo "<td class='exit-qty'>{$exititem_qty}</td>";  // Issue
                                                        echo "<td class='return-qty'>{$returnitem_qty}</td>";  // Returned Material
                                                        echo "<td class='actual-stock'>" . (($item_qty - $exititem_qty) + $returnitem_qty) . "</td>";  // Actual Physical Stock
                                                        echo "</tr>";
                                                        $sl_no++;
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='8'>No items found for this category</td></tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Daily Material Receipt Tab Content -->
                            <div id="daily_material_receipt" class="tab-pane fade">
                                <?php echo form_open(admin_url('inventory/export_receipt_against_store/' . $stores[0]['id'])); ?>
                                <div class="row" style="margin-bottom:15px;">
                                    <div class="col-md-6">
                                        <h4>Daily Material Receipt: <span class="label label-primary">Store: <?php echo $stores[0]['store_name']; ?></span></h4>
                                    </div>
                                    <div class="col-md-3">
                                    <?php $currentMonth = date('F'); ?>
                                        <select class="form-control" name="receipt_month">
                                            <option value="01" <?php echo ($currentMonth == 'January') ? 'selected' : ''; ?>>January</option>
                                            <option value="02" <?php echo ($currentMonth == 'February') ? 'selected' : ''; ?>>February</option>
                                            <option value="03" <?php echo ($currentMonth == 'March') ? 'selected' : ''; ?>>March</option>
                                            <option value="04" <?php echo ($currentMonth == 'April') ? 'selected' : ''; ?>>April</option>
                                            <option value="05" <?php echo ($currentMonth == 'May') ? 'selected' : ''; ?>>May</option>
                                            <option value="06" <?php echo ($currentMonth == 'June') ? 'selected' : ''; ?>>June</option>
                                            <option value="07" <?php echo ($currentMonth == 'July') ? 'selected' : ''; ?>>July</option>
                                            <option value="08" <?php echo ($currentMonth == 'August') ? 'selected' : ''; ?>>August</option>
                                            <option value="09" <?php echo ($currentMonth == 'September') ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?php echo ($currentMonth == 'October') ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?php echo ($currentMonth == 'November') ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?php echo ($currentMonth == 'December') ? 'selected' : ''; ?>>December</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success" style="width: 100%;float: inline-end;margin-right: 4px;">
                                            <i class="fas fa-file-export tw-mr-1"></i> Export Stock
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                <table>
                                    <thead>
                                        <?php
                                        // Get the current month abbreviation in uppercase and the last day of the current month
                                        $monthAbbreviation = strtoupper(date('M')); // e.g., "SEPT"
                                        $lastDayOfMonth = date('t/m/Y'); // Last day of the month in the format "30/09/24"
                                        ?>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>Description of Materials</th>
                                            <th>Unit</th>
                                            <?php foreach ($day_qty as $day_qtys) { ?>
                                            <th><?php echo $day_qtys['destination']; ?> - <?php echo $day_qtys['movement_day']; ?></th>
                                            <?php } ?>
                                            <th>Total Receipt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sl_no = 1;
                                        foreach ($categories as $category) {
                                            // Display the category row
                                            echo "<tr class='category-header'><td colspan='2'>{$category['category_name']}</td><td colspan='6'></td></tr>";

                                            // Display items under the category
                                            if (!empty($category['items'])) {
                                                foreach ($category['items'] as $item) {
                                                    $item_qty = isset($store_item_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                                                    $exititem_qty = isset($store_item_exit_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_exit_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                                                    $returnitem_qty = isset($store_item_return_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_return_qty_map[$stores[0]['id']][$item['item_id']] : 0;

                                                    echo "<tr class='item-row' data-item-id='{$item['item_id']}'>";
                                                    echo "<td>{$sl_no}</td>";
                                                    echo "<td style='white-space:nowrap'>{$item['description']}</td>";
                                                    echo "<td>{$item['unit']}</td>";
                                                    $total_day_qtys = 0;
                                                    foreach ($day_qty as $day_qtys) {
                                                        if ($day_qtys['item_id'] == $item['item_id']) {
                                                            $total_day_qtys += $day_qtys['item_qty'];
                                                            echo "<td class='day-stock'>{$day_qtys['item_qty']}</td>";
                                                        } else {
                                                            $total_day_qtys = 0;   
                                                            echo "<td class='day-stock'>0</td>"; // If no match, display 0
                                                        }                                                   
                                                    }
                                                    echo "<td class='total-stock'>{$total_day_qtys}</td>";
                                                    echo "</tr>";
                                                    $sl_no++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No items found for this category</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Daily Material Issue Tab Content -->
                            <div id="daily_material_issue" class="tab-pane fade">
                                <?php echo form_open(admin_url('inventory/export_issue_against_store/' . $stores[0]['id'])); ?>
                                <div class="row" style="margin-bottom:15px;">
                                    <div class="col-md-6">
                                        <h4>Daily Material Issue: <span class="label label-primary">Store: <?php echo $stores[0]['store_name']; ?></span></h4>
                                    </div>
                                    <div class="col-md-3">
                                        <?php $currentMonth = date('F'); ?>
                                        <select class="form-control" name="issue_month">
                                            <option value="01" <?php echo ($currentMonth == 'January') ? 'selected' : ''; ?>>January</option>
                                            <option value="02" <?php echo ($currentMonth == 'February') ? 'selected' : ''; ?>>February</option>
                                            <option value="03" <?php echo ($currentMonth == 'March') ? 'selected' : ''; ?>>March</option>
                                            <option value="04" <?php echo ($currentMonth == 'April') ? 'selected' : ''; ?>>April</option>
                                            <option value="05" <?php echo ($currentMonth == 'May') ? 'selected' : ''; ?>>May</option>
                                            <option value="06" <?php echo ($currentMonth == 'June') ? 'selected' : ''; ?>>June</option>
                                            <option value="07" <?php echo ($currentMonth == 'July') ? 'selected' : ''; ?>>July</option>
                                            <option value="08" <?php echo ($currentMonth == 'August') ? 'selected' : ''; ?>>August</option>
                                            <option value="09" <?php echo ($currentMonth == 'September') ? 'selected' : ''; ?>>September</option>
                                            <option value="10" <?php echo ($currentMonth == 'October') ? 'selected' : ''; ?>>October</option>
                                            <option value="11" <?php echo ($currentMonth == 'November') ? 'selected' : ''; ?>>November</option>
                                            <option value="12" <?php echo ($currentMonth == 'December') ? 'selected' : ''; ?>>December</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-success" style="width: 100%;float: inline-end;margin-right: 4px;">
                                            <i class="fas fa-file-export tw-mr-1"></i> Export Stock
                                        </button>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                                <table>
                                    <thead>
                                        <?php
                                        // Get the current month abbreviation in uppercase and the last day of the current month
                                        $monthAbbreviation = strtoupper(date('M')); // e.g., "SEPT"
                                        $lastDayOfMonth = date('t/m/Y'); // Last day of the month in the format "30/09/24"
                                        ?>
                                        <tr>
                                            <th>Sl No</th>
                                            <th>Description of Materials</th>
                                            <th>Unit</th>
                                            <?php foreach ($day_issue_qty as $day_issue_qtys) { ?>
                                            <th><?php echo $day_issue_qtys['destination']; ?> - <?php echo $day_issue_qtys['movement_day']; ?></th>
                                            <?php } ?>
                                            <th>Total Issue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sl_no = 1;
                                        foreach ($categories as $category) {
                                            // Display the category row
                                            echo "<tr class='category-header'><td colspan='2'>{$category['category_name']}</td><td colspan='6'></td></tr>";

                                            // Display items under the category
                                            if (!empty($category['items'])) {
                                                foreach ($category['items'] as $item) {
                                                    $item_qty = isset($store_item_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                                                    $exititem_qty = isset($store_item_exit_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_exit_qty_map[$stores[0]['id']][$item['item_id']] : 0;
                                                    $returnitem_qty = isset($store_item_return_qty_map[$stores[0]['id']][$item['item_id']]) ? $store_item_return_qty_map[$stores[0]['id']][$item['item_id']] : 0;

                                                    echo "<tr class='item-row' data-item-id='{$item['item_id']}'>";
                                                    echo "<td>{$sl_no}</td>";
                                                    echo "<td style='white-space:nowrap'>{$item['description']}</td>";
                                                    echo "<td>{$item['unit']}</td>";
                                                    $total_day_issue_qtys = 0;
                                                    foreach ($day_issue_qty as $day_issue_qtys) {
                                                        if ($day_issue_qtys['item_id'] == $item['item_id']) {
                                                            $total_day_issue_qtys += $day_issue_qtys['item_qty'];
                                                            echo "<td class='day-stock'>{$day_issue_qtys['item_qty']}</td>";
                                                        } else {
                                                            $total_day_issue_qtys = 0;   
                                                            echo "<td class='day-stock'>0</td>"; // If no match, display 0
                                                        }                                                   
                                                    }
                                                    echo "<td class='total-stock'>{$total_day_issue_qtys}</td>";
                                                    echo "</tr>";
                                                    $sl_no++;
                                                }
                                            } else {
                                                echo "<tr><td colspan='8'>No items found for this category</td></tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- end of tab-content -->
                    </div> <!-- end of panel-body -->
                </div> <!-- end of panel_s -->
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
$(document).ready(function() {
    // Trigger AJAX call when the month is changed
    $('select[name="filter_month"]').change(function() {
        var selectedMonth = $(this).val();
        var selectedYear = new Date().getFullYear(); // You can add a year dropdown as well
        var storeId = <?php echo $stores[0]['id']; ?>; // Get the current store ID

        // Get the last day of the selected month and year
        var lastDayOfMonth = new Date(selectedYear, selectedMonth, 0).getDate();

        // Get the month name abbreviation (e.g., 'OCT') for the selected month
        var monthAbbreviation = new Date(selectedYear, selectedMonth - 1).toLocaleString('en-us', { month: 'short' }).toUpperCase();

        // Update the text of the table headers with the new month and last day
        $('#closing').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
        $('#receipt').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
        $('#issue').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
        $('#return').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
        $('#actual').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);

        // Send the AJAX request
        $.ajax({
            url: "<?php echo admin_url('inventory/get_stock_data'); ?>", // Call the PHP function
            method: "POST",
            data: {
                filter_month: selectedMonth,
                filter_year: selectedYear,
                store_id: storeId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    var stockData = response.data;

                    // Iterate through the stock data and update the table
                    $('.item-row').each(function() {
                        var itemId = $(this).data('item-id'); // Get item_id from a data attribute in the row
                        var entryQty = stockData[itemId] && stockData[itemId].entry_qty ? parseFloat(stockData[itemId].entry_qty) : 0;
                        var exitQty = stockData[itemId] && stockData[itemId].exit_qty ? parseFloat(stockData[itemId].exit_qty) : 0;
                        var returnQty = stockData[itemId] && stockData[itemId].return_qty ? parseFloat(stockData[itemId].return_qty) : 0;

                        // Calculate closing stock
                        var closingStock = (parseFloat(entryQty) - parseFloat(exitQty)) + parseFloat(returnQty);
                        
                        // Update the table cells with the new data
                        $(this).find('.closing-stock').text(closingStock);
                        $(this).find('.entry-qty').text(entryQty);
                        $(this).find('.exit-qty').text(exitQty);
                        $(this).find('.return-qty').text(returnQty);
                        $(this).find('.actual-stock').text(closingStock);
                    });
                }
            }
        });
    });
    $('select[name="receipt_month"]').change(function() {
        var selectedMonth = $(this).val();
        var selectedYear = new Date().getFullYear(); // You can add a year dropdown as well
        var storeId = <?php echo $stores[0]['id']; ?>; // Get the current store ID

        // Send the AJAX request
        $.ajax({
            url: "<?php echo admin_url('inventory/get_stock_receipt_data'); ?>", // Call the PHP function
            method: "POST",
            data: {
                filter_month: selectedMonth,
                filter_year: selectedYear,
                store_id: storeId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    var stockData = response.data;
                    // Iterate through each item row
                    $('.item-row').each(function() {
                        var itemId = $(this).data('item-id'); // Get item_id from a data attribute in the row
                        var dayQty = stockData[itemId] ? stockData[itemId].day_qty : 0;
                        var totalQty = stockData[itemId] ? stockData[itemId].total_qty : 0;

                        // Reset day-wise quantities for this item
                        $(this).find('.day-stock').text(dayQty);
                        $(this).find('.total-stock').text(totalQty);
                    });
                }
            }
        });
    });
    $('select[name="issue_month"]').change(function() {
        var selectedMonth = $(this).val();
        var selectedYear = new Date().getFullYear(); // You can add a year dropdown as well
        var storeId = <?php echo $stores[0]['id']; ?>; // Get the current store ID

        // Send the AJAX request
        $.ajax({
            url: "<?php echo admin_url('inventory/get_stock_issue_data'); ?>", // Call the PHP function
            method: "POST",
            data: {
                filter_month: selectedMonth,
                filter_year: selectedYear,
                store_id: storeId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    var stockData = response.data;
                    // Iterate through each item row
                    $('.item-row').each(function() {
                        var itemId = $(this).data('item-id'); // Get item_id from a data attribute in the row
                        var dayQty = stockData[itemId] ? stockData[itemId].day_qty : 0;
                        var totalQty = stockData[itemId] ? stockData[itemId].total_qty : 0;

                        // Reset day-wise quantities for this item
                        $(this).find('.day-stock').text(dayQty);
                        $(this).find('.total-stock').text(totalQty);
                    });
                }
            }
        });
    });
});
</script>