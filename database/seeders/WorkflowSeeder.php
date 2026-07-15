<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $steps = [
            [
                'label' => 'Persetujuan VP',
                'state_code' => 'pending_vp', // Gunakan morph label Spatie (biasanya snake_case dari nama class)
                'sort_order' => 1,
            ],
            [
                'label' => 'Persetujuan SVP',
                'state_code' => 'pending_svp',
                'sort_order' => 2,
            ],
            [
                'label' => 'Review Manager TI',
                'state_code' => 'pending_mgr_ti',
                'sort_order' => 3,
            ],
            [
                'label' => 'Disposed BP',
                'state_code' => 'disposed_bp',
                'sort_order' => 4,
            ],
            [
                'label' => 'Pending EA',
                'state_code' => 'pending_ea',
                'sort_order' => 5,
            ],
            [
                'label' => 'Manager Approval',
                'state_code' => 'pending_mngr_approval',
                'sort_order' => 6,
            ],
            [
                'label' => 'Selesai',
                'state_code' => 'completed',
                'sort_order' => 7,
            ],
        ];

        foreach ($steps as $step) {
            Workflow::updateOrCreate(
                ['state_code' => $step['state_code']],
                [
                    'label' => $step['label'],
                    'sort_order' => $step['sort_order']
                ]
            );
        }
    }
}
