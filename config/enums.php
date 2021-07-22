<?php
/* Config For Statis Enum Type */
return [
    'currency' => [
        'rp'        => "Rp",
        'dollar'    => "$",
        'yen'       => "¥",
        'euro'      => "€"
    ],
    'rule' => [
        'adb'       => "ADB",
        'gde'       => "GDE"
    ],
    'adb_guideline' => [
        'goods'             => "Goods",
        'works 1e'          => "Works 1E",
        'works 2e'          => "Works 2E",
        'works and plant'   => "Works and Plant",
        'pmc'               => "PMC",
        'pilot plant'       => "Pilot Plant",
        'rig inspection'    => "Rig Inspection",
    ],
    'warehouse_type' => [
        'internal'          => "Internal",
        'virtual'          => "Virtual",
        'vondor'          => "Vendor"
    ],
    'warehouse_status' => [
        'active'          => "Active",
        'non active'          => "Non Active"
    ],
    'is_show' => [
        'YES'   => "YES",
        'NO'    => "NO"
    ],
    'dc_type' => [
        'Engineering'   => "Engineering",
        'Subsurface'    => "Subsurface",
        'Safeguard'    => "Safeguard",
        'General'    => "General",
        'Letter'    => "Letter",
        'Asset'    => "Asset"
    ],
    'contract_type' => [
        'product'   => "Product",
        'service'    => "Service",
        'product & service' => "Product & Service",
    ],
    'machine_type'  => [
        'CHECKIN'       => 'Check In',
        'CHECKOUT'      => 'Check Out'
    ],
    'uom_type'      => [
        'REFERENCE'     => 'Reference',
        'BIGGERTHAN'    => 'Bigger Than',
        'SMALLERTHAN'   => 'Smaller Than',
    ],
    'status_receipt'    => [
        'WAITING'       => 'Waiting',
        'INPROGRESS'    => 'In Progress',
        'COMPLETED'     => 'Completed',
        'DELIVERY'      => 'Delivery',
    ],
    'status_adjustment'    => [
        'DRAFT'         => 'Draft',
        'WAITING'       => 'Waiting Approval',
        'APPROVED'      => 'Approved',
        'REJECTED'      => 'Rejected'
    ],
    'status_global'     => [
        'draft'             => 'Draft',
        'waitingapproval'   => 'Waiting Approval',
        'approved'          => 'Approved',
    ],
    'status_w_rejected' => [
        'DRAFT'         => 'Draft',
        'WAITING'       => 'Waiting Approval',
        'APPROVED'      => 'Approved',
        'REJECTED'      => 'Rejected',
    ],
    'global_status'     => [
        'DRAFT'         => ['text' => 'Draft', 'badge' => 'secondary', 'isShow' => true, 'isLocked' => false, 'approval' => false],
        'WAITING'       => ['text' => 'Waiting', 'badge' => 'warning', 'isShow' => true, 'isLocked' => false, 'approval' => true],
        'APPROVED'      => ['text' => 'Approved', 'badge' => 'success', 'isShow' => true, 'isLocked' => true, 'approval' => false],
        'REJECTED'      => ['text' => 'Rejected', 'badge' => 'maroon', 'isShow' => true, 'isLocked' => true, 'approval' => false],
        'ARCHIVED'      => ['text' => 'Archived', 'badge' => 'maroon', 'isShow' => false, 'isLocked' => true, 'approval' => false],
        'REVISED'       => ['text' => 'Revised', 'badge' => 'info', 'isShow' => false, 'isLocked' => false, 'approval' => false],
    ]
];