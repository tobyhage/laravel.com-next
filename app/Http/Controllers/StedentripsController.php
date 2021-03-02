<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Stedentrip;
use Symfony\Component\DomCrawler\Crawler;

class StedentripsController extends Controller
{
    /**
     * The documentation repository.
     *
     * @var \App\Stedentrip
     */
    protected $docs;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Stedentrip  $docs
     * @return void
     */
    public function __construct(Stedentrip $docs)
    {
        $this->docs = $docs;
    }

    /**
     * Show the root documentation page (/docs).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showRootPage()
    {
        return redirect('stedentrips/');
    }

    /**
     * Show a documentation page.
     *
     * @param  string  $continent
     * @param  string  $page
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($continent, $page)
    {
        $sectionPage = $page ?: 'installation';
        $content = $this->docs->get($continent, $sectionPage);

        $title = (new Crawler($content))->filterXPath('//h1');

        $section = '';

        if ($this->docs->sectionExists($continent, $page)) {
            $section .= '/'.$page;
        } elseif (! is_null($page)) {
            return redirect('/docs/'.$continent);
        }

        $canonical = null;

        if ($this->docs->sectionExists(DEFAULT_VERSION, $sectionPage)) {
            $canonical = 'docs/'.DEFAULT_VERSION.'/'.$sectionPage;
        }

        return view('stedentrips', [
            'title' => count($title) ? $title->text() : null,
            'index' => $this->docs->getIndex($continent),
            'content' => $content,
            'currentSection' => $section,
            'canonical' => $canonical,
        ]);
    }
}
