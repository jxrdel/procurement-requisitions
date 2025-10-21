<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Accounts',
            'Accounts Payable',
            'Accounts Vault',
            'Audiology/School Health',
            'Business Unit II',
            'Central Stores (C-40)',
            'Check Dispatch',
            'Check Staff',
            'Chemistry Foods & Drugs Division',
            'Cheque Processing',
            'Corporate Communications',
            'Cost & Budgeting',
            'Couva Medical and Multi-Training Facility',
            'Directorate of Women\'s Health',
            'Disaster Preparedness Coordinating Unit',
            'Drug Inspectorate Division',
            'Environmental Health',
            'Epidemiology Division',
            'Expanded Programme on Immunization',
            'External Patient Programme',
            'Facilities Management',
            'General Administration',
            'General Administration & Vertical Services',
            'Hansen\'s Disease Control Unit',
            'Health Education',
            'Health Policy Research and Planning',
            'Health Sector Advisory Unit',
            'Health Sector Human Resource Planning',
            'Health Services Support Program',
            'HIV/AIDS Coordinating Unit',
            'Human Resource Development Unit',
            'Human Resources Management Division',
            'ICT',
            'Industrial Relations/Employee Relations (Human Resource)',
            'Insect Vector Control Division',
            'Internal Audit',
            'International Cooperation Desk',
            'JSAC Record Management Facility',
            'Legal Services',
            'Medical Section',
            'Ministry of Health, Head Office',
            'National Alcohol and Drug Abuse Prevention Programme',
            'National Blood Transfusion Services',
            'National Breastfeeding Coordinating Unit',
            'National Emergency Ambulance Service Authority',
            'Non-Communicable Diseases',
            'Occupational Safety Health',
            'Office Management',
            'Office of the Chief Medical Officer',
            'Office of the Deputy Permanent Secretary',
            'Office of the Minister of Health',
            'Office of the Permanent Secretary',
            'Office of the Senior Health System Adviser',
            'Permanent Secretary Secretariat',
            'PMO\'s Office',
            'Population Programme Unit',
            'Procurement Unit',
            'Project Management Unit',
            'Quality Management',
            'Queen\'s Park Counseling Centre & Clinic',
            'Recruitment Unit',
            'Registry',
            'Special Programmes and Services Unit',
            'Terminal Benefits Section',
            'Tobacco Control Unit',
            'Tradezone',
            'Trinidad Public Health Laboratory',
            'Vertical Services',
            'Veterinary Public Health',
            'Vote Control',
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['name' => $department],
                ['name' => $department]
            );
        }

        $this->command->info('Departments seeded successfully!');
    }
}
