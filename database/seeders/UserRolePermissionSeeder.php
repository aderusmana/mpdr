<?php

namespace Database\Seeders;

use App\Models\Master\Department;
use App\Models\Master\Level;
use App\Models\Master\Position;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Permissions (hanya yang aktif digunakan di sistem)
        $permissions = array (
  0 => 'create permission',
  1 => 'create role',
  2 => 'create user',
  3 => 'delete permission',
  4 => 'delete role',
  5 => 'delete user',
  6 => 'update permission',
  7 => 'update role',
  8 => 'update user',
  9 => 'view approval mpdr',
  10 => 'view approval pre',
  11 => 'view approver mpdr',
  12 => 'view approver pre',
  13 => 'view create mpdr',
  14 => 'view create pre',
  15 => 'view delete mpdr',
  16 => 'view delete pre',
  17 => 'view direct gm mpdr',
  18 => 'view direct head snm pre',
  19 => 'view edit mpdr',
  20 => 'view edit pre',
  21 => 'view mpdr',
  22 => 'view permission',
  23 => 'view pre',
  24 => 'view resend mpdr',
  25 => 'view resend pre',
  26 => 'view role',
  27 => 'view user',
);

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // 2. Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $adminRole      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole       = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $approverRole   = Role::firstOrCreate(['name' => 'approver', 'guard_name' => 'web']);
        $gmRole         = Role::firstOrCreate(['name' => 'gm', 'guard_name' => 'web']);
        $staffRole      = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        // Assign all permissions to super-admin
        $superAdminRole->syncPermissions($permissions);

        // Assign management permissions to admin
        $adminRole->syncPermissions([
            'view role',
            'create role',
            'update role',
            'view permission',
            'create permission',
            'view user',
            'create user',
            'update user',
        ]);

        // 3. Departments
        $departments = array (
  0 => 
  array (
    'id' => 2,
    'name' => 'Finance Admin',
  ),
  1 => 
  array (
    'id' => 3,
    'name' => 'HCD',
  ),
  2 => 
  array (
    'id' => 4,
    'name' => 'Manufacturing',
  ),
  3 => 
  array (
    'id' => 5,
    'name' => 'QM',
  ),
  4 => 
  array (
    'id' => 6,
    'name' => 'R&D',
  ),
  5 => 
  array (
    'id' => 7,
    'name' => 'Sales & Marketing',
  ),
  6 => 
  array (
    'id' => 11,
    'name' => 'General Manager',
  ),
);

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['id' => $dept['id']],
                ['name' => $dept['name']]
            );
        }

        // 4. Levels
        $levels = array (
  0 => 
  array (
    'id' => 1,
    'name' => 'I',
    'slug' => 'i',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'II',
    'slug' => 'ii',
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'III',
    'slug' => 'iii',
  ),
  3 => 
  array (
    'id' => 4,
    'name' => 'IV',
    'slug' => 'iv',
  ),
  4 => 
  array (
    'id' => 5,
    'name' => 'V',
    'slug' => 'v',
  ),
  5 => 
  array (
    'id' => 6,
    'name' => 'VI',
    'slug' => 'vi',
  ),
  6 => 
  array (
    'id' => 7,
    'name' => 'VII',
    'slug' => 'vii',
  ),
  7 => 
  array (
    'id' => 8,
    'name' => 'Developer',
    'slug' => 'developer',
  ),
  8 => 
  array (
    'id' => 17,
    'name' => 'VIII',
    'slug' => 'viii',
  ),
);

        foreach ($levels as $lvl) {
            Level::updateOrCreate(
                ['id' => $lvl['id']],
                ['name' => $lvl['name'], 'slug' => $lvl['slug']]
            );
        }

        // 5. Positions
        $positions = array (
  0 => 
  array (
    'id' => 2,
    'name' => 'General Manager',
    'slug' => 'general-manager',
    'department_id' => 2,
    'level_id' => 17,
  ),
  1 => 
  array (
    'id' => 3,
    'name' => 'Department Head Finance',
    'slug' => 'department-head-finance',
    'department_id' => 2,
    'level_id' => 7,
  ),
  2 => 
  array (
    'id' => 4,
    'name' => 'Assistant Manager MIS',
    'slug' => 'assistant-manager-mis',
    'department_id' => 2,
    'level_id' => 5,
  ),
  3 => 
  array (
    'id' => 5,
    'name' => 'Manager Accounting & Tax',
    'slug' => 'manager-accounting-tax',
    'department_id' => 2,
    'level_id' => 5,
  ),
  4 => 
  array (
    'id' => 6,
    'name' => 'Manager Bussiness Opr. Control',
    'slug' => 'manager-bussiness-opr-control',
    'department_id' => 2,
    'level_id' => 5,
  ),
  5 => 
  array (
    'id' => 7,
    'name' => 'IT Support',
    'slug' => 'it-support',
    'department_id' => 2,
    'level_id' => 3,
  ),
  6 => 
  array (
    'id' => 8,
    'name' => 'Web Developer',
    'slug' => 'web-developer',
    'department_id' => 2,
    'level_id' => 3,
  ),
  7 => 
  array (
    'id' => 16,
    'name' => 'Manager - Creative Bussines Tranformation',
    'slug' => 'manager-creative-bussines-tranformation',
    'department_id' => 2,
    'level_id' => 6,
  ),
  8 => 
  array (
    'id' => 17,
    'name' => 'Senior Advisor',
    'slug' => 'senior-advisor',
    'department_id' => 4,
    'level_id' => 7,
  ),
  9 => 
  array (
    'id' => 18,
    'name' => 'Manager-Batch Refinery',
    'slug' => 'manager-batch-refinery',
    'department_id' => 4,
    'level_id' => 6,
  ),
  10 => 
  array (
    'id' => 19,
    'name' => 'Manager - Marsho Plant',
    'slug' => 'manager-marsho-plant',
    'department_id' => 4,
    'level_id' => 6,
  ),
  11 => 
  array (
    'id' => 24,
    'name' => 'Manager - Industrial Commercial',
    'slug' => 'manager-industrial-commercial',
    'department_id' => 7,
    'level_id' => 6,
  ),
  12 => 
  array (
    'id' => 25,
    'name' => 'Manager - Key Account',
    'slug' => 'manager-key-account',
    'department_id' => 7,
    'level_id' => 6,
  ),
  13 => 
  array (
    'id' => 26,
    'name' => 'Asst. Mgr - Industrial Commercial',
    'slug' => 'asst-mgr-industrial-commercial',
    'department_id' => 7,
    'level_id' => 5,
  ),
  14 => 
  array (
    'id' => 27,
    'name' => 'Manager - Technical & Application',
    'slug' => 'manager-technical-application',
    'department_id' => 7,
    'level_id' => 6,
  ),
  15 => 
  array (
    'id' => 29,
    'name' => 'Regional Sales Manager - Head of East Zone',
    'slug' => 'regional-sales-manager-head-of-east-zone',
    'department_id' => 7,
    'level_id' => 6,
  ),
  16 => 
  array (
    'id' => 30,
    'name' => 'Dept. Head - Sales & Marketing',
    'slug' => 'dept-head-sales-marketing',
    'department_id' => 7,
    'level_id' => 7,
  ),
  17 => 
  array (
    'id' => 31,
    'name' => 'Data Analis & Sales Support Specialist Supervisor',
    'slug' => 'data-analis-sales-support-specialist-supervisor',
    'department_id' => 7,
    'level_id' => 4,
  ),
  18 => 
  array (
    'id' => 32,
    'name' => 'Staff',
    'slug' => 'staff',
    'department_id' => 2,
    'level_id' => 3,
  ),
  19 => 
  array (
    'id' => 36,
    'name' => 'Dept. Head - Quality Mgmt & HSE',
    'slug' => 'dept-head-quality-mgmt-hse',
    'department_id' => 5,
    'level_id' => 7,
  ),
  20 => 
  array (
    'id' => 37,
    'name' => 'Marketing Manager',
    'slug' => 'marketing-manager',
    'department_id' => 7,
    'level_id' => 6,
  ),
  21 => 
  array (
    'id' => 39,
    'name' => 'Brand Ambasador Gold Bullion',
    'slug' => 'brand-ambasador-gold-bullion',
    'department_id' => 7,
    'level_id' => 6,
  ),
  22 => 
  array (
    'id' => 40,
    'name' => 'Assistant Marketing Manager',
    'slug' => 'assistant-marketing-manager',
    'department_id' => 7,
    'level_id' => 5,
  ),
  23 => 
  array (
    'id' => 42,
    'name' => 'QA Manager',
    'slug' => 'qa-manager',
    'department_id' => 5,
    'level_id' => 6,
  ),
  24 => 
  array (
    'id' => 43,
    'name' => 'R & D Assistant Manager',
    'slug' => 'r-d-assistant-manager',
    'department_id' => 6,
    'level_id' => 5,
  ),
  25 => 
  array (
    'id' => 45,
    'name' => 'R&D Packaging & Regulatory',
    'slug' => 'rd-packaging-regulatory',
    'department_id' => 6,
    'level_id' => 3,
  ),
  26 => 
  array (
    'id' => 46,
    'name' => 'R&D Application & Sensory',
    'slug' => 'rd-application-sensory',
    'department_id' => 6,
    'level_id' => 3,
  ),
  27 => 
  array (
    'id' => 47,
    'name' => 'Manager - R & D',
    'slug' => 'manager-r-d',
    'department_id' => 6,
    'level_id' => 6,
  ),
  28 => 
  array (
    'id' => 60,
    'name' => 'Key Account Supervisor',
    'slug' => 'key-account-supervisor',
    'department_id' => 7,
    'level_id' => 4,
  ),
  29 => 
  array (
    'id' => 64,
    'name' => 'Asst. Mgr - Product Development',
    'slug' => 'asst-mgr-product-development',
    'department_id' => 7,
    'level_id' => 5,
  ),
  30 => 
  array (
    'id' => 65,
    'name' => 'Technician Analist',
    'slug' => 'technician-analist',
    'department_id' => 5,
    'level_id' => 3,
  ),
  31 => 
  array (
    'id' => 66,
    'name' => 'Head of Region 4a (Manado, Gorontalo, Jayapura, Sorong, Ternate & Halmahera, Timika)',
    'slug' => 'head-of-region-4a-manado-gorontalo-jayapura-sorong-ternate-halmahera-timika',
    'department_id' => 7,
    'level_id' => 5,
  ),
  32 => 
  array (
    'id' => 67,
    'name' => 'Head of Area Pontianak',
    'slug' => 'head-of-area-pontianak',
    'department_id' => 7,
    'level_id' => 4,
  ),
  33 => 
  array (
    'id' => 69,
    'name' => 'Head of Region 3b (Sumbar & Sumut)',
    'slug' => 'head-of-region-3b-sumbar-sumut',
    'department_id' => 7,
    'level_id' => 5,
  ),
  34 => 
  array (
    'id' => 70,
    'name' => 'Head of Area (Jabodetabek Project UKM)',
    'slug' => 'head-of-area-jabodetabek-project-ukm',
    'department_id' => 7,
    'level_id' => 4,
  ),
  35 => 
  array (
    'id' => 71,
    'name' => 'Head of Region 4b (Banjarmasin, Samarinda, Kendari, Makasar, Ambon & Palu)',
    'slug' => 'head-of-region-4b-banjarmasin-samarinda-kendari-makasar-ambon-palu',
    'department_id' => 7,
    'level_id' => 5,
  ),
  36 => 
  array (
    'id' => 72,
    'name' => 'Head of Area Palembang',
    'slug' => 'head-of-area-palembang',
    'department_id' => 7,
    'level_id' => 4,
  ),
  37 => 
  array (
    'id' => 73,
    'name' => 'Head of Area Cirebon',
    'slug' => 'head-of-area-cirebon',
    'department_id' => 7,
    'level_id' => 4,
  ),
  38 => 
  array (
    'id' => 74,
    'name' => 'Head of Region 2a',
    'slug' => 'head-of-region-2a',
    'department_id' => 7,
    'level_id' => 5,
  ),
  39 => 
  array (
    'id' => 75,
    'name' => 'Head Of Region Sumatera Utara & Aceh',
    'slug' => 'head-of-region-sumatera-utara-aceh',
    'department_id' => 7,
    'level_id' => 5,
  ),
  40 => 
  array (
    'id' => 76,
    'name' => 'Head of Area Palu',
    'slug' => 'head-of-area-palu',
    'department_id' => 7,
    'level_id' => 4,
  ),
  41 => 
  array (
    'id' => 77,
    'name' => 'Head Of Area Bandung',
    'slug' => 'head-of-area-bandung',
    'department_id' => 7,
    'level_id' => 4,
  ),
  42 => 
  array (
    'id' => 78,
    'name' => 'Head Of Area Jak-Bar & Jak-Utara',
    'slug' => 'head-of-area-jak-bar-jak-utara',
    'department_id' => 7,
    'level_id' => 4,
  ),
  43 => 
  array (
    'id' => 79,
    'name' => 'Sales Coordinator Pekanbaru',
    'slug' => 'sales-coordinator-pekanbaru',
    'department_id' => 7,
    'level_id' => 3,
  ),
  44 => 
  array (
    'id' => 80,
    'name' => 'Sales Coordinator Balikpapan',
    'slug' => 'sales-coordinator-balikpapan',
    'department_id' => 7,
    'level_id' => 3,
  ),
  45 => 
  array (
    'id' => 81,
    'name' => 'Sales Coodinator Sumut & Aceh',
    'slug' => 'sales-coodinator-sumut-aceh',
    'department_id' => 7,
    'level_id' => 3,
  ),
  46 => 
  array (
    'id' => 82,
    'name' => 'Head Of Area Tangsel & Tangerang Kota',
    'slug' => 'head-of-area-tangsel-tangerang-kota',
    'department_id' => 7,
    'level_id' => 4,
  ),
  47 => 
  array (
    'id' => 83,
    'name' => 'Field Technical Supervisor Reg 4B',
    'slug' => 'field-technical-supervisor-reg-4b',
    'department_id' => 7,
    'level_id' => 4,
  ),
  48 => 
  array (
    'id' => 84,
    'name' => 'Field Technical Supervisor Reg 3C',
    'slug' => 'field-technical-supervisor-reg-3c',
    'department_id' => 7,
    'level_id' => 4,
  ),
  49 => 
  array (
    'id' => 85,
    'name' => 'Technical Coordinator Reg. 4A',
    'slug' => 'technical-coordinator-reg-4a',
    'department_id' => 7,
    'level_id' => 3,
  ),
  50 => 
  array (
    'id' => 86,
    'name' => 'Indutrial Commercial Supervisor',
    'slug' => 'indutrial-commercial-supervisor',
    'department_id' => 7,
    'level_id' => 4,
  ),
  51 => 
  array (
    'id' => 88,
    'name' => 'Field Technical Coordinator Reg. 2D',
    'slug' => 'field-technical-coordinator-reg-2d',
    'department_id' => 7,
    'level_id' => 3,
  ),
  52 => 
  array (
    'id' => 89,
    'name' => 'Technical Supervisor Reg 2C',
    'slug' => 'technical-supervisor-reg-2c',
    'department_id' => 7,
    'level_id' => 4,
  ),
  53 => 
  array (
    'id' => 90,
    'name' => 'Sales Coordinator Kupang',
    'slug' => 'sales-coordinator-kupang',
    'department_id' => 7,
    'level_id' => 3,
  ),
  54 => 
  array (
    'id' => 91,
    'name' => 'Head Of Area Sumatera Barat',
    'slug' => 'head-of-area-sumatera-barat',
    'department_id' => 7,
    'level_id' => 4,
  ),
  55 => 
  array (
    'id' => 92,
    'name' => 'Sales Coordinator Area Madura',
    'slug' => 'sales-coordinator-area-madura',
    'department_id' => 7,
    'level_id' => 3,
  ),
  56 => 
  array (
    'id' => 93,
    'name' => 'Head Of Area Manado',
    'slug' => 'head-of-area-manado',
    'department_id' => 7,
    'level_id' => 4,
  ),
  57 => 
  array (
    'id' => 94,
    'name' => 'Head Of Region Bali, NTT, NTB',
    'slug' => 'head-of-region-bali-ntt-ntb',
    'department_id' => 7,
    'level_id' => 5,
  ),
  58 => 
  array (
    'id' => 95,
    'name' => 'Field Technical Coordinator Bandung',
    'slug' => 'field-technical-coordinator-bandung',
    'department_id' => 7,
    'level_id' => 3,
  ),
  59 => 
  array (
    'id' => 96,
    'name' => 'Sales Coordinator Jayapura',
    'slug' => 'sales-coordinator-jayapura',
    'department_id' => 7,
    'level_id' => 3,
  ),
  60 => 
  array (
    'id' => 97,
    'name' => 'Sales Coordinator Jakarta Timur & Utara',
    'slug' => 'sales-coordinator-jakarta-timur-utara',
    'department_id' => 7,
    'level_id' => 3,
  ),
  61 => 
  array (
    'id' => 98,
    'name' => 'Head of Area Jember & Bnywgi',
    'slug' => 'head-of-area-jember-bnywgi',
    'department_id' => 7,
    'level_id' => 4,
  ),
  62 => 
  array (
    'id' => 99,
    'name' => 'Sales Coordinator Timika',
    'slug' => 'sales-coordinator-timika',
    'department_id' => 7,
    'level_id' => 3,
  ),
  63 => 
  array (
    'id' => 100,
    'name' => 'Industrial Supervisor Jawa Timur',
    'slug' => 'industrial-supervisor-jawa-timur',
    'department_id' => 7,
    'level_id' => 4,
  ),
  64 => 
  array (
    'id' => 101,
    'name' => 'Field Technical',
    'slug' => 'field-technical',
    'department_id' => 7,
    'level_id' => 3,
  ),
  65 => 
  array (
    'id' => 102,
    'name' => 'Head Of Area Tangerang Kabupaten & Serang',
    'slug' => 'head-of-area-tangerang-kabupaten-serang',
    'department_id' => 7,
    'level_id' => 4,
  ),
  66 => 
  array (
    'id' => 103,
    'name' => 'Sales Coordinator Batam',
    'slug' => 'sales-coordinator-batam',
    'department_id' => 7,
    'level_id' => 3,
  ),
  67 => 
  array (
    'id' => 104,
    'name' => 'Sales Coordinator Tasikmalaya',
    'slug' => 'sales-coordinator-tasikmalaya',
    'department_id' => 7,
    'level_id' => 3,
  ),
  68 => 
  array (
    'id' => 105,
    'name' => 'Field Technical Coordinator Reg. 2A',
    'slug' => 'field-technical-coordinator-reg-2a',
    'department_id' => 6,
    'level_id' => 3,
  ),
  69 => 
  array (
    'id' => 106,
    'name' => 'Head of Region 3a (Sumatera Bagian Selatan)',
    'slug' => 'head-of-region-3a-sumatera-bagian-selatan',
    'department_id' => 7,
    'level_id' => 5,
  ),
  70 => 
  array (
    'id' => 107,
    'name' => 'Head of Area Samarinda',
    'slug' => 'head-of-area-samarinda',
    'department_id' => 7,
    'level_id' => 4,
  ),
  71 => 
  array (
    'id' => 108,
    'name' => 'Head of Area Banjarmasin',
    'slug' => 'head-of-area-banjarmasin',
    'department_id' => 7,
    'level_id' => 4,
  ),
  72 => 
  array (
    'id' => 109,
    'name' => 'Sales coordinator Lombok',
    'slug' => 'sales-coordinator-lombok',
    'department_id' => 7,
    'level_id' => 3,
  ),
  73 => 
  array (
    'id' => 110,
    'name' => 'Head Of Area Madiun & Bojonegoro',
    'slug' => 'head-of-area-madiun-bojonegoro',
    'department_id' => 7,
    'level_id' => 4,
  ),
  74 => 
  array (
    'id' => 111,
    'name' => 'Head Of Region 2b (Jatim)',
    'slug' => 'head-of-region-2b-jatim',
    'department_id' => 7,
    'level_id' => 5,
  ),
  75 => 
  array (
    'id' => 112,
    'name' => 'Head of Area Malang & Tulungagung',
    'slug' => 'head-of-area-malang-tulungagung',
    'department_id' => 7,
    'level_id' => 4,
  ),
  76 => 
  array (
    'id' => 113,
    'name' => 'Sales Coordinator Banyuwangi',
    'slug' => 'sales-coordinator-banyuwangi',
    'department_id' => 7,
    'level_id' => 3,
  ),
  77 => 
  array (
    'id' => 114,
    'name' => 'Head Of Region 1C',
    'slug' => 'head-of-region-1c',
    'department_id' => 7,
    'level_id' => 5,
  ),
  78 => 
  array (
    'id' => 115,
    'name' => 'Sales Coordinator Jambi',
    'slug' => 'sales-coordinator-jambi',
    'department_id' => 7,
    'level_id' => 3,
  ),
  79 => 
  array (
    'id' => 116,
    'name' => 'Head of Region 1a',
    'slug' => 'head-of-region-1a',
    'department_id' => 7,
    'level_id' => 5,
  ),
  80 => 
  array (
    'id' => 117,
    'name' => 'Head of Area Bekasi & Purwakarta',
    'slug' => 'head-of-area-bekasi-purwakarta',
    'department_id' => 7,
    'level_id' => 4,
  ),
  81 => 
  array (
    'id' => 118,
    'name' => 'Sales Coordinator Kudus',
    'slug' => 'sales-coordinator-kudus',
    'department_id' => 7,
    'level_id' => 3,
  ),
  82 => 
  array (
    'id' => 119,
    'name' => 'Head of Area Solo & Yogyakarta',
    'slug' => 'head-of-area-solo-yogyakarta',
    'department_id' => 7,
    'level_id' => 4,
  ),
  83 => 
  array (
    'id' => 120,
    'name' => 'Sales Coordinator Gorontalo',
    'slug' => 'sales-coordinator-gorontalo',
    'department_id' => 7,
    'level_id' => 3,
  ),
  84 => 
  array (
    'id' => 121,
    'name' => 'Head of Region 2d',
    'slug' => 'head-of-region-2d',
    'department_id' => 7,
    'level_id' => 5,
  ),
  85 => 
  array (
    'id' => 122,
    'name' => 'Sales Coordinator Jakarta Selatan dan Depok',
    'slug' => 'sales-coordinator-jakarta-selatan-dan-depok',
    'department_id' => 7,
    'level_id' => 3,
  ),
  86 => 
  array (
    'id' => 123,
    'name' => 'Sales Coordinator Baubau',
    'slug' => 'sales-coordinator-baubau',
    'department_id' => 7,
    'level_id' => 3,
  ),
  87 => 
  array (
    'id' => 124,
    'name' => 'Sales Coordinator Kendari',
    'slug' => 'sales-coordinator-kendari',
    'department_id' => 7,
    'level_id' => 3,
  ),
  88 => 
  array (
    'id' => 125,
    'name' => 'Sales Coordinator Purwokerto',
    'slug' => 'sales-coordinator-purwokerto',
    'department_id' => 7,
    'level_id' => 3,
  ),
  89 => 
  array (
    'id' => 126,
    'name' => 'Sales Coordinator Magelang',
    'slug' => 'sales-coordinator-magelang',
    'department_id' => 7,
    'level_id' => 3,
  ),
  90 => 
  array (
    'id' => 127,
    'name' => 'Head of Area Surabaya',
    'slug' => 'head-of-area-surabaya',
    'department_id' => 7,
    'level_id' => 4,
  ),
  91 => 
  array (
    'id' => 128,
    'name' => 'Sales Coordinator Tegal',
    'slug' => 'sales-coordinator-tegal',
    'department_id' => 7,
    'level_id' => 3,
  ),
  92 => 
  array (
    'id' => 129,
    'name' => 'Head Of Area Bali',
    'slug' => 'head-of-area-bali',
    'department_id' => 7,
    'level_id' => 4,
  ),
  93 => 
  array (
    'id' => 130,
    'name' => 'Channel Development Support Staff',
    'slug' => 'channel-development-support-staff',
    'department_id' => 7,
    'level_id' => 3,
  ),
  94 => 
  array (
    'id' => 131,
    'name' => 'Sales Support Admin',
    'slug' => 'sales-support-admin',
    'department_id' => 7,
    'level_id' => 3,
  ),
  95 => 
  array (
    'id' => 132,
    'name' => 'Digital Marketing Staff',
    'slug' => 'digital-marketing-staff',
    'department_id' => 7,
    'level_id' => 3,
  ),
  96 => 
  array (
    'id' => 133,
    'name' => 'Field Technical Coordinator Palembang',
    'slug' => 'field-technical-coordinator-palembang',
    'department_id' => 7,
    'level_id' => 3,
  ),
  97 => 
  array (
    'id' => 134,
    'name' => 'Field Technical Coordinator Reg. 2B',
    'slug' => 'field-technical-coordinator-reg-2b',
    'department_id' => 7,
    'level_id' => 3,
  ),
  98 => 
  array (
    'id' => 135,
    'name' => 'Technical Supervisor Reg 1A',
    'slug' => 'technical-supervisor-reg-1a',
    'department_id' => 7,
    'level_id' => 4,
  ),
  99 => 
  array (
    'id' => 136,
    'name' => 'Head Of Area Lampung',
    'slug' => 'head-of-area-lampung',
    'department_id' => 7,
    'level_id' => 4,
  ),
  100 => 
  array (
    'id' => 137,
    'name' => 'Sales Coordinator Sorong & Manokwari',
    'slug' => 'sales-coordinator-sorong-manokwari',
    'department_id' => 7,
    'level_id' => 3,
  ),
  101 => 
  array (
    'id' => 138,
    'name' => 'Sales Coordinator Ambon',
    'slug' => 'sales-coordinator-ambon',
    'department_id' => 7,
    'level_id' => 3,
  ),
  102 => 
  array (
    'id' => 139,
    'name' => 'Sales Coordinator Ternate & Pulau Halmahera',
    'slug' => 'sales-coordinator-ternate-pulau-halmahera',
    'department_id' => 7,
    'level_id' => 3,
  ),
  103 => 
  array (
    'id' => 140,
    'name' => 'Head of Area Bogor & Sukabumi',
    'slug' => 'head-of-area-bogor-sukabumi',
    'department_id' => 7,
    'level_id' => 4,
  ),
  104 => 
  array (
    'id' => 141,
    'name' => 'Head of Region 1d ( Bogor, Cianjur, Sukabumi, Bekasi, Karawang, Cikampek & Purwakarta)',
    'slug' => 'head-of-region-1d-bogor-cianjur-sukabumi-bekasi-karawang-cikampek-purwakarta',
    'department_id' => 7,
    'level_id' => 5,
  ),
  105 => 
  array (
    'id' => 142,
    'name' => 'Sales Coordinator Bojonegoro',
    'slug' => 'sales-coordinator-bojonegoro',
    'department_id' => 7,
    'level_id' => 3,
  ),
  106 => 
  array (
    'id' => 143,
    'name' => 'Sales Coordinator Jakarta Selatan',
    'slug' => 'sales-coordinator-jakarta-selatan',
    'department_id' => 7,
    'level_id' => 3,
  ),
  107 => 
  array (
    'id' => 144,
    'name' => 'Head Of Area Makassar',
    'slug' => 'head-of-area-makassar',
    'department_id' => 7,
    'level_id' => 4,
  ),
  108 => 
  array (
    'id' => 145,
    'name' => 'Distribution & Sales Capability Support Specialist',
    'slug' => 'distribution-sales-capability-support-specialist',
    'department_id' => 7,
    'level_id' => 4,
  ),
  109 => 
  array (
    'id' => 146,
    'name' => 'International Sales Supervisor',
    'slug' => 'international-sales-supervisor',
    'department_id' => 7,
    'level_id' => 4,
  ),
  110 => 
  array (
    'id' => 147,
    'name' => 'Asst. Test Baker',
    'slug' => 'asst-test-baker',
    'department_id' => 7,
    'level_id' => 2,
  ),
  111 => 
  array (
    'id' => 150,
    'name' => 'Asst. Mgr - Field Technical Reg 2 & 4',
    'slug' => 'asst-mgr-field-technical-reg-2-4',
    'department_id' => 7,
    'level_id' => 5,
  ),
  112 => 
  array (
    'id' => 151,
    'name' => 'Asst. Mgr - Technical (KACOM & Mother Choice)',
    'slug' => 'asst-mgr-technical-kacom-mother-choice',
    'department_id' => 7,
    'level_id' => 5,
  ),
  113 => 
  array (
    'id' => 152,
    'name' => 'Technical Supervisor Reg 1D',
    'slug' => 'technical-supervisor-reg-1d',
    'department_id' => 7,
    'level_id' => 4,
  ),
  114 => 
  array (
    'id' => 154,
    'name' => 'Asst. Mgr - Industrial Commercial',
    'slug' => 'asst-mgr-industrial-commercial',
    'department_id' => 7,
    'level_id' => 5,
  ),
  115 => 
  array (
    'id' => 155,
    'name' => 'S & M Admin Assistant',
    'slug' => 's-m-admin-assistant',
    'department_id' => 7,
    'level_id' => 3,
  ),
  116 => 
  array (
    'id' => 156,
    'name' => 'Route to Market & Channel Development Ass. Manager',
    'slug' => 'route-to-market-channel-development-ass-manager',
    'department_id' => 7,
    'level_id' => 5,
  ),
  117 => 
  array (
    'id' => 157,
    'name' => 'Technical Supervisor Reg. 1C',
    'slug' => 'technical-supervisor-reg-1c',
    'department_id' => 7,
    'level_id' => 4,
  ),
  118 => 
  array (
    'id' => 158,
    'name' => 'Graphic Designer',
    'slug' => 'graphic-designer',
    'department_id' => 7,
    'level_id' => 3,
  ),
  119 => 
  array (
    'id' => 159,
    'name' => 'Digital Marketing Supervisor',
    'slug' => 'digital-marketing-supervisor',
    'department_id' => 7,
    'level_id' => 4,
  ),
);

        foreach ($positions as $pos) {
            Position::updateOrCreate(
                ['id' => $pos['id']],
                [
                    'name' => $pos['name'],
                    'slug' => $pos['slug'],
                    'department_id' => $pos['department_id'],
                    'level_id' => $pos['level_id'],
                ]
            );
        }
    }
}