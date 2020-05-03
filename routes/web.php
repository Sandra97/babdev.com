<?php

use BabDev\Http\Controllers\UploadImageThruCKEditorController;
use BabDev\Http\Controllers\ViewOpenSourcePackagesController;
use BabDev\Http\Controllers\ViewOpenSourceUpdateController;
use BabDev\Http\Controllers\ViewOpenSourceUpdatesController;
use Illuminate\Routing\Router;

/** @var Router $router */

$router->view('/', 'homepage')->name('homepage');
$router->view('/privacy', 'privacy')->name('privacy');

$router->get(
    '/open-source/packages',
    ViewOpenSourcePackagesController::class
)->name('open-source.packages');

$router->get(
    '/open-source/updates/{update}',
    ViewOpenSourceUpdateController::class
)->name('open-source.update');

$router->get(
    '/open-source/updates',
    ViewOpenSourceUpdatesController::class
)->name('open-source.updates');

$router->get(
    '/open-source/updates/page/{page}',
    ViewOpenSourceUpdatesController::class
)->name('open-source.updates.paginated');

$router->group(
    [
        'middleware' => ['auth'],
    ],
    static function (Router $router): void {
        $router->domain(config('nova.domain', null))
            ->post(
                '/ckeditor/upload/image',
                UploadImageThruCKEditorController::class
            )->name('ckeditor.upload.image');
    }
);
