<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/import/App_import.php');

class Import_items_sub_contractor extends App_import
{
    protected $notImportableFields = ['id'];

    protected $requiredFields = ['description', 'unit'];

    protected $boq_id;

    public function __construct()
    {
        $this->addItemsGuidelines();

        parent::__construct();
    }

    public function setBoqId($boq_id)
    {
        $this->boq_id = $boq_id; // Assign the boq_id passed from the controller
    }

    public function perform()
    {
        $this->initialize();

        $databaseFields = $this->getImportableDatabaseFields();
        $totalDatabaseFields = count($databaseFields);

        foreach ($this->getRows() as $rowNumber => $row) {
            $insert = [];
            $quantity = 0; // Initialize quantity
            $rate = 0; // Initialize rate
            $tax = null; // Initialize tax
            $tax2 = null; // Initialize tax2

            for ($i = 0; $i < $totalDatabaseFields; $i++) {
                $row[$i] = $this->checkNullValueAddedByUser($row[$i]);

                // Handle specific fields
                if ($databaseFields[$i] == 'description' && $row[$i] == '') {
                    $row[$i] = '/';
                } elseif (startsWith($databaseFields[$i], 'Sub_Cont_Unit_Rate')) {
                    $rate = is_numeric($row[$i]) ? $row[$i] : 0; // Ensure rate is numeric
                    $insert['rate'] = $rate; // Add rate to insert array
                } elseif ($databaseFields[$i] == 'qty_per_KM') {
                    $quantity = is_numeric($row[$i]) ? $row[$i] : 1; // Default to 1 if not numeric
                } elseif ($databaseFields[$i] == 'LOA_BOQ_Sl_No') {
                    $LOA_BOQ_Sl_No = is_numeric($row[$i]) ? $row[$i] : 1; // Default to 1 if not numeric
                } elseif ($databaseFields[$i] == 'Total_LOA_Qty') {
                    $Total_LOA_Qty = is_numeric($row[$i]) ? $row[$i] : 1; // Default to 1 if not numeric
                } elseif ($databaseFields[$i] == 'Sub_Cont_Qty') {
                    $Sub_Cont_Sub_Cont_Qty = is_numeric($row[$i]) ? $row[$i] : 1; // Default to 1 if not numeric
                } else {
                    $insert[$databaseFields[$i]] = $row[$i];
                }
            }

            // Apply custom trimming for insert values
            $insert = $this->trimInsertValues($insert);
            // Check if there's data to insert
            if (count($insert) > 0) {
                $this->incrementImported();

                $item_id = null;

                if (!$this->isSimulation()) {
                    // Insert item into tblitems
                    $this->ci->db->insert(db_prefix() . 'items', $insert);
                    $item_id = $this->ci->db->insert_id();

                    // Insert into tblboq_details with quantity and rate
                    $boq_details = [
                        'item_id'    => $item_id,
                        'qty_km'   => $quantity,
                        'loa_no'   => $LOA_BOQ_Sl_No,
                        'loa_qty'   => $Total_LOA_Qty,
                        'sub_contractor_qty'   => $Sub_Cont_Sub_Cont_Qty,
                        'sub_contractor_price'   => $rate,
                        'boq_id'     => $this->boq_id, // Use dynamic BOQ ID
                    ];

                    // Ensure sub_contractor_price is not null
                    if (is_null($boq_details['sub_contractor_price'])) {
                        $boq_details['sub_contractor_price'] = 0;
                    }

                    $this->ci->db->insert(db_prefix() . 'sub_contractorboq_details', $boq_details);
                } else {
                    $this->simulationData[$rowNumber] = $this->formatValuesForSimulation($insert);
                }

                // Handle custom fields insert if applicable
                $this->handleCustomFieldsInsert($item_id, $row, $i, $rowNumber, 'items_pr');
            }

            // Stop simulation if the maximum number of rows is reached
            if ($this->isSimulation() && $rowNumber >= $this->maxSimulationRows) {
                break;
            }
        }
    }

    public function formatFieldNameForHeading($field)
    {
        $this->ci->load->model('currencies_model');

        if (strtolower($field) == 'group_id') {
            return 'Group';
        } elseif (startsWith($field, 'rate')) {
            $str = 'Rate - ';
            // Base currency
            if ($field == 'rate') {
                $str .= $this->ci->currencies_model->get_base_currency()->name;
            } else {
                $str .= $this->ci->currencies_model->get(strafter($field, 'rate_currency_'))->name;
            }

            return $str;
        }

        return parent::formatFieldNameForHeading($field);
    }

    protected function failureRedirectURL()
    {
        return admin_url('invoice_items/import');
    }

    private function addItemsGuidelines()
    {
        $this->addImportGuidelinesInfo('In the column <b>Tax</b> and <b>Tax2</b>, you <b>must</b> add either the <b>TAX NAME or the TAX ID</b>, which you can get them by navigating to <a href="' . admin_url('taxes') . '" target="_blank">Setup->Finance->Taxes</a>.');
        $this->addImportGuidelinesInfo('In the column <b>Group</b>, you <b>must</b> add either the <b>GROUP NAME or the GROUP ID</b>, which you can get them by clicking <a href="' . admin_url('invoice_items?groups_modal=true') . '" target="_blank">here</a>.');
    }

    private function formatValuesForSimulation($values)
    {
        foreach ($values as $column => $val) {
            if ($column == 'group_id' && !empty($val) && is_numeric($val)) {
                $group = $this->getGroupBy('id', $val);
                if ($group) {
                    $values[$column] = $group->name;
                }
            } elseif (($column == 'tax' || $column == 'tax2') && !empty($val) && is_numeric($val)) {
                $tax = $this->getTaxBy('id', $val);
                if ($tax) {
                    $values[$column] = $tax->name . ' (' . $tax->taxrate . '%)';
                }
            }
        }

        return $values;
    }

    private function getTaxBy($field, $idOrName)
    {
        $this->ci->db->where($field, $idOrName);

        return $this->ci->db->get(db_prefix().'taxes')->row();
    }

    private function getGroupBy($field, $idOrName)
    {
        $this->ci->db->where($field, $idOrName);

        return $this->ci->db->get(db_prefix().'items_groups')->row();
    }

    private function taxValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $tax   = $this->getTaxBy('name', $value);
                $value = $tax ? $tax->id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }

    private function groupValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $group = $this->getGroupBy('name', $value);
                $value = $group ? $group->id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }
}
