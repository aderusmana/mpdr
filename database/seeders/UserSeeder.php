<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array (
  0 => 
  array (
    'id' => 1,
    'nik' => 'AG1111',
    'name' => 'Admin',
    'email' => 'staff@smii.co.id',
    'password' => '$2y$12$YjBvJTmOwhwIBSsh47YKR.pElE6DgdbaObLysE9IZqN00wlUsBcAa',
    'avatar' => 'AG1111.png',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 32,
    'section_id' => 18,
    'status' => 'Active',
    'atasan_nik' => 'T0926',
    'roles' => 
    array (
      0 => 'super-admin',
      1 => 'approver',
    ),
  ),
  1 => 
  array (
    'id' => 1306,
    'nik' => 'W1005',
    'name' => 'Yovi Susanti',
    'email' => 'yovi.susanti@smii.co.id',
    'password' => '$2y$12$0Q.9IjEh7YxTGWQEaNbuye8wwtg.XgvIZixw22RDOGqp1f5Ra2piu',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 64,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => 'AG1337',
    'roles' => 
    array (
      0 => 'super-admin',
      1 => 'approver',
    ),
  ),
  2 => 
  array (
    'id' => 1307,
    'nik' => 'T0926',
    'name' => 'Melvin Darmadinata',
    'email' => 'melvin.darmadinata@smii.co.id',
    'password' => '$2y$12$FuMkdv0PMoPUi6KaqqLVF.LmIgDxXvDU9dqqEK6ycLtyXKhRQXxVS',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 2,
    'section_id' => 10,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
      2 => 'gm',
    ),
  ),
  3 => 
  array (
    'id' => 1308,
    'nik' => 'T0932',
    'name' => 'Mas Arief Fazry',
    'email' => 'arief.fazry@smii.co.id',
    'password' => '$2y$12$6qrtNTMTLSl0.VJ9SKfKGu4ZQxG71wfvvEUAJtaPRjUZnXVFAm9Sa',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 16,
    'section_id' => 5,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  4 => 
  array (
    'id' => 1309,
    'nik' => 'U0955',
    'name' => 'Danu Purboyo',
    'email' => 'danu.purboyo@smii.co.id',
    'password' => '$2y$12$UE8iqCFx7YHhbYnbIAThh.huijdHwJKtf6t9tTYmufaCVdtFUhhw2',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 6,
    'section_id' => 10,
    'status' => 'Active',
    'atasan_nik' => 'X1031',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  5 => 
  array (
    'id' => 1310,
    'nik' => 'X1031',
    'name' => 'Edie Hirman',
    'email' => 'edie.hirman@smii.co.id',
    'password' => '$2y$12$KNvD4xUVGrj7kZrD97KuK..BCPdhinD1bZgOJ7515jm.X.mbfLbom',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 3,
    'section_id' => 10,
    'status' => 'Active',
    'atasan_nik' => 'T0926',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  6 => 
  array (
    'id' => 1311,
    'nik' => 'AD1214',
    'name' => 'Rainita Darmadi',
    'email' => 'rainita.darmadi@smii.co.id',
    'password' => '$2y$12$xv2PZAXPZQP5JXp283sqZeMs1dyByZEoNco5xYH8j.5HzznwwT4.O',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 5,
    'section_id' => 10,
    'status' => 'Active',
    'atasan_nik' => 'X1031',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  7 => 
  array (
    'id' => 1312,
    'nik' => 'AD1227',
    'name' => 'Andika Suhendar',
    'email' => 'andika.suhendar@smii.co.id',
    'password' => '$2y$12$qwp0YoRngfEankizis6SVuvuR8oqaZtiz.Pfo7p/iqpqM9Xp5SpeO',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 4,
    'section_id' => 18,
    'status' => 'Active',
    'atasan_nik' => 'X1031',
    'roles' => 
    array (
      0 => 'super-admin',
      1 => 'user',
    ),
  ),
  8 => 
  array (
    'id' => 1313,
    'nik' => 'AE1244',
    'name' => 'Samuel David Christoper',
    'email' => 'samuel.christoper@smii.co.id',
    'password' => '$2y$12$QC5QDHJhwLAOjpfpkRKk/OO6qYCNdKQGoDSHyVuPoEwrK1P9ApEWW',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 7,
    'section_id' => 18,
    'status' => 'Active',
    'atasan_nik' => 'AD1227',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  9 => 
  array (
    'id' => 1314,
    'nik' => 'AG1315',
    'name' => 'Ade Rusmana',
    'email' => 'ade.rusmana@smii.co.id',
    'password' => '$2y$12$FQ.Ftni.PWA1AbriPHLLruKNzhGwnTyKZo0rfyEQKX737w4jNmFAS',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 2,
    'position_id' => 8,
    'section_id' => 18,
    'status' => 'Active',
    'atasan_nik' => 'AD1227',
    'roles' => 
    array (
      0 => 'super-admin',
      1 => 'user',
    ),
  ),
  10 => 
  array (
    'id' => 1315,
    'nik' => 'D0437',
    'name' => 'Budi Adimulyo',
    'email' => 'budi.adimulyo@smii.co.id',
    'password' => '$2y$12$1jotmyo2QxooX4YfKZbVqOYpFy6lgk9s0m902OmyKNKbHLY31C.5a',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 4,
    'position_id' => 17,
    'section_id' => 7,
    'status' => 'Active',
    'atasan_nik' => 'T0926',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  11 => 
  array (
    'id' => 1316,
    'nik' => 'M0812',
    'name' => 'Djarot Hendrawan',
    'email' => 'djarot.hendrawan@smii.co.id',
    'password' => '$2y$12$G/VupShjc4f3C2sD59w4leSZ.sAU4pKld9VPjKqqAYQRagKO/tHia',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 4,
    'position_id' => 19,
    'section_id' => 17,
    'status' => 'Active',
    'atasan_nik' => 'D0437',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  12 => 
  array (
    'id' => 1317,
    'nik' => 'AC1166',
    'name' => 'Dimas Supriyadi',
    'email' => 'dimas.supriyadi@smii.co.id',
    'password' => '$2y$12$bt3/MWF0FDj9/ediv/83luWh36Xuwj.bYRULzVUbTt//5lI7/N3Wm',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 4,
    'position_id' => 18,
    'section_id' => 7,
    'status' => 'Active',
    'atasan_nik' => 'D0437',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  13 => 
  array (
    'id' => 1318,
    'nik' => 'C0346',
    'name' => 'Payaman Pandiangan',
    'email' => 'payaman.pandiangan@smii.co.id',
    'password' => '$2y$12$fVv.iAtCPYC5XmHSWJUmguV3YlSnBkuw8ffPMa3zLJCdz9dOMVWd.',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 4,
    'position_id' => 36,
    'section_id' => 7,
    'status' => 'Active',
    'atasan_nik' => 'T0926',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  14 => 
  array (
    'id' => 1319,
    'nik' => 'AC1157',
    'name' => 'Dedy Yuda Tri Artanto',
    'email' => 'dedy.artanto@smii.co.id',
    'password' => '$2y$12$1QOzou.mZKnXHVw12uYxoulNtr9oE8GC8skHne0Y42adJXDWCKUdW',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 5,
    'position_id' => 65,
    'section_id' => 21,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  15 => 
  array (
    'id' => 1320,
    'nik' => 'AF1308',
    'name' => 'Millicent Songster',
    'email' => 'millicent.songster@smii.co.id',
    'password' => '$2y$12$jLQQNrJu1642BFAa/zjEle1o6EeRFs3Mkj88SnleQXieo53UNgCCC',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 5,
    'position_id' => 42,
    'section_id' => 20,
    'status' => 'Active',
    'atasan_nik' => 'C0346',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  16 => 
  array (
    'id' => 1321,
    'nik' => 'Q0881',
    'name' => 'Muyarni',
    'email' => 'maria.muyarni@smii.co.id',
    'password' => '$2y$12$mWK1R5GsM.oR84i6..wMoegLF0K9jWaglavUhpdAglbF.lUSIIuKG',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 6,
    'position_id' => 47,
    'section_id' => 22,
    'status' => 'Active',
    'atasan_nik' => 'T0926',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  17 => 
  array (
    'id' => 1322,
    'nik' => 'U0939',
    'name' => 'Trancy Chandra',
    'email' => 'trancy.chandra@smii.co.id',
    'password' => '$2y$12$bpuMlBRwHMwUlB5Rm4hfU.sDkoEQUsG3jmv1an0oXnSJ/tfvcYOWi',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 6,
    'position_id' => 43,
    'section_id' => 22,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  18 => 
  array (
    'id' => 1323,
    'nik' => 'AE1263',
    'name' => 'Irfan Raditya Hendraputra',
    'email' => 'Irfan.raditya@smii.co.id',
    'password' => '$2y$12$kqrI4j08j0e4xt76.QQR4.87B3rIZiHfa9iejYU8D78gUxPTC0oZu',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 6,
    'position_id' => 45,
    'section_id' => 22,
    'status' => 'Active',
    'atasan_nik' => 'Q0881',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  19 => 
  array (
    'id' => 1324,
    'nik' => 'AE1264',
    'name' => 'Elia Herlina Dwiyanti',
    'email' => 'elia.dwiyanti@smii.co.id',
    'password' => '$2y$12$pD8HtcMyOIAtCEvrFoN8FuKq9iyIrs3ZdNOPIVn9T08Xx19kDHXaG',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 6,
    'position_id' => 46,
    'section_id' => 22,
    'status' => 'Active',
    'atasan_nik' => 'Q0881',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  20 => 
  array (
    'id' => 1325,
    'nik' => 'A0192',
    'name' => 'Haryanto Makmoer',
    'email' => 'haryanto.makmoer@smii.co.id',
    'password' => '$2y$12$qDGgR5a2lEf/DNdjftUz4.sYsLHrJGhf8qvOTLoXKLm5njjxDOEsa',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 39,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => 'Z1058',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  21 => 
  array (
    'id' => 1326,
    'nik' => 'K0761',
    'name' => 'Soebianto Halim',
    'email' => 'soebianto.halim@smii.co.id',
    'password' => '$2y$12$WX3p5kCHRq3KX/4xm2ONXemAFKhFf7DFgCQyCBSEQXRn52k6Dqzji',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 29,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => 'Z1058',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  22 => 
  array (
    'id' => 1327,
    'nik' => 'K0769',
    'name' => 'Jack Gembira Sitinjak',
    'email' => 'jack.sitinjak@smii.co.id',
    'password' => '$2y$12$mhay3guGBvRJbKH1uO9XC.QHeB5k6BoKZuUQl/xRdWLC1CcZAq9NC',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 66,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => 'AH1416',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  23 => 
  array (
    'id' => 1328,
    'nik' => 'K0771',
    'name' => 'Santo Salim',
    'email' => 'santo.salim@smii.co.id',
    'password' => '$2y$12$oQc4O7lWJmr92cT53Dt9CeL./yZV.MwADoLPA2VBWtHRA.OiSfiTm',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 67,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  24 => 
  array (
    'id' => 1329,
    'nik' => 'K0778',
    'name' => 'Jumhasrifel',
    'email' => 'jumhasrifel@smii.co.id',
    'password' => '$2y$12$xwUDL35zVvMgWEAz2lXksOuugm3pmDU6m3J8.xPAB66LwU20yehQS',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 69,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  25 => 
  array (
    'id' => 1330,
    'nik' => 'Q0858',
    'name' => 'Reginald Iskandar',
    'email' => 'reginald.iskandar@smii.co.id',
    'password' => '$2y$12$F3thwO7GK04/pjSc0ozfWOuLOzdqyXdFVFj3rRUjP.5Grgv5VmP8O',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 27,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => 'Z1058',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  26 => 
  array (
    'id' => 1331,
    'nik' => 'Q0860',
    'name' => 'Munkus Whisnu Dwisaputra',
    'email' => 'munkus.whisnu@smii.co.id',
    'password' => '$2y$12$99l44jH3eLR9dNHqCKw6gurcnRYNVIm0ZFPuSnizjNo/mm7qZw6hu',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 150,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  27 => 
  array (
    'id' => 1332,
    'nik' => 'Q0883',
    'name' => 'Molek Susilawati',
    'email' => 'molek.susilawati@smii.co.id',
    'password' => '$2y$12$ktue4ecydEDzOqgFSIGeNe9u.5A1QQjeL.AlRF2CGDq0OaN4K6PrK',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 70,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  28 => 
  array (
    'id' => 1333,
    'nik' => 'U0941',
    'name' => 'Aditiawansyah',
    'email' => 'aditiawansyah@smii.co.id',
    'password' => '$2y$12$u04aZ4E5D1TWjlwrKdtjDeXgEtJDlMptfH4KC5ZdrpECvPusUaIpm',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 71,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  29 => 
  array (
    'id' => 1334,
    'nik' => 'U0945',
    'name' => 'Chandra Humanita',
    'email' => 'chandra.humanita@smii.co.id',
    'password' => '$2y$12$2IGRPU/SJXoaPKctnxS2o./lFl1OIo1k7CKUxF1u1tb49r4wJ0Qe2',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 151,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  30 => 
  array (
    'id' => 1335,
    'nik' => 'U0950',
    'name' => 'Rofika',
    'email' => 'rofika.lay@smii.co.id',
    'password' => '$2y$12$Vv/kmmG/.oIGxNjXIJbP3.JvvIRA2HTjnNopfMU1/LK0tugu9Sv82',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 25,
    'section_id' => 13,
    'status' => 'Active',
    'atasan_nik' => 'Z1058',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  31 => 
  array (
    'id' => 1336,
    'nik' => 'U0958',
    'name' => 'Sri Cahyani Dwi Anggoro',
    'email' => 'sri.cahyani@smii.co.id',
    'password' => '$2y$12$vuaDKee4vDIjTS3ZCibjZuHE1VWH5tVHNBqZpczIEjGVm3qxg/nQC',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 24,
    'section_id' => 13,
    'status' => 'Active',
    'atasan_nik' => 'Z1058',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  32 => 
  array (
    'id' => 1337,
    'nik' => 'V0961',
    'name' => 'Rury Pratama',
    'email' => 'rury.pratama@smii.co.id',
    'password' => '$2y$12$55ubzsaVgVNJlfRzFyCib.s.OCpqGyOOl95YaWDzoqKi8W8ASQ5AC',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 152,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  33 => 
  array (
    'id' => 1338,
    'nik' => 'V0981',
    'name' => 'Nyimas Mariam',
    'email' => 'nyimas.mariam@smii.co.id',
    'password' => '$2y$12$P0LxB5plekxks5NvVl./zO1LWZG5aPX2zFE1hlF/YjbihZ/nhEx62',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 72,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  34 => 
  array (
    'id' => 1339,
    'nik' => 'W1002',
    'name' => 'Agus Sujana',
    'email' => 'agus.sujana@smii.co.id',
    'password' => '$2y$12$oe29BvQJD/ov/SHsCyqFK.OcRXR7afON487.aJL9Qw3rChPYxreg2',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 73,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  35 => 
  array (
    'id' => 1340,
    'nik' => 'W1011',
    'name' => 'Mufti Allutfi',
    'email' => 'mufti.allutfi@smii.co.id',
    'password' => '$2y$12$4iJ2MiZg.nbJg9M5i/I2ce9e4bwly55BBRw9IZkOctn/PJVHIw252',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 154,
    'section_id' => 13,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  36 => 
  array (
    'id' => 1341,
    'nik' => 'X1017',
    'name' => 'Putri Wulandari',
    'email' => 'putri.wulandari@smii.co.id',
    'password' => '$2y$12$dWmS5WPfE2YOytWhEe36s.YJNhYAXy8OQDeHWlar5sPjzlIx9fQy6',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 155,
    'section_id' => 24,
    'status' => 'Active',
    'atasan_nik' => 'Z1058',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  37 => 
  array (
    'id' => 1342,
    'nik' => 'X1030',
    'name' => 'Sapto Yogasmara',
    'email' => 'sapto.yogasmara@smii.co.id',
    'password' => '$2y$12$NmP9FMej6cCZYLNaCgFXEuRgtB0L37qepcvFr3QpoaJrCAGl2vqsy',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 74,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  38 => 
  array (
    'id' => 1343,
    'nik' => 'Y1042',
    'name' => 'Chien Siang',
    'email' => 'chen.siang@smii.co.id',
    'password' => '$2y$12$rxtt/kWfKf9sDAzWOcl/LOX6pDfkhYw7VB3m8rnFdQKllECG2MuTK',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 75,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  39 => 
  array (
    'id' => 1344,
    'nik' => 'Y1050',
    'name' => 'Nahason Haria',
    'email' => 'nahason.haria@smii.co.id',
    'password' => '$2y$12$clG14/6b7pOpGezfbS2o8.23a9S6g37lDaXNUSduamo1coQ.aahoK',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 156,
    'section_id' => 23,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  40 => 
  array (
    'id' => 1345,
    'nik' => 'Z1058',
    'name' => 'Ronal Katili',
    'email' => 'ronal.katili@smii.co.id',
    'password' => '$2y$12$nBsBLhC6gE/AfW.p7ffAjOFPKEP65hazdigu0BZt.8ho70TEFd8KC',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 30,
    'section_id' => 24,
    'status' => 'Active',
    'atasan_nik' => 'T0926',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  41 => 
  array (
    'id' => 1346,
    'nik' => 'Z1079',
    'name' => 'Mifa Miftahul Chair Anwar',
    'email' => 'miftahul.choir@smii.co.id',
    'password' => '$2y$12$qUqNy39OAszzYdw88AFqU..pcqM1WC3ZZGXwowvjzArRlFXC0Udm6',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 76,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  42 => 
  array (
    'id' => 1347,
    'nik' => 'AA1088',
    'name' => 'Yudha Nugraha',
    'email' => 'yudha.nugraha@smii.co.id',
    'password' => '$2y$12$t1qtTvvQHzEDfvBVgNzhuezruKu0ixlzR02huMt1rVNXsni7WIStW',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 77,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  43 => 
  array (
    'id' => 1348,
    'nik' => 'AA1095',
    'name' => 'Christoforus Kevin',
    'email' => 'christoforus.kevin@smii.co.id',
    'password' => '$2y$12$84BWFV2Du73m.DZINOnSTOkN2oKo2M8asungDfZOuSKOEj/mJow0O',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 157,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  44 => 
  array (
    'id' => 1349,
    'nik' => 'AA1098',
    'name' => 'Siswadi',
    'email' => 'siswadi@smii.co.id',
    'password' => '$2y$12$zsaJQLAntMoNQew4Vu.vxukJ2hknQ8C1cr8gMVqS8Da9xypDzllc6',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 78,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  45 => 
  array (
    'id' => 1350,
    'nik' => 'AA1100',
    'name' => 'Yandri Fahmi',
    'email' => 'yandri.fahmi@smii.co.id',
    'password' => '$2y$12$LZvp3ap1HQQdS5L8z5RZeOTkQ3JNGtGpCLfYnkdke9.tIwq9p.R3S',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 79,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  46 => 
  array (
    'id' => 1351,
    'nik' => 'AA1104',
    'name' => 'Akhmad Sahril',
    'email' => 'akhmad.sahril@smii.co.id',
    'password' => '$2y$12$XfuS/y6wyuDt7I5OJU8I7OptLCfM15JBRZabkLZSD9CnsQnuMPgBW',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 80,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  47 => 
  array (
    'id' => 1352,
    'nik' => 'AA1108',
    'name' => 'Wilson Macmiland',
    'email' => 'wilson.macmiland@smii.co.id',
    'password' => '$2y$12$jHArsFRTHWyfyl1b7esSn.9JP4MeTK/2lAvXQw..Qc7HZyY9QfqVa',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 82,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  48 => 
  array (
    'id' => 1353,
    'nik' => 'AA1112',
    'name' => 'Kevin Wijaya',
    'email' => 'kevin.wijaya@smii.co.id',
    'password' => '$2y$12$UoKUZK8AuMqdvG9edmcrHu56sGtEeKF5.wK.UB9L.KCzyc6.qjwgO',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 81,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  49 => 
  array (
    'id' => 1354,
    'nik' => 'AB1117',
    'name' => 'Dhani Irfantoro',
    'email' => 'dhani.irfantoro@smii.co.id',
    'password' => '$2y$12$lEhDfj/2WbfG0feeH5n7euE0zKG8oa38tiuCqyFYwBy5JpzRoUGUS',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 83,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  50 => 
  array (
    'id' => 1355,
    'nik' => 'AB1118',
    'name' => 'Martin Paulus Sinaga',
    'email' => 'martin.sinaga@smii.co.id',
    'password' => '$2y$12$9EmxlwFw2lU5lNbLcH.XselMMs5sKBGaqbU17J.VscCFrEuD3IzNG',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 84,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  51 => 
  array (
    'id' => 1356,
    'nik' => 'AB1128',
    'name' => 'Rianto Wibisono',
    'email' => 'rianto.wibisono@smii.co.id',
    'password' => '$2y$12$joU2dRGZt0DTlm0SNWeV.eeF4UdkOhjNeqKBfXxyXh93TqEcgiIGS',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 86,
    'section_id' => 13,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  52 => 
  array (
    'id' => 1358,
    'nik' => 'AB1133',
    'name' => 'Rizal Anwar',
    'email' => 'rizal.anwar@smii.co.id',
    'password' => '$2y$12$VadOlw.LDJVCuyuVfb2qwu2i4.B4aAKLlaEntwCdRFqy7cxOWVAWC',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 85,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  53 => 
  array (
    'id' => 1359,
    'nik' => 'AB1135',
    'name' => 'Panca Setiawan',
    'email' => 'panca.setiawan@smii.co.id',
    'password' => '$2y$12$UmU1Ubbe6rkj71kDLEJbre4LXGnOH.QLJnBA39KMer9eQ1ROLnQSu',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 88,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  54 => 
  array (
    'id' => 1360,
    'nik' => 'AB1141',
    'name' => 'Nugraha Amrul Fahlevi Lubis',
    'email' => 'fahlevi.lubis@smii.co.id',
    'password' => '$2y$12$R5oF.yXxE9GZB/4JdTX/3.OxFXSXP6hemtu3UWtu4Da4DwJ54TqIi',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 89,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  55 => 
  array (
    'id' => 1361,
    'nik' => 'AC1156',
    'name' => 'Ayu Untari Putri',
    'email' => 'ayu.putri@smii.co.id',
    'password' => '$2y$12$CLk4OxINJYT.vQLJg4y2hejPSmk9Tv/nB6BUaGxvO4N8aea.73v/G',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 31,
    'section_id' => 24,
    'status' => 'Active',
    'atasan_nik' => 'Z1058',
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  56 => 
  array (
    'id' => 1362,
    'nik' => 'AC1165',
    'name' => 'Leri Christian Manafe',
    'email' => 'christian.manafe@smii.co.id',
    'password' => '$2y$12$R7NRZE2Az9K6R1bDMpYXTeXR2ql9L6Of82CR.z69J/zhL0epzTsQu',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 90,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  57 => 
  array (
    'id' => 1363,
    'nik' => 'AC1167',
    'name' => 'Dedi Mangampu Tua',
    'email' => 'dedi.tua@smii.co.id',
    'password' => '$2y$12$fDFf/e4I29UHrZGUQdASuO79.unJpYFFQOJp8AxdLiFIqkaNT6PzG',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 91,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  58 => 
  array (
    'id' => 1364,
    'nik' => 'AC1168',
    'name' => 'Sugeng Hariyanto',
    'email' => 'sugeng.harianto@smii.co.id',
    'password' => '$2y$12$K4IvGgcNZLTTB58EdWktc.jRT54cZq5Eq3hu6ZTOc4U3QXNPtoOnO',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 92,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  59 => 
  array (
    'id' => 1365,
    'nik' => 'AC1174',
    'name' => 'Franies Matriyanto Yohakim Tandawuya',
    'email' => 'franies.tandawuya@smii.co.id',
    'password' => '$2y$12$fYVcWTM8EyRykWMwo.KMGOCt.yI.uOrg9B/I/yW9Qrt16jwtNUbEa',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 93,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  60 => 
  array (
    'id' => 1366,
    'nik' => 'AC1177',
    'name' => 'Khairudin',
    'email' => 'khairudin@smii.co.id',
    'password' => '$2y$12$w7YcjA8bCnqAdIfvzPnoeOO5CKgTiXrctF9l3tRVPSIlIOJniaAYi',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 94,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  61 => 
  array (
    'id' => 1367,
    'nik' => 'AD1205',
    'name' => 'Zen Ekasari Tristianto',
    'email' => 'zen.tristianto@smii.co.id',
    'password' => '$2y$12$j/y1EVABYJ80UQ36fHGytuz7NMTDGRnI1tKZHMOxIoRi7zvK/VCUy',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 95,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  62 => 
  array (
    'id' => 1368,
    'nik' => 'AD1228',
    'name' => 'Kelfin Alamanda',
    'email' => 'kelfin.alamanda@smii.co.id',
    'password' => '$2y$12$rzOd.9Y7vRrjZtwbjFhul.zi5G/laos/Hn.WaQIv.h2YX2ui3bB46',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 158,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  63 => 
  array (
    'id' => 1369,
    'nik' => 'AD1231',
    'name' => 'Dinur Yuwidianto',
    'email' => 'dinur.yuwidianto@smii.co.id',
    'password' => '$2y$12$bCTLVOoBksUm7W2QuS0nIeSK./ymamfxLQRZSMNKrCsM2txaOV93i',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 26,
    'section_id' => 13,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  64 => 
  array (
    'id' => 1370,
    'nik' => 'AD1234',
    'name' => 'Azwar Munif',
    'email' => 'azwar.munif@smii.co.id',
    'password' => '$2y$12$cI9mAdlp9sCSvJ.WJCoHCuDnpqJeCnzyGfOAuivQl4HJ8NmD5pIY2',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 96,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  65 => 
  array (
    'id' => 1371,
    'nik' => 'AD1236',
    'name' => 'Hendro Prayetno',
    'email' => 'hendro.prayetno@smii.co.id',
    'password' => '$2y$12$0yDI9whHd17RP8Mw.5nhdexPycRZ91OpUgWC3KJ.7dXfiHKUHrEJy',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 97,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  66 => 
  array (
    'id' => 1372,
    'nik' => 'AD1237',
    'name' => 'Dendi Heriyanto',
    'email' => 'dendy.heriyanto@smii.co.id',
    'password' => '$2y$12$R2hyhiq8MY/xGCMFUYXk/el/MggxErhLLI5DDxS2e795GmbVm2Sai',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 99,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  67 => 
  array (
    'id' => 1373,
    'nik' => 'AD1239',
    'name' => 'Andrey Hermawan',
    'email' => 'andrey.hermawan@smii.co.id',
    'password' => '$2y$12$32uYvjpGXJaWZrR5KITLl.2eWbx9FI5C2/pVh62qlvrcvqtmL1/c.',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 98,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  68 => 
  array (
    'id' => 1374,
    'nik' => 'AE1249',
    'name' => 'An Nisaa Catur Pekerti',
    'email' => 'nisaa.pekerti@smii.co.id',
    'password' => '$2y$12$.AfVA9lla3tAuf7EZQ.8/uir7R8W5WjUN2A33oQ1ifrop1jrPn4k2',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 100,
    'section_id' => 13,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  69 => 
  array (
    'id' => 1375,
    'nik' => 'AE1256',
    'name' => 'Putri Yashi Nabilah',
    'email' => 'putri.nabilah@smii.co.id',
    'password' => '$2y$12$qrvTW/hctvT.U5PicFDhR.DnTzKn8.a3a5Lc32LZ5/GMJdEssbs2K',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 101,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  70 => 
  array (
    'id' => 1376,
    'nik' => 'AE1261',
    'name' => 'Noor Kurnia Andrean',
    'email' => 'noor.andrean@smii.co.id',
    'password' => '$2y$12$Dz4OPBl1dQO8S0vwevjuz.ORetPY4OVgGGyqV.l7autPd4rLdesFC',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 102,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  71 => 
  array (
    'id' => 1377,
    'nik' => 'AE1268',
    'name' => 'Dinah Dzakiyyah Rasikhah',
    'email' => 'dinah.rasikhah@smii.co.id',
    'password' => '$2y$12$lS.93qlOecmjre6UDJrB1OwGBVEogwIgPPERkO453HBRdzq/1IGiK',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 159,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  72 => 
  array (
    'id' => 1378,
    'nik' => 'AF1281',
    'name' => 'Muhamad Faiq Renaldiandadinata',
    'email' => 'muhammad.faiq@smii.co.id',
    'password' => '$2y$12$3pM/UPyya1RwouLd5s5dRuVWiY0Mt5aEhz1tHRhN//1EJX02empJW',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 103,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  73 => 
  array (
    'id' => 1379,
    'nik' => 'AF1286',
    'name' => 'Teni Muhamad Rijal',
    'email' => 'teni.rijal@smii.co.id',
    'password' => '$2y$12$uY1haHfRO6youDgda3RB1Obc5phV5DZayTG7lzvsPebMegxodeOEe',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 104,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  74 => 
  array (
    'id' => 1380,
    'nik' => 'AF1287',
    'name' => 'Sonny Pratama Mandira',
    'email' => 'sonny.mandira@smii.co.id',
    'password' => '$2y$12$QgTehcHeykQk6XOW4mCc2O9H5rtCslxGkcCgQcRO75g40qUMUn2ga',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 105,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  75 => 
  array (
    'id' => 1385,
    'nik' => 'AH1416',
    'name' => 'Chandra Yudha Imanuddin',
    'email' => 'chandra.imanuddin@smii.co.id',
    'password' => '$2y$12$HEusrLg7mLI2WlR/sUediueRV/xXC20lDp1DXgqHshlExiW5QsuTq',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 29,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  76 => 
  array (
    'id' => 1386,
    'nik' => 'AH1415',
    'name' => 'Hoky Guna Souwputra',
    'email' => 'hoky.souwputra@smii.co.id',
    'password' => '$2y$12$qn5bND4IunMYgJZnK2MksuK/kTO8VDeMVu075WRc/zg5mQKyVl9Gu',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 140,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  77 => 
  array (
    'id' => 1387,
    'nik' => 'AH1408',
    'name' => 'Samueluy Panese',
    'email' => 'samueluy.panese@smii.co.id',
    'password' => '$2y$12$ezHMI7feiXKqoFT23y4Wv.zEtCRv5d4aZ0dqQStX5Gxsy4rN9V9nW',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 139,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  78 => 
  array (
    'id' => 1388,
    'nik' => 'AH1402',
    'name' => 'Ibnu Rijal Khoeron',
    'email' => 'ibnu.khoeron@smii.co.id',
    'password' => '$2y$12$VDl.jhpfsKu6zP97nx4EH.wrwFMq/WM2QEGNkVCS0LOJkO.iVaE/a',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 60,
    'section_id' => 13,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  79 => 
  array (
    'id' => 1389,
    'nik' => 'AH1399',
    'name' => 'Chairudin Unde',
    'email' => 'chairudin.unde@smii.co.id',
    'password' => '$2y$12$JglrC/IrKojBk.KjuvuvRuaBUuEscP9gefKUytJf6E/T5sIrpwW/a',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 138,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  80 => 
  array (
    'id' => 1390,
    'nik' => 'AI1442',
    'name' => 'Jasmine Dhawi Sabrina',
    'email' => 'jasmine.sabrina@smii.co.id',
    'password' => '$2y$12$qHcXWnSZGlZ/h8c/qJ9NQ.Dy4I/9vFdstOCBGEU4Npwm0gor27jFy',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 147,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  81 => 
  array (
    'id' => 1391,
    'nik' => 'AI1437',
    'name' => 'Muhammad Faishal Syahputra',
    'email' => 'faishal.syahputra@smii.co.id',
    'password' => '$2y$12$vKy5rQ6umIPz1Tu1rtnPHOUhwkDUM9iE9gPgdfzXqeNrIp2yEOPt6',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 146,
    'section_id' => 14,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  82 => 
  array (
    'id' => 1392,
    'nik' => 'AI1433',
    'name' => 'Rachmat Baharuddin',
    'email' => 'rachmat.baharuddin@smii.co.id',
    'password' => '$2y$12$lJZgVmUjl4i7w2OMPUtupuA/rsQFMKCYRfI78VWXv70VSrtFkJF/W',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 144,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  83 => 
  array (
    'id' => 1393,
    'nik' => 'AI1430',
    'name' => 'Adam Hafez Candrasusilo',
    'email' => 'adam.candrasusilo@smii.co.id',
    'password' => '$2y$12$pPPXeNViw7lELNeT6c.bk.6a3FU6V3ZljgOZAAhuhabye0mdBomsG',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 145,
    'section_id' => 23,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  84 => 
  array (
    'id' => 1394,
    'nik' => 'AH1428',
    'name' => 'Agita Putri Haditama',
    'email' => 'agita.haditama@smii.co.id',
    'password' => '$2y$12$AIIydOkiVtwAUSwMKywDU./ksy1qoXWUme.IbQcsocb/lMdAHyL6e',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 143,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  85 => 
  array (
    'id' => 1395,
    'nik' => 'AH1427',
    'name' => 'Adi Rizky Pradana',
    'email' => 'adi.pradana@smii.co.id',
    'password' => '$2y$12$CfsEfsVD4jKRczPiYN4DZOVSCGy5AAGXTqvBBv/EGKe3msuLWduhS',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 142,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  86 => 
  array (
    'id' => 1396,
    'nik' => 'AH1425',
    'name' => 'Budi Kristanto',
    'email' => 'budy.kristanto@smii.co.id',
    'password' => '$2y$12$SDU0uvoVKKlnY2nLonIkeuFPIkGvAvswdfGw.mleF6CVb/cBzpH.i',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 141,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  87 => 
  array (
    'id' => 1397,
    'nik' => 'AF1291',
    'name' => 'Fredy Salim',
    'email' => 'fredy.salim@smii.co.id',
    'password' => '$2y$12$dwdbvI1xNfkhuybRKnE62e76E9LRiUr9l281p5ZQVQ64Y1jmBXuMy',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 106,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  88 => 
  array (
    'id' => 1398,
    'nik' => 'AF1292',
    'name' => 'Arham Bisma Bahari',
    'email' => 'arham.bahari@smii.co.id',
    'password' => '$2y$12$KYpmwekGX.0vVx7a6zX9penrzei2V89sp3O9dhFnJ3YNVss1jfu8i',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 107,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  89 => 
  array (
    'id' => 1399,
    'nik' => 'AF1295',
    'name' => 'Anwar Rusady',
    'email' => 'anwar.rusady@smii.co.id',
    'password' => '$2y$12$Os32/Sz/FO6EFPTjIBMqSOn8ndhYlW19BCjeyzQ/AeHEjllVn72QC',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 108,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  90 => 
  array (
    'id' => 1400,
    'nik' => 'AF1297',
    'name' => 'Lopu Satria Nugraha',
    'email' => 'lopu.nugraha@smii.co.id',
    'password' => '$2y$12$Sz0pOtasrT29qdkj9lP1nedm55mPvawUQREjrYSecQZsNlCJR11wa',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 109,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  91 => 
  array (
    'id' => 1401,
    'nik' => 'AF1299',
    'name' => 'Dewi Nurhayati',
    'email' => 'dewi.nurhayati@smii.co.id',
    'password' => '$2y$12$WW5jvdHKj2WW3P2YP.aA5ug70PY2q5FHsKs30jV14fVgTtqh1KfQ2',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 110,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  92 => 
  array (
    'id' => 1402,
    'nik' => 'AF1301',
    'name' => 'Arip Haryanto',
    'email' => 'arip.haryanto@smii.co.id',
    'password' => '$2y$12$XZfqx70QAjokRfs9bANxluOCYJSpqv91um2IS8cMIFIjvtz5p1syq',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 111,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  93 => 
  array (
    'id' => 1403,
    'nik' => 'AF1302',
    'name' => 'Andrie Setiadi Putera',
    'email' => 'andrie.putera@smii.co.id',
    'password' => '$2y$12$WepHS4DFplmqUb17RZ5Jsu/k.MWEHvVUZJKEAkZRs8xZxaba9phAS',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 112,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  94 => 
  array (
    'id' => 1404,
    'nik' => 'AF1303',
    'name' => 'Nova Lila Sari',
    'email' => 'nova.lilasari@smii.co.id',
    'password' => '$2y$12$1.LlGx8VbxrEb2VqT0Cube8nBYsYOJZs0GlLjafWnchEGs5qv/lp6',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 113,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  95 => 
  array (
    'id' => 1405,
    'nik' => 'AF1307',
    'name' => 'Alfons Edward Sugito Palondongan',
    'email' => 'alfons.palondongan@smii.co.id',
    'password' => '$2y$12$hPNqTfJar.oqqhE.fPHsNuQifWdY9J14h1fcB0knzdCn6BKdHsn.m',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 114,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  96 => 
  array (
    'id' => 1406,
    'nik' => 'AG1310',
    'name' => 'Lepi Candra',
    'email' => 'lepi.candra@smii.co.id',
    'password' => '$2y$12$IjXgl36dnSm16m6Ny7gEiOpX82TLoanFVCZQnJDd0NxLxLylEVeB2',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 115,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  97 => 
  array (
    'id' => 1407,
    'nik' => 'AG1312',
    'name' => 'Andrea Aji Asmara',
    'email' => 'andrea.asmara@smii.co.id',
    'password' => '$2y$12$2OrmB2lw6OBdfmGVLPrNWOmUh9dfstng7gV.9ddxHzq9IJ8f6hylu',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 116,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  98 => 
  array (
    'id' => 1408,
    'nik' => 'AG1313',
    'name' => 'Gabriella Stephanie Gultom',
    'email' => 'gabriella.gultom@smii.co.id',
    'password' => '$2y$12$NteYdLaALAggqijsRVIqguUXU0V6tHCubLejgAZoJK.RVfX0ZSr5K',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 40,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  99 => 
  array (
    'id' => 1409,
    'nik' => 'AG1314',
    'name' => 'Achmad Fauzi',
    'email' => 'achmad.fauzi@smii.co.id',
    'password' => '$2y$12$w4GOEud/fRk359ckDiB9HeNAkoUMoXyysjxOQyqFZgjNK9mA2XeA6',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 117,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  100 => 
  array (
    'id' => 1410,
    'nik' => 'AG1316',
    'name' => 'Ali Mahmudi',
    'email' => 'ali.mahmudi@smii.co.id',
    'password' => '$2y$12$vtkaSJx6/dhuV8n.uVZyeOpjmhWK9aFNcGkTL8qIyoeJGo3kn26Ei',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 118,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  101 => 
  array (
    'id' => 1411,
    'nik' => 'AG1319',
    'name' => 'Albet Nego',
    'email' => 'albet.nego@smii.co.id',
    'password' => '$2y$12$18N2w7yDGx.Xoo.giX50luXYM0zEkzgohLmn430VTTclk5bEkA/em',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 119,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  102 => 
  array (
    'id' => 1412,
    'nik' => 'AG1321',
    'name' => 'Zuniar Usman',
    'email' => 'zuniar.usman@smii.co.id',
    'password' => '$2y$12$y3B.N3DlMZjO3x3qNcsuiuiRb4tEsU/bCVDbm1SSUUGFhGFm8hGGO',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 120,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  103 => 
  array (
    'id' => 1413,
    'nik' => 'AG1322',
    'name' => 'Hery Martono',
    'email' => 'hery.martono@smii.co.id',
    'password' => '$2y$12$swWrXXRtJhGKGlEWkiCgG.A1zF.g09mmBhkKRlYnAJnwVo3lmbMmq',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 121,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  104 => 
  array (
    'id' => 1414,
    'nik' => 'AG1326',
    'name' => 'Dadang Achmad Fadoli',
    'email' => 'dadang.fadoli@smii.co.id',
    'password' => '$2y$12$9sKDDZ2T.sTxZUK7vrzc9e1kHcXdrFc8ckKBT76g2YVAwlFLNpEf2',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 122,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  105 => 
  array (
    'id' => 1415,
    'nik' => 'AG1330',
    'name' => 'La Ode Saiful',
    'email' => 'laode.saiful@smii.co.id',
    'password' => '$2y$12$gEphcWcKPI/oZ1cbP6ZoqeXmMhWbXuJCLuGG0fYqyd32tzBfy5aI6',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 123,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  106 => 
  array (
    'id' => 1416,
    'nik' => 'AG1331',
    'name' => 'Muhammad Taufikurrahman',
    'email' => 'muhammad.taufikurrahman@smii.co.id',
    'password' => '$2y$12$gXoYY.tC28iYn.KIV1kKh.IKVRTj8xPZc1WSnZK8JNk3lMNhQxXxq',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 124,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  107 => 
  array (
    'id' => 1417,
    'nik' => 'AG1337',
    'name' => 'George Novaro Tarigan',
    'email' => 'george.tarigan@smii.co.id',
    'password' => '$2y$12$rvWnFnnI6ATfL65ACnYbSOEmv/XJOVMqCIGfPdd2cZphbr6/pn3fq',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 37,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => 'Z1058',
    'roles' => 
    array (
      0 => 'user',
      1 => 'approver',
    ),
  ),
  108 => 
  array (
    'id' => 1418,
    'nik' => 'AG1343',
    'name' => 'Rachman Fernanda',
    'email' => 'rachman.fernanda@smii.co.id',
    'password' => '$2y$12$cf4tOZ9GGwWAGyijbOQLhOFIIpjFMQ7goFn6aaQSqpBX4Xymk2OAG',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 125,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  109 => 
  array (
    'id' => 1419,
    'nik' => 'AG1346',
    'name' => 'Akhmad Naofal',
    'email' => 'akhmad.naofal@smii.co.id',
    'password' => '$2y$12$QRi1L/peR461Q5uWlRCErumdknS6ChuDEjO8Q1KD3E/R6v5ZJ9rCi',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 126,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  110 => 
  array (
    'id' => 1420,
    'nik' => 'AG1351',
    'name' => 'Agustinus Sulistiyanto',
    'email' => 'agustinus.sulistyanto@smii.co.id',
    'password' => '$2y$12$WY.Sr7L00LiMp2F93Qr1KOMcwPETCmGJLhMIZR5xfIe8vFY/uOzcS',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 127,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  111 => 
  array (
    'id' => 1421,
    'nik' => 'AG1354',
    'name' => 'Irma Sulistyowati',
    'email' => 'irma.sulistyowati@smii.co.id',
    'password' => '$2y$12$MnuklzjFfDpQPxM6v502mOy8I2.jvoo2I/hY0RyEaJwFVWFCXlNcW',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 128,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  112 => 
  array (
    'id' => 1422,
    'nik' => 'AH1389',
    'name' => 'Kelvin Gunandar Makaraya',
    'email' => 'kelvin.makaraya@smii.co.id',
    'password' => '$2y$12$OzgH6i8GC7kg0m0R3ngVHuf4H/gl5xC5ka5mkJiREum7tn0MbWYMK',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 137,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  113 => 
  array (
    'id' => 1423,
    'nik' => 'AH1364',
    'name' => 'I Kadek Dwi Okta Riskanadi',
    'email' => 'dwi.okta@smii.co.id',
    'password' => '$2y$12$9/nPgyYVHzynlD0XJVgeHOYzNe1S7N2ZYET.wtNDrQVJGZJgj3TTq',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 129,
    'section_id' => 9,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  114 => 
  array (
    'id' => 1424,
    'nik' => 'AH1367',
    'name' => 'Anissa Dwi Septiani',
    'email' => 'anissa.septiani@smii.co.id',
    'password' => '$2y$12$yCga.8ZEFEclHxNL4jtpYuiUkT06eTTgxSBWqWsfTd2LX6PAJD/rK',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 130,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  115 => 
  array (
    'id' => 1425,
    'nik' => 'AH1369',
    'name' => 'Kartika Cintya',
    'email' => 'kartika.cintya@smii.co.id',
    'password' => '$2y$12$xqn8CjnT5GYy2D41rD0ZhONlTYxT7Nb8QtmESKkLuUT0/rD61Dyvy',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 131,
    'section_id' => 24,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  116 => 
  array (
    'id' => 1426,
    'nik' => 'AH1370',
    'name' => 'Zafira Husna Salsabila',
    'email' => 'zafira.salsabila@smii.co.id',
    'password' => '$2y$12$DFNxW0dOQ6vqxqqkF8nPY.Ae/ugugk.2cI6hVXN4JVSK5VqkFb9Rm',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 132,
    'section_id' => 16,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  117 => 
  array (
    'id' => 1427,
    'nik' => 'AH1380',
    'name' => 'Hana Rahmania',
    'email' => 'hana.rahmania@smii.co.id',
    'password' => '$2y$12$uHdR7M5Be2tp/JfPyxforesSBy9qpm7cLZRc/X6Ok.VwV7mwemIt.',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 133,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  118 => 
  array (
    'id' => 1428,
    'nik' => 'AH1386',
    'name' => 'Ali Sahid Abdillah',
    'email' => 'ali.abdillah@smii.co.id',
    'password' => '$2y$12$gLcOUU2cSh3biK6DGZsDx..bXPU4v2/frKksyZOjr30zw.q4DHX76',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 134,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  119 => 
  array (
    'id' => 1429,
    'nik' => 'AH1387',
    'name' => 'Anna Nurlaila',
    'email' => 'anna.nurlaila@smii.co.id',
    'password' => '$2y$12$K93VfyVfKJH/1LHZ8/ZiPuHGwJD9wPh/vOfVYlILTWsBqK5eGrgFa',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 135,
    'section_id' => 25,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
  120 => 
  array (
    'id' => 1430,
    'nik' => 'AH1388',
    'name' => 'Prayudi Agus Kurniawan',
    'email' => 'prayudi.kurniawan@smii.co.id',
    'password' => '$2y$12$DrB6HKcHcg3WEIlEiIPawOUfIRyJyb9DsLrn0qsupGHHrQx93rUfe',
    'avatar' => '',
    'banner' => NULL,
    'department_id' => 7,
    'position_id' => 136,
    'section_id' => 26,
    'status' => 'Active',
    'atasan_nik' => NULL,
    'roles' => 
    array (
      0 => 'user',
    ),
  ),
);

        // Insert / Update users
        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['id' => $u['id']],
                [
                    'nik' => $u['nik'],
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => $u['password'],
                    'avatar' => $u['avatar'] ?? '',
                    'banner' => $u['banner'] ?? null,
                    'department_id' => $u['department_id'],
                    'position_id' => $u['position_id'],
                    'section_id' => $u['section_id'],
                    'status' => $u['status'] ?? 1,
                    'atasan_nik' => $u['atasan_nik'],
                ]
            );

            if (!empty($u['roles'])) {
                $user->syncRoles($u['roles']);
            }
        }
    }
}