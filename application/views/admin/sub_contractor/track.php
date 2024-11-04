<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="track">
    <div class="content">
        <div class="row">
            <div class="tw-mt-12 sm:tw-mt-0 <?php echo isset($track) ? 'col-md-12' : 'col-md-8 col-md-offset-2'; ?>">
                <div class="project-menu-panel tw-my-5">
                    <div class="horizontal-scrollable-tabs tw-bg-white tw-shadow-sm tw-rounded-lg tw-px-3 tw-min-h-0">
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs tw-mb-0 project-tabs nav-tabs-horizontal tw-border-b-0" role="tablist">
                                <li class="project_tab_project_overview tw-py-2"><a href="<?php echo admin_url('sub-contractor/projects/'.$project_id);?>" class="tw-py-2"> 
                                    <i class="fa fa-list"></i> 
                                    <span class="pull-right mleft5">Go to Projects Sub Contractors List </span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel_s">
                <?php echo form_open('admin/boq/update-status/'.$track[0]->id); ?>
                    <div class="panel-heading">
                        <h3>Track Material Issue 
                            <div class="label label-primary">Sub Contractor: <?php echo $sub_contractor[0]->sub_contractor_name?></div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            
                            <table>
                                <tr>
                                    <td>
                                        <strong class="col-lg-12"><?php echo $sub_contractor[0]->sub_contractor_name?></strong>
                                        <strong class="col-lg-12"><?php echo $sub_contractor[0]->sub_contractor_description?></strong>                                      
                                    </td>
                                    <td>
                                        <strong class="col-lg-12">Title: <?php echo $track[0]->boq_title;?></strong>
                                        <span class="col-lg-12 text-small"><?php echo $track[0]->remarks; ?></span>
                                    </td>
                                </tr>
                                
                            </table>
                        </div>
                        <div class="row">
                            
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-striped table-hover" id="boqTable">
                            <thead>
                                <tr>
                                    <th>Sl No.</th>
                                    <th>Product Description</th>
                                    <th class="text-right">Qty</th>
                                    <th class="text-right">Units</th>
                                    <th class="text-right">Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                ini_set("display_errors",1);
                                $total_qty = 0;
                                $total_amount = 0;
                                $i = 1;
                                $current_date = '';

                                foreach ($track_items as $track_item):
                                    // Display a new date row if the movement_date changes
                                    if ($current_date != $track_item->movement_date) {
                                        $current_date = $track_item->movement_date;
                                        echo "<tr><td colspan='6' class='text-left'><strong>Date: " . date('d-m-Y', strtotime($current_date)) . "</strong></td></tr>";
                                        $i = 1; // Reset item counter for new date
                                    }

                                    $total_qty += $track_item->item_qty;
                                    $total_amount += $track_item->rate * $track_item->item_qty;
                                ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><span class="text-dark"><?php echo $track_item->description; ?></span><small class="clearfix text-primary"><?php echo nl2br(htmlspecialchars_decode($track_item->long_description)); ?></small></td>
                                        <td class="text-right"><?php echo $track_item->item_qty; ?></td>
                                        <td class="text-right"><?php echo $track_item->unit; ?></td>
                                        <td class="text-right"><?php echo number_format($track_item->rate, 2); ?></td>
                                    </tr>
                                <?php $i++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<!-- Add JavaScript for Exporting Table Data to CSV -->
<script>
document.getElementById('exportButton').addEventListener('click', function() {
    var table = document.getElementById("boqTable");
    var rows = table.querySelectorAll("tr");
    var csv = [];

    // Loop over table rows and build CSV
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length; j++) {
            // Clean up text and remove unwanted characters
            var text = cols[j].innerText.replace(/,/g, ''); // remove commas from text to avoid CSV issues
            row.push('"' + text + '"');
        }

        csv.push(row.join(",")); // Join columns into a CSV row
    }

    // Create a Blob from CSV data
    var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });

    // Create a download link
    var downloadLink = document.createElement("a");
    downloadLink.download = "boq_data.csv";
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";

    // Append the link to the document and trigger the download
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
});
</script>
</body>
</html>