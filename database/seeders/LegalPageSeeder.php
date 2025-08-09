<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LegalPage;

class LegalPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LegalPage::create([
            'slug' => 'privacy-policy',
            'title' => 'Privacy Policy',
            'content' => '<p>This is the privacy policy content.</p>',
            'isOn' => true,
        ]);

        LegalPage::create([
            'slug' => 'terms-and-conditions',
            'title' => 'Terms and Conditions',
            'content' => '<p>This is the terms and conditions content.</p>',
            'isOn' => true,
        ]);
    }
}
