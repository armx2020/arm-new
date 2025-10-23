<?php

use App\Http\Controllers\Pages\DinamicRouteController;
use App\Http\Controllers\Pages\EntityController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\ProfileController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/profile.php';
require __DIR__ . '/inform-us.php';

Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/condition-of-use', [HomeController::class, 'conditionOfUse'])->name('condition-of-use');

Route::get('/edit/{idOrTranscript}', [EntityController::class, 'edit'])->name('entity.edit');
Route::patch('/{idOrTranscript}', [EntityController::class, 'update'])->name('entity.update');

Route::get('/photo/{idOrTranscript}', [EntityController::class, 'editPhoto'])->name('entity.photo.edit');
Route::patch('/photo/{idOrTranscript}', [EntityController::class, 'updatePhoto'])->name('entity.photo.update');

Route::get('/user/{id}', [ProfileController::class, 'show'])->name('user.show');

Route::get('/company/{idOrTranscript}', function($idOrTranscript) {
    return app(DinamicRouteController::class)->show('companies', $idOrTranscript);
})->name('company.show');
Route::get('/companies/{regionTranslit}/{category?}/{subCategory?}', function($regionTranslit, $category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->region('companies', $regionTranslit, $category, $subCategory);
})->name('companies.region');
Route::get('/companies/{category?}/{subCategory?}', function($category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->index('companies', $category, $subCategory);
})->name('companies.index');

Route::get('/group/{idOrTranscript}', function($idOrTranscript) {
    return app(DinamicRouteController::class)->show('groups', $idOrTranscript);
})->name('group.show');
Route::get('/groups/{regionTranslit}/{category?}/{subCategory?}', function($regionTranslit, $category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->region('groups', $regionTranslit, $category, $subCategory);
})->name('groups.region');
Route::get('/groups/{category?}/{subCategory?}', function($category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->index('groups', $category, $subCategory);
})->name('groups.index');

Route::get('/place/{idOrTranscript}', function($idOrTranscript) {
    return app(DinamicRouteController::class)->show('places', $idOrTranscript);
})->name('place.show');
Route::get('/places/{regionTranslit}/{category?}/{subCategory?}', function($regionTranslit, $category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->region('places', $regionTranslit, $category, $subCategory);
})->name('places.region');
Route::get('/places/{category?}/{subCategory?}', function($category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->index('places', $category, $subCategory);
})->name('places.index');

Route::get('/community/{idOrTranscript}', function($idOrTranscript) {
    return app(DinamicRouteController::class)->show('communities', $idOrTranscript);
})->name('community.show');
Route::get('/communities/{regionTranslit}/{category?}/{subCategory?}', function($regionTranslit, $category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->region('communities', $regionTranslit, $category, $subCategory);
})->name('communities.region');
Route::get('/communities/{category?}/{subCategory?}', function($category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->index('communities', $category, $subCategory);
})->name('communities.index');

Route::get('/job/{idOrTranscript}', function($idOrTranscript) {
    return app(DinamicRouteController::class)->show('jobs', $idOrTranscript);
})->name('job.show');
Route::get('/jobs/{regionTranslit}/{category?}/{subCategory?}', function($regionTranslit, $category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->region('jobs', $regionTranslit, $category, $subCategory);
})->name('jobs.region');
Route::get('/jobs/{category?}/{subCategory?}', function($category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->index('jobs', $category, $subCategory);
})->name('jobs.index');

Route::get('/project/{idOrTranscript}', function($idOrTranscript) {
    return app(DinamicRouteController::class)->show('projects', $idOrTranscript);
})->name('project.show');
Route::get('/projects/{regionTranslit}/{category?}/{subCategory?}', function($regionTranslit, $category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->region('projects', $regionTranslit, $category, $subCategory);
})->name('projects.region');
Route::get('/projects/{category?}/{subCategory?}', function($category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->index('projects', $category, $subCategory);
})->name('projects.index');

Route::get('/resume/{idOrTranscript}', function($idOrTranscript) {
    return app(DinamicRouteController::class)->show('resumes', $idOrTranscript);
})->name('resume.show');
Route::get('/resumes/{regionTranslit}/{category?}/{subCategory?}', function($regionTranslit, $category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->region('resumes', $regionTranslit, $category, $subCategory);
})->name('resumes.region');
Route::get('/resumes/{category?}/{subCategory?}', function($category = null, $subCategory = null) {
    return app(DinamicRouteController::class)->index('resumes', $category, $subCategory);
})->name('resumes.index');

Route::get('/test-s3-config', function() {
    return view('test-s3');
})->name('test.s3');

Route::post('/deploy/webhook', [\App\Http\Controllers\DeployController::class, 'deploy'])->name('deploy.webhook');
