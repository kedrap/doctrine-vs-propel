<?php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\StoreBundle\Model\ProductQuery;
use Acme\StoreBundle\Model\CategoryQuery;
use Doctrine\Common\Util\Debug;

class DefaultController extends Controller
{
    public function doctrineAction()
    {
        echo "<pre>";

        $products = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Product')
            ->findOneById(1);
        $this->dump($products, 'Doctrine Product');

        $products = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select('p, c')
            ->from('AcmeStoreBundle:Product', 'p')
            ->leftJoin('p.category', 'c')
            ->where('p = :p')
            ->setParameter('p', 2)
            ->getQuery()
            ->getSingleResult();
        $this->dump($products, 'Doctrine Product join Category');

        $categories = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Category')
            ->findOneById(1);
        $this->dump($categories, 'Doctrine Category');

        exit;
        return $this->render('AcmeStoreBundle:Default:index.html.twig');
    }

    public function propelAction()
    {
        echo "<pre>";

        $products = ProductQuery::create()
            ->findOneById(1);
        $this->dump($products, 'Propel Product');

        $products = ProductQuery::create()
            ->joinWithCategory()
            ->findOneById(2);
        $this->dump($products, 'Propel Product join Category');

        $categories = CategoryQuery::create()
            ->findOneById(1);
        $this->dump($categories, 'Propel Category');

        exit;
        return $this->render('AcmeStoreBundle:Default:index.html.twig');
    }

    protected function dump($data, $title)
    {
        echo '<hr>' . $title . '<hr>';
//        var_dump($data);
        Debug::dump($data, 10, false);
    }
}
