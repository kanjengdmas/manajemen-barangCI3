<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes with
| underscores in the controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth';
$route['dashboard'] = 'Dashboard/index';

// Auth routes
$route['login'] = 'Auth/index';
$route['logout'] = 'Auth/logout';
$route['auth/attempt'] = 'Auth/attempt';

// Dashboard API routes
$route['api/dashboard/stats'] = 'api/Dashboard/stats';
$route['api/dashboard/stok-chart'] = 'api/Dashboard/stok_chart';
$route['api/dashboard/low-stock'] = 'api/Dashboard/low_stock';
$route['api/dashboard/movement-chart'] = 'api/Dashboard/movement_chart';
$route['api/dashboard/recent-activities'] = 'api/Dashboard/recent_activities';
$route['api/batch/expiring'] = 'api/Dashboard/expiring_batches';

// Profile & Preferences
$route['profile'] = 'Profil/index';
$route['profil'] = 'Profil/index';
$route['api/user'] = 'api/Profile/me';
$route['api/profile'] = 'api/Profile/index';
$route['api/profile/update'] = 'api/Profile/update';
$route['api/profile/password'] = 'api/Profile/password';
$route['api/profile/preferences'] = 'api/Profile/preferences';

// Settings & System Backup
$route['settings'] = 'Pengaturan/index';
$route['pengaturan'] = 'Pengaturan/index';
$route['api/settings'] = 'api/Settings/index';
$route['api/settings/save'] = 'api/Settings/save';
$route['api/settings/backup'] = 'api/Settings/backup';
$route['api/settings/backup-sql'] = 'api/Settings/backup_sql';
$route['api/settings/company'] = 'api/Settings/company';
$route['api/settings/clear-cache'] = 'api/Settings/clear_cache';

// Produk
$route['produk'] = 'Produk/index';
$route['produk/create'] = 'Produk/create';
$route['produk/store'] = 'Produk/store';
$route['produk/edit/(:num)'] = 'Produk/edit/$1';
$route['produk/update/(:num)'] = 'Produk/update/$1';
$route['produk/delete/(:num)'] = 'Produk/delete/$1';
$route['api/produk/(:num)/history'] = 'api/Produk/history/$1';
$route['api/produk/(:num)/generate-qr'] = 'api/Produk/generate_qr/$1';
$route['api/produk/(:num)/download-qr'] = 'api/Produk/download_qr/$1';
$route['api/produk/(:num)/delete-qr'] = 'api/Produk/delete_qr/$1';

// Kategori
$route['kategori'] = 'Kategori/index';
$route['kategori/create'] = 'Kategori/create';
$route['kategori/store'] = 'Kategori/store';
$route['kategori/edit/(:num)'] = 'Kategori/edit/$1';
$route['kategori/update/(:num)'] = 'Kategori/update/$1';
$route['kategori/delete/(:num)'] = 'Kategori/delete/$1';

// Pemasok
$route['pemasok'] = 'Pemasok/index';
$route['pemasok/create'] = 'Pemasok/create';
$route['pemasok/store'] = 'Pemasok/store';
$route['pemasok/edit/(:num)'] = 'Pemasok/edit/$1';
$route['pemasok/update/(:num)'] = 'Pemasok/update/$1';
$route['pemasok/delete/(:num)'] = 'Pemasok/delete/$1';

// Batch
$route['batch'] = 'Batch/index';
$route['batch/create'] = 'Batch/create';
$route['batch/store'] = 'Batch/store';
$route['batch/edit/(:num)'] = 'Batch/edit/$1';
$route['batch/update/(:num)'] = 'Batch/update/$1';
$route['batch/delete/(:num)'] = 'Batch/delete/$1';
$route['batch/detail/(:num)'] = 'Batch/detail/$1';
$route['batch/history/(:num)'] = 'Batch/history/$1';
$route['api/batch/(:num)/history'] = 'api/Batch/history/$1';
$route['api/batch/scan/(:any)'] = 'api/Batch/scan/$1';
$route['api/batch/produk/(:num)/batches'] = 'api/Batch/by_produk/$1';
$route['api/batch/(:num)/generate-qr'] = 'api/Batch/generate_qr/$1';
$route['api/batch/(:num)/download-qr'] = 'api/Batch/download_qr/$1';

// Stok & Transaksi
$route['stok'] = 'Stok/index';
$route['stok/masuk'] = 'Stok/masuk';
$route['stok/keluar'] = 'Stok/keluar';
$route['stok/bukti/(:num)'] = 'Stok/bukti/$1';
$route['api/stok/history'] = 'api/Stok/history';
$route['api/stok/summary'] = 'api/Stok/summary';
$route['api/stok/export/excel'] = 'api/Stok/export_excel';
$route['api/stok/export/pdf'] = 'api/Stok/export_pdf';
$route['api/batch/export/excel'] = 'api/Batch/export_excel';
$route['api/produk/export/excel'] = 'api/Produk/export_excel';

// Scanner & Laporan
$route['scan'] = 'Scanner/index';
$route['laporan'] = 'Laporan/index';

// CLI Routes
$route['migrate'] = 'Migrate/latest';
$route['migrate/(:any)'] = 'Migrate/$1';
$route['cli_digest/(:any)'] = 'Cli_digest/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;