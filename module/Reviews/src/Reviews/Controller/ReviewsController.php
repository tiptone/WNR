<?
namespace Reviews\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ReviewsController extends AbstractActionController
{
    protected $reviewsTable;

    public function indexAction()
    {
        return new ViewModel(array(
        ));
    }

    public function aboutAction()
    {
        return array();
    }

    public function userAction()
    {
        return array();
    }

    public function autocompleteAction()
    {
        $search = $this->params()->fromQuery('term', 'mellow');

        $brands = $this->getReviewsTable()->findFuzzyMatches($search);

        $out = array();

        foreach ($brands as $brand) {
            if (!in_array($brand->whiskey, $out)) {
                $out[] = $brand->whiskey;
            }
        }
        return new JsonModel($out);
    }

    public function userautocompleteAction()
    {
        $search = $this->params()->fromQuery('term', 'texacer');

        $users = $this->getReviewsTable()->findUserMatches($search);

        $out = array();

        foreach ($users as $user) {
            if (!in_array($user->reviewer, $out)) {
                $out[] = $user->reviewer;
            }
        }

        return new JsonModel($out);
    }

    public function reviewerAction()
    {
        $reviewer = $this->params()->fromRoute('id', 'TheWhiskeyJug');

        $vm = new ViewModel();

        $reviews = $this->getReviewsTable()->findByReviewer($reviewer);

        $vm->setVariable('reviews', $reviews);
        $vm->setVariable('reviewer', $reviewer);

        return $vm;
    }

    public function searchAction()
    {
        $search = $this->params()->fromPost('search');

        $vm = new ViewModel();

        $matches = $this->getReviewsTable()->findByName($search);

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

        $vm->setTerminal($this->getRequest()->isXmlHttpRequest());

        $vm->setVariable('search', $search);
        $vm->setVariable('matches', $matches);
        $vm->setVariable('high', max($ratings));
        $vm->setVariable('low', min($ratings));
        $vm->setVariable('mean', $mean);
        $vm->setVariable('median', $median);
        $vm->setVariable('mode', $mode);
        $vm->setVariable('count', count($ratings));

        return $vm;
    }

    public function getReviewsTable()
    {
        if (!$this->reviewsTable) {
            $sm = $this->getServiceLocator();
            $this->reviewsTable = $sm->get('Reviews\Model\ReviewTable');
        }
        return $this->reviewsTable;
    }
}
