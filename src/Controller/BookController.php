<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Borrower;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/', name: 'book_list')]
    public function list(EntityManagerInterface $em): Response {
        $book = $em->getRepository(Book::class);
        $books = $book->findAll();
        return $this->render('book/index.html.twig', ['books' => $books]);
    }

    #[Route('/add', name: 'book_add')]
    public function add(EntityManagerInterface $em): Response {
        $book = new Book();
        $category = ($em->getRepository(Category::class))->find(2);
        $book->setTitle("Nouveau livre")->setStatus(0)->addCategory($category);

        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute("book_list");
    }

    #[Route('/delete_{id}', name: 'book_delete')]
    public function deleteProduct(Book $book, EntityManagerInterface $em): Response {
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute("book_list");
    }

    #[Route('/borrower/{id}', name: 'book_add')]
    public function booked(Book $book, EntityManagerInterface $em): Response {
        $borrower = ($em->getRepository(Borrower::class))->find(2);
        $book->setStatus(1)->addBorrower($borrower);

        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute("book_list");
    }

    #[Route('/return/{id}', name: 'book_add')]
    public function returnBook(Book $book, EntityManagerInterface $em): Response {
        $book->setStatus(0)->addBorrower(null);

        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute("book_list");
    }
}
