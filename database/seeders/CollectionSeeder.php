<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            [
                'value' => 'Русский',
                'type' => 'language',
            ],
            [
                'value' => 'Английский',
                'type' => 'language',
            ],
            [
                'value' => 'Татарский',
                'type' => 'language',
            ],
            [
                'value' => 'done',
                'type' => 'ticket_statuses'
            ],
            [
                'value' => 'pending',
                'type' => 'ticket_statuses'
            ],
            [
                'value' => 'new',
                'type' => 'ticket_statuses'
            ],
            [
                'value' => 'archive',
                'type' => 'ticket_statuses'
            ],
            [
                'value' => 'question',
                'type' => 'ticket_types'
            ],
            [
                'value' => 'buy',
                'type' => 'ticket_types'
            ],
            [
                'value' => 'preview',
                'type' => 'ticket_types'
            ],
        ];

        Collection::insert($collections);
    }
}
