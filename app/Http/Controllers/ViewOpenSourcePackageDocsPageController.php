<?php

namespace BabDev\Http\Controllers;

use BabDev\Models\Package;
use BabDev\Services\DocumentationProcessor;
use BabDev\Services\Exceptions\PageNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ViewOpenSourcePackageDocsPageController
{
    /**
     * @return RedirectResponse|View
     */
    public function __invoke(Package $package, string $version, string $slug, DocumentationProcessor $documentationProcessor)
    {
        // Redirect if package does not have docs
        if (!$package->has_documentation) {
            return redirect()->route('open-source.packages');
        }

        abort_unless($package->hasDocsVersion($version), 404);

        // Don't allow the index (sidebar contents) to be requested
        abort_if($slug === 'index', 404);

        try {
            $page = $documentationProcessor->renderPage($package, $package->mapDocsVersionToGitBranch($version), $slug);
        } catch (PageNotFoundException $exception) {
            throw new NotFoundHttpException($exception->getMessage(), $exception);
        }

        $sidebar = $documentationProcessor->renderPage($package, $package->mapDocsVersionToGitBranch($version), 'index');

        $title = (new Crawler($page))->filterXPath('//h1');

        return view(
            'open_source.packages.docs_page',
            [
                'package' => $package,
                'page' => $page,
                'sidebar' => $sidebar,
                'title' => count($title) ? $title->text() : null,
            ]
        );
    }
}
