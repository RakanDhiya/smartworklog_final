<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    protected array $types = [
        'Meeting' => 'Pertemuan internal maupun dengan client.',
        'Research' => 'Riset hukum terkait kasus.',
        'Drafting' => 'Penyusunan dokumen hukum.',
        'Court' => 'Persidangan/kehadiran di pengadilan.',
        'Client Consultation' => 'Konsultasi dengan client.',
        'Document Review' => 'Peninjauan dokumen.',
        'Administration' => 'Pekerjaan administratif terkait kasus.',
        'Other' => 'Aktivitas lain yang tidak masuk kategori di atas.',
    ];

    public function run(): void
    {
        foreach ($this->types as $name => $description) {
            ActivityType::firstOrCreate(['name' => $name], ['description' => $description]);
        }

        $this->command->info('Activity types seeded: '.count($this->types).' items.');
    }
}