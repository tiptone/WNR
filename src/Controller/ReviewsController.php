<?php

namespace Tiptone\Mvc\Controller;

use Tiptone\Mvc\Model\Review;
use Tiptone\Mvc\Service\ReviewService;
use Tiptone\Mvc\View\FragmentView;
use Tiptone\Mvc\View\JsonView;
use Tiptone\Mvc\View\View;

class ReviewsController extends AbstractController
{
    /**
     * @var ReviewService
     */
    protected $reviewService;

    public function __construct(ReviewService $reviewService) {
        $this->reviewService = $reviewService;
    }

    public function autocompleteAction() {
        $this->log->info($_GET['term']);

        $review = [];
        $out = [];

        try {
            $reviews = $this->reviewService->findFuzzyMatches($_GET['term']);
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            // @todo: HANDLE ERROR
        }

        foreach ($reviews as $review) {
            if (!in_array($review->whiskey, $out)) {
                $out[] = $review->whiskey;
            }
        }

        $view = new JsonView();
        $view->setVariable('json', $out);

        return $view;
    }

    public function userautocompleteAction() {
        $this->log->info($_GET['term']);

        $reviews = [];
        $out = [];

        try {
            $reviews = $this->reviewService->findFuzzyUser($_GET['term']);
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            // @todo: HANDLE ERROR
        }

        foreach ($reviews as $review) {
            if (!in_array($review->reviewer, $out)) {
                $out[] = $review->reviewer;
            }
        }

        $view = new JsonView();
        $view->setVariable('json', $out);

        return $view;
    }

    public function reviewerAction() {
        $this->log->info($_GET['id']);

        $reviews = [];

        try {
            $reviews = $this->reviewService->findByReviewer($_GET['id']);
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            // @todo: HANDLE ERROR
        }

        $view = new View();
        $view->setVariable('reviewer', $_GET['id']);
        $view->setVariable('reviews', $reviews);

        return $view;
    }

    public function searchAction() {
        $search = $_POST['search'];
        $this->log->info($search);

        $matches = [];
        $ratings = [];

        try {
            $matches = $this->reviewService->findByName($search);
        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
            // @todo: HANDLE ERROR
        }

        foreach ($matches as $match) {
            if (trim($match->rating) != '') {
                $ratings[] = $match->rating;
            }
        }

        $count = count($ratings);
        $sum = array_sum($ratings);
        $mean = $sum / $count;

        rsort($ratings);
        $middle = round(count($ratings) / 2);
        $median = $ratings[$middle-1];

        $v = array_count_values($ratings);
        arsort($v);
        foreach ($v as $k => $v) { $mode = $k; break; }

        $view = new FragmentView();
        $view->setVariable('search', $search);
        $view->setVariable('matches', $matches);
        $view->setVariable('high', max($ratings));
        $view->setVariable('low', min($ratings));
        $view->setVariable('mean', $mean);
        $view->setVariable('median', $median);
        $view->setVariable('mode', $mode);
        $view->setVariable('count', count($ratings));

        return $view;
    }

    public function aboutAction() {
        return new View();
    }

    public function userAction() {
        return new View();
    }
}
