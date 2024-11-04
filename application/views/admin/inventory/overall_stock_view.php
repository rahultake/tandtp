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
    .receipt-row td {
        background-color: #f9f9f9; /* Light background for items */
    }
    .issue-row td {
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
                            <li class="active"><a data-toggle="tab" href="#view_overall_store">View Overall Store</a></li>
                            <li><a data-toggle="tab" href="#daily_material_receipt">Daily Material Receipt</a></li>
                            <li><a data-toggle="tab" href="#daily_material_issue">Daily Material Issue</a></li>
                        </ul>

                        <div class="tab-content">
                            <!-- View Overall Store Tab Content -->
                            <div id="view_overall_store" class="tab-pane fade in active">
                                <?php echo form_open(admin_url('inventory/export_to_csv/' . $project_id)); ?>
                                <div class="row" style="margin-bottom:15px;">
                                    <div class="col-md-6">
                                        <h4>View Overall Store:</h4>
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
                                                <?php foreach ($stores as $store) { ?>
                                                    <th><?php echo $store['store_name']; ?> - Closing Stock of <span id="closing-<?= $store['id']; ?>"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                    <th><?php echo $store['store_name']; ?> - Receipt of <span id="receipt-<?= $store['id']; ?>"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                    <th><?php echo $store['store_name']; ?> - Issue of <span id="issue-<?= $store['id']; ?>"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                    <th><?php echo $store['store_name']; ?> - Returned Material of <span id="return-<?= $store['id']; ?>"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                    <th><?php echo $store['store_name']; ?> - Actual Physical Stock of <span id="actual-<?= $store['id']; ?>"><?= $monthAbbreviation ?> as on <?= $lastDayOfMonth ?></span></th>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sl_no = 1;
                                            foreach ($categories as $category) {
                                                echo "<tr class='category-header'><td colspan='2'>{$category['category_name']}</td><td colspan='100'></td></tr>";
                                            
                                                if (!empty($category['items'])) {
                                                    foreach ($category['items'] as $item) {
                                                        echo "<tr class='item-row' data-item-id='{$item['item_id']}'>";
                                                        echo "<td>{$sl_no}</td>";
                                                        echo "<td style='white-space:nowrap;'>{$item['description']}</td>";
                                                        echo "<td>{$item['unit']}</td>";
                                            
                                                        foreach ($stores as $store) {
                                                            $item_qty = isset($store_item_qty_map[$store['id']][$item['item_id']]) ? $store_item_qty_map[$store['id']][$item['item_id']] : 0;
                                                            $exititem_qty = isset($store_item_exit_qty_map[$store['id']][$item['item_id']]) ? $store_item_exit_qty_map[$store['id']][$item['item_id']] : 0;
                                                            $returnitem_qty = isset($store_item_return_qty_map[$store['id']][$item['item_id']]) ? $store_item_return_qty_map[$store['id']][$item['item_id']] : 0;
                                            
                                                            echo "<td class='closing-stock' data-store-id='{$store['id']}'>" . (($item_qty - $exititem_qty) + $returnitem_qty) . "</td>";  // Closing Stock
                                                            echo "<td class='entry-qty' data-store-id='{$store['id']}'>{$item_qty}</td>";  // Receipt
                                                            echo "<td class='exit-qty' data-store-id='{$store['id']}'>{$exititem_qty}</td>";  // Issue
                                                            echo "<td class='return-qty' data-store-id='{$store['id']}'>{$returnitem_qty}</td>";  // Returned Material
                                                            echo "<td class='actual-stock' data-store-id='{$store['id']}'>" . (($item_qty - $exititem_qty) + $returnitem_qty) . "</td>";  // Actual Physical Stock
                                                        }
                                            
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
                                <?php echo form_open(admin_url('inventory/export_receipt_all_store/' . $stores[0]['id'])); ?>
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
                                                echo "<tr class='category-header'><td colspan='2'>{$category['category_name']}</td><td colspan='100'></td></tr>";
                                            
                                                if (!empty($category['items'])) {
                                                    foreach ($category['items'] as $item) {
                                                        echo "<tr class='receipt-row' data-item-id='{$item['item_id']}'>";
                                                        echo "<td>{$sl_no}</td>";
                                                        echo "<td style='white-space:nowrap;'>{$item['description']}</td>";
                                                        echo "<td>{$item['unit']}</td>";
                                                        $total_day_qtys = 0;
                                                        foreach ($day_qty as $day_qtys) {
                                                            if ($day_qtys['item_id'] == $item['item_id']) {
                                                                $total_day_qtys += $day_qtys['item_qty'];
                                                                echo "<td class='receiptday-stock' data-store-id='{$day_qtys['store_id']}'>{$day_qtys['item_qty']}</td>";
                                                            } else {
                                                                $total_day_qtys = 0;   
                                                                echo "<td class='receiptday-stock'>0</td>"; // If no match, display 0
                                                            }                                                   
                                                        }
                                                        echo "<td class='receipttotal-stock'>{$total_day_qtys}</td>";
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

                            <!-- Daily Material Issue Tab Content -->
                            <div id="daily_material_issue" class="tab-pane fade">
                                <?php echo form_open(admin_url('inventory/export_issue_all_store/' . $stores[0]['id'])); ?>
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
                                                echo "<tr class='category-header'><td colspan='2'>{$category['category_name']}</td><td colspan='100'></td></tr>";
                                            
                                                if (!empty($category['items'])) {
                                                    foreach ($category['items'] as $item) {
                                                        echo "<tr class='issue-row' data-item-id='{$item['item_id']}'>";
                                                        echo "<td>{$sl_no}</td>";
                                                        echo "<td style='white-space:nowrap;'>{$item['description']}</td>";
                                                        echo "<td>{$item['unit']}</td>";
                                                        $total_day_issue_qtys = 0;
                                                        foreach ($day_issue_qty as $day_issue_qtys) {
                                                            if ($day_issue_qtys['item_id'] == $item['item_id']) {
                                                                $total_day_issue_qtys += $day_issue_qtys['item_qty'];
                                                                echo "<td class='issueday-stock' data-store-id='{$day_issue_qtys['store_id']}'>{$day_issue_qtys['item_qty']}</td>";
                                                            } else {
                                                                $total_day_issue_qtys = 0;   
                                                                echo "<td class='issueday-stock'>0</td>"; // If no match, display 0
                                                            }                                                   
                                                        }
                                                        echo "<td class='issuetotal-stock'>{$total_day_issue_qtys}</td>";
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
        var projectId = <?php echo $project_id; ?>; // Get the current project/store ID

        // Get the last day of the selected month and year
        var lastDayOfMonth = new Date(selectedYear, selectedMonth, 0).getDate();

        // Get the month name abbreviation (e.g., 'OCT') for the selected month
        var monthAbbreviation = new Date(selectedYear, selectedMonth - 1).toLocaleString('en-us', { month: 'short' }).toUpperCase();

        // Update the text of the table headers for each store with the new month and last day
        <?php foreach ($stores as $store) { ?>
            $('#closing-<?= $store['id']; ?>').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
            $('#receipt-<?= $store['id']; ?>').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
            $('#issue-<?= $store['id']; ?>').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
            $('#return-<?= $store['id']; ?>').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
            $('#actual-<?= $store['id']; ?>').text(`${monthAbbreviation} as on ${lastDayOfMonth}/${selectedMonth}/${selectedYear}`);
        <?php } ?>

        // Send the AJAX request
        $.ajax({
            url: "<?php echo admin_url('inventory/get_overall_stock_data'); ?>", // Call the PHP function
            method: "POST",
            data: {
                filter_month: selectedMonth,
                filter_year: selectedYear,
                project_id: projectId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    var stockData = response.data;
                    // Iterate through each item row and update its store-specific data
                    $('.item-row').each(function() {
                        var itemId = $(this).data('item-id'); // Get item_id from a data attribute in the row
                        
                        // Iterate through the table cells and update store-specific data
                        $(this).find('td').each(function() {
                            var storeId = $(this).data('store-id'); // Get store_id from the table cell

                            // Initialize quantities to 0 by default
                            var entryQty = 0;
                            var exitQty = 0;
                            var returnQty = 0;
                            var closingStock = 0;

                            // If store data exists for the current item, update the quantities
                            if (storeId && stockData[storeId] && stockData[storeId][itemId]) {
                                entryQty = stockData[storeId][itemId].entry_qty ? stockData[storeId][itemId].entry_qty : 0;
                                exitQty = stockData[storeId][itemId].exit_qty ? stockData[storeId][itemId].exit_qty : 0;
                                returnQty = stockData[storeId][itemId].return_qty ? stockData[storeId][itemId].return_qty : 0;
                                
                                // Calculate closing stock
                                closingStock = (parseFloat(entryQty)) - (parseFloat(exitQty)) + (parseFloat(returnQty));
                            }

                            // Update the specific table cell based on the store and item
                            if ($(this).hasClass('closing-stock')) {
                                $(this).text(closingStock);
                            } else if ($(this).hasClass('entry-qty')) {
                                $(this).text(entryQty);
                            } else if ($(this).hasClass('exit-qty')) {
                                $(this).text(exitQty);
                            } else if ($(this).hasClass('return-qty')) {
                                $(this).text(returnQty);
                            } else if ($(this).hasClass('actual-stock')) {
                                $(this).text(closingStock);
                            }
                        });
                    });
                }
            }
        });
    });
    $('select[name="receipt_month"]').change(function() {
        var selectedMonth = $(this).val();
        var selectedYear = new Date().getFullYear(); // You can add a year dropdown as well
        var projectId = <?php echo $project_id; ?>;

        // Send the AJAX request
        $.ajax({
            url: "<?php echo admin_url('inventory/get_all_receipt_data'); ?>", // Call the PHP function
            method: "POST",
            data: {
                filter_month: selectedMonth,
                filter_year: selectedYear,
                project_id: projectId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    var stockData = response.data;

                    // Iterate through each item row
                    $('.receipt-row').each(function() {
                        var itemId = $(this).data('item-id'); // Get item_id from a data attribute in the row
                        var totalItemQty = 0; // Initialize total qty for the item

                        $(this).find('td').each(function() {
                            var storeId = $(this).data('store-id'); // Get store_id from a data attribute in the cell
                            var dayQty = 0;
                            var totalQty = 0;

                            // Check if storeId exists in stockData and itemId exists within that store
                            if (stockData[storeId] && stockData[storeId][itemId]) {
                                dayQty = stockData[storeId][itemId].day_qty ? stockData[storeId][itemId].day_qty : 0;
                                totalQty = stockData[storeId][itemId].total_qty ? stockData[storeId][itemId].total_qty : 0;
                            }

                            // Accumulate total qty for this item across all stores
                            totalItemQty += parseFloat(totalQty);

                            // Update the table cells with the quantities
                            if ($(this).hasClass('receiptday-stock')) {
                                $(this).text(dayQty); // Update the day stock value
                            }

                            if ($(this).hasClass('receipttotal-stock')) {
                                $(this).text(totalItemQty); // Update the total stock value
                            }
                        });
                    });
                }
            }
        });
    });
    $('select[name="issue_month"]').change(function() {
        var selectedMonth = $(this).val();
        var selectedYear = new Date().getFullYear(); // You can add a year dropdown as well
        var projectId = <?php echo $project_id; ?>;

        // Send the AJAX request
        $.ajax({
            url: "<?php echo admin_url('inventory/get_all_issue_data'); ?>", // Call the PHP function
            method: "POST",
            data: {
                filter_month: selectedMonth,
                filter_year: selectedYear,
                project_id: projectId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    var stockData = response.data;

                    // Iterate through each item row
                    $('.issue-row').each(function() {
                        var itemId = $(this).data('item-id'); // Get item_id from a data attribute in the row
                        var totalItemQty = 0; // Initialize total qty for the item

                        $(this).find('td').each(function() {
                            var storeId = $(this).data('store-id'); // Get store_id from a data attribute in the cell
                            var dayQty = 0;
                            var totalQty = 0;

                            // Check if storeId exists in stockData and itemId exists within that store
                            if (stockData[storeId] && stockData[storeId][itemId]) {
                                dayQty = stockData[storeId][itemId].day_qty ? stockData[storeId][itemId].day_qty : 0;
                                totalQty = stockData[storeId][itemId].total_qty ? stockData[storeId][itemId].total_qty : 0;
                            }

                            // Accumulate total qty for this item across all stores
                            totalItemQty += parseFloat(totalQty);

                            // Update the table cells with the quantities
                            if ($(this).hasClass('issueday-stock')) {
                                $(this).text(dayQty); // Update the day stock value
                            }

                            if ($(this).hasClass('issuetotal-stock')) {
                                $(this).text(totalItemQty); // Update the total stock value
                            }
                        });
                    });
                }
            }
        });
    });
});
</script>