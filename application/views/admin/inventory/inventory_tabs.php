<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="horizontal-scrollable-tabs tw-bg-white tw-shadow-sm tw-rounded-lg tw-px-3 tw-min-h-0">
    <div class="scroller arrow-left !tw-py-[18px] tw-mt-px tw-border-0"><i class="fa fa-angle-left"></i></div>
    <div class="scroller arrow-right !tw-py-[18px] tw-mt-px tw-border-0"><i class="fa fa-angle-right"></i></div>
    <div class="horizontal-tabs">
        <ul class="nav nav-tabs tw-mb-0 project-tabs nav-tabs-horizontal tw-border-b-0" role="tablist">
            <li class="project_tab_project_overview tw-py-2"><a href="<?php echo admin_url('inventory/'.$project_id);?>" class="tw-py-2"> 
                <i class="fa fa-list"></i> 
                <span class="pull-right mleft5">List Inventory </span></a>
            </li>
            <li class="project_tab_project_overview tw-py-2"><a href="<?php echo admin_url('inventory/categories/'.$project_id);?>" class="tw-py-2"> 
                <i class="fa fa-solid fa-chart-gantt menu-icon"></i> 
                <span class="pull-right mleft5">Categories </span></a>
            </li>
            <li class="project_tab_project_overview tw-py-2"><a href="<?php echo admin_url('inventory/stores/'.$project_id)?>" class="tw-py-2"> 
                <i class="fa fa-solid fa-chart-gantt menu-icon"></i>
                <span class="pull-right mleft5">Stores </span></a>
            </li>
            <li class="project_tab_project_overview tw-py-2"><a href="<?php echo admin_url('inventory/attach_categories/'.$project_id)?>" class="tw-py-2"> 
                <i class="fa fa-solid fa-chart-gantt menu-icon"></i> 
                <span class="pull-right mleft5">Attach categories to items </span></a>
            </li>            
        </ul>
    </div>
</div>