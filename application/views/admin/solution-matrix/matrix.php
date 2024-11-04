<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php
    function is_checked($matrix, $location_id, $step_id) {
        foreach ($matrix as $record) {
            if ($record->loc_id == $location_id && $record->step_id == $step_id) {
                return true;
            }
        }
        return false;
    }
    $distinct_projects = array_column($matrix, 'proj_id');
    $distinct_solution_categories = array_column($matrix, 'sol_cat_id');
    // $distinct_projects = array_unique($distinct_projects);
    $distinct_solution_categories = array_unique($distinct_solution_categories);
    $form_suffix="";
    if(!isset($selected_project)){
        if(isset($_POST['proj_id'])){
            $selected_project = $_POST['proj_id'];
            $form_suffix="/".$selected_project;
        }
        else{
            $form_suffix="/".$_POST['proj_id'];
        }
        // else{$selected_project=0;}
    }
    else{
        $form_suffix="/".$selected_project;
    }
    if(isset($_POST['sol_cat_id'])){
        $selected_solution_category = $_POST['sol_cat_id'];
    }
    else{$selected_solution_category=0;}
?>
<div id="wrapper">
    <div class="content">
        <!-- Filter Form -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php
                            $attributes = array('id' => 'matrix-form'); 
                            echo form_open('admin/solution-matrix'.$form_suffix, $attributes); ?>
                            <div class="form-group">
                                <label for="project_filter">Project:</label>
                                <select class="form-control" name="proj_id" id="proj_id">
                                    <option value="">Select a  Project</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?php echo $project->id; ?>" <?php echo $project->id == $selected_project ? 'selected' : ''; ?>>
                                            <?php echo $project->name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sol_cat_id">Solution Category:</label>
                                <select class="form-control" name="sol_cat_id" id="sol_cat_id">
                                    <option value="">Select a  Categoriy</option>
                                    <?php foreach ($solution_categories as $sol_cat): ?>
                                        <option value="<?php echo $sol_cat->sol_cat_id; ?>" <?php echo $sol_cat->sol_cat_id == $selected_solution_category ? 'selected' : ''; ?>>
                                            <?php echo $sol_cat->sol_cat_title; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-right"></i> Show Matrix</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                    <div class="panel_s">
                    <div class="panel-body table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <?php foreach ($steps as $step): ?>
                                        <th><?php echo $step->step_title; ?></th>
                                    <?php endforeach; ?>
                                    <th class="text-center">Work Progress (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($locations as $location): ?>
    <tr>
        <td><?php echo $location->location_title; ?></td>
        <?php foreach ($steps as $step): ?>
            <td>
                <?php
                $checkboxAttributes = array(
                    'type' => 'checkbox',
                    'name' => 'matrix_checkbox',
                    'data-project' => $project_id,
                    'data-location' => $location->loc_id,
                    'data-step' => $step->step_id,
                    'data-solcat' => $sol_cat_id,
                );

                // Check if corresponding record exists in the matrix
                $matrixKey = array_search(
                    array('loc_id' => $location->loc_id, 'step_id' => $step->step_id),
                    array_map(
                        function ($record) {
                            return array('loc_id' => $record->loc_id, 'step_id' => $record->matrix_step_id);
                        },
                        $matrix
                    ),
                    true
                );

                // If the matrix record exists, mark the checkbox as checked
                if ($matrixKey !== false) {
                    $checkboxAttributes['checked'] = 'checked';
                }

                echo form_checkbox($checkboxAttributes);
                ?>
            </td>
        <?php endforeach; ?>
        <td class="text-center" id="work_progress_<?php echo $location->loc_id; ?>">0.00%</td>
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

<?php init_tail(); ?>
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
<!-- <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> -->
<script>
    $(document).ready(function () {
        
        $('input[name="matrix_checkbox"]').on('change', function () {
            // var isChecked = $(this).prop('checked');
            var project = $(this).data('project');
            var location = $(this).data('location');
            var step = $(this).data('step');
            var sol_cat_id = $(this).data('solcat');
            if($(this).prop('checked')){
                isChecked='yes';
            }
            else{
                isChecked='false';
            }
            // alert("ALERT:: "+project +" "+ location +" "+ step +" "+ sol_cat_id);
            
            // Insert or delete records based on checkbox state
            updateMatrixRecord(project, location, step, sol_cat_id, isChecked);

            // Update work progress dynamically
            updateWorkProgress(location);
        });

        function updateMatrixRecord(project, location, step, sol_cat_id, isChecked) {
            
            // alert(project +" "+ location +" "+ step +" "+ sol_cat_id);
            $.ajax({
                url: 'update-matrix',
                type: 'POST',
                data: {
                    proj_id: project,
                    loc_id: location,
                    step_id: step,
                    sol_cat_id: sol_cat_id,
                    isChecked: isChecked
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function updateWorkProgress(location) {
            // Select all checkboxes in the current row
            var checkboxes = $('input[data-location="' + location + '"][name="matrix_checkbox"]');

            // Count the checked checkboxes in the row
            var checkedCount = checkboxes.filter(':checked').length;

            // Calculate the total number of checkboxes in the row
            var totalCount = checkboxes.length;

            // Calculate the work progress percentage
            var workProgress = (checkedCount / totalCount) * 100;

            // Update the UI with the new work progress value
            $('#work_progress_' + location).html(workProgress.toFixed(2) + '%');
        }
        $("#proj_id").on('change', function () {
            var selectedProjId = $(this).val();
            var currentAction = $("#matrix-form").attr('action');
            var newAction = '<?php echo admin_url('solution-matrix');?>' + '/' + selectedProjId;
            $("#matrix-form").attr('action', newAction);
            // console.log('New Form Action:', newAction);
        });

        // $('input[name="matrix_checkbox"]').trigger('change');
        function calculateAndDisplayWorkProgress() {
            $('tbody tr').each(function () {
                var location = $(this).find('td:first-child').text();
                var totalSteps = $(this).find('td input[name="matrix_checkbox"]').length;
                var checkedSteps = $(this).find('td input[name="matrix_checkbox"]:checked').length;
                var workProgress = totalSteps > 0 ? (checkedSteps / totalSteps) * 100 : 0;

                $(this).find('td:last-child').text(workProgress.toFixed(2) + '%');
            });
        }
    calculateAndDisplayWorkProgress();

    });
</script>

</body>
</html>
