<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ExposureController;
use App\Controllers\HedgeController;
use App\Controllers\ReviewController;
use App\Controllers\AuditController;
use App\Controllers\ReportController;

$router->get('/', fn () => (new DashboardController())->index());

$router->get('/login', fn () => (new AuthController())->showLoginForm());
$router->post('/login', fn () => (new AuthController())->login());
$router->get('/logout', fn () => (new AuthController())->logout());

$router->get('/exposures', fn () => (new ExposureController())->index());
$router->get('/exposures/create', fn () => (new ExposureController())->create());
$router->post('/exposures/store', fn () => (new ExposureController())->store());
$router->get('/exposures/edit', fn () => (new ExposureController())->edit());
$router->post('/exposures/update', fn () => (new ExposureController())->update());
$router->post('/exposures/delete', fn () => (new ExposureController())->delete());

$router->get('/hedges', fn () => (new HedgeController())->index());
$router->get('/hedges/create', fn () => (new HedgeController())->create());
$router->post('/hedges/store', fn () => (new HedgeController())->store());
$router->get('/hedges/edit', fn () => (new HedgeController())->edit());
$router->post('/hedges/update', fn () => (new HedgeController())->update());
$router->post('/hedges/delete', fn () => (new HedgeController())->delete());

$router->get('/reviews', fn () => (new ReviewController())->index());
$router->post('/reviews/submit', fn () => (new ReviewController())->submit());

$router->get('/audit', fn () => (new AuditController())->index());

$router->get('/reports', fn () => (new ReportController())->index());
$router->get('/reports/csv', fn () => (new ReportController())->csv());
