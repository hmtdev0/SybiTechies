<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            ['name' => 'Clinic Management System', 'category' => 'ERP', 'thumbnail' => 'clinic-management.jpg', 'description' => 'End-to-end clinic operations: appointments, patients, billing and reports.', 'tech' => ['Laravel', 'MySQL', 'Bootstrap 5']],
            ['name' => 'School Management ERP', 'category' => 'ERP', 'thumbnail' => 'school-erp.jpg', 'description' => 'Complete academic ERP with admissions, attendance, fees and results.', 'tech' => ['Laravel', 'JavaScript', 'MySQL']],
            ['name' => 'Restaurant POS', 'category' => 'Desktop', 'thumbnail' => 'restaurant-pos.jpg', 'description' => 'Fast point-of-sale with kitchen display, tables and sales analytics.', 'tech' => ['Flutter', 'PHP']],
            ['name' => 'Inventory Management System', 'category' => 'Web', 'thumbnail' => 'inventory-management.jpg', 'description' => 'Real-time stock tracking, purchasing, warehouses and low-stock alerts.', 'tech' => ['Laravel', 'MySQL', 'Bootstrap 5']],
            ['name' => 'Hospital Management Software', 'category' => 'ERP', 'thumbnail' => 'hospital-management.jpg', 'description' => 'Multi-department hospital suite: OPD, IPD, pharmacy, labs and billing.', 'tech' => ['Laravel', 'Python', 'MySQL']],
            ['name' => 'Customer Support Live Chat', 'category' => 'Web', 'thumbnail' => 'live-chat.jpg', 'description' => 'Real-time live chat and ticketing to delight and retain customers.', 'tech' => ['JavaScript', 'PHP']],
            ['name' => 'Car Rental Management System', 'category' => 'Web', 'thumbnail' => 'car-rental.jpg', 'description' => 'Fleet, bookings, contracts and online payments in one dashboard.', 'tech' => ['Laravel', 'JavaScript', 'MySQL']],
            ['name' => 'Real Estate CRM', 'category' => 'CRM', 'thumbnail' => 'real-estate-crm.jpg', 'description' => 'Lead capture, property listings and sales pipeline automation.', 'tech' => ['Laravel', 'Bootstrap 5', 'MySQL']],
            ['name' => 'Accounting Software', 'category' => 'Desktop', 'thumbnail' => 'accounting-software.jpg', 'description' => 'Invoicing, ledgers, tax and financial reporting made effortless.', 'tech' => ['Python', 'MySQL']],
            ['name' => 'HR & Payroll System', 'category' => 'ERP', 'thumbnail' => 'hr-payroll.jpg', 'description' => 'Employees, attendance, leaves and automated payroll processing.', 'tech' => ['Laravel', 'JavaScript', 'MySQL']],
            ['name' => 'Food Delivery App', 'category' => 'Mobile', 'thumbnail' => 'restaurant-pos.jpg', 'description' => 'Customer & rider apps with live order tracking and payments.', 'tech' => ['Flutter', 'MySQL']],
            ['name' => 'Property Finder App', 'category' => 'Mobile', 'thumbnail' => 'real-estate-crm.jpg', 'description' => 'On-the-go property search, tours and agent messaging.', 'tech' => ['Flutter', 'PHP']],
        ];

        foreach ($projects as $i => $project) {
            $category = ProjectCategory::query()->where('name', $project['category'])->first();

            $record = Project::query()->updateOrCreate(
                ['name' => $project['name']],
                [
                    'project_category_id' => $category?->id,
                    'thumbnail' => 'assets/images/projects/'.$project['thumbnail'],
                    'description' => $project['description'],
                    'display_order' => $i + 1,
                    'status' => true,
                    'is_featured' => $i < 3,
                ]
            );

            $techIds = Technology::query()->whereIn('name', $project['tech'])->pluck('id');
            $record->technologies()->sync($techIds);
        }
    }
}
