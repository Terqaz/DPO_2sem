<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Контроллер для книг
#[Route('/books')]
class BookController extends AbstractController
{
    /** Получение каталога книг
     * @param $bookRepository - репозиторий для книг
     * @return Response - HTTP-ответ. Cтраница с каталогом книг
     */
    #[Route('/', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findBy([], ['dateRead' => 'DESC']),
        ]);
    }

    /** Создать новую книгу
     * @param $request - HTTP-запрос
     * @param $bookRepository - репозиторий для книг
     * @return Response - HTTP-ответ. Форма для создания книги или редирект на обработчик для получения каталога книг
     */
    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookRepository $bookRepository): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->add($book, true);

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /** Получение страницы с подробной информацией о книге
     * @param $book - экземпляр книги с id из url
     * @return Response - HTTP-ответ. Cтраница с подробной информацией о книге
     */
    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /** Получение страницы с формой редактирования книги
     * @param $request - HTTP-запрос
     * @param $book - экземпляр книги с id из url
     * @param $bookRepository - репозиторий для книг
     * @return Response - HTTP-ответ. Форма для редактирования книги или редирект на обработчик для получения каталога книг
     */
    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->add($book, true);

            return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('book/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    /** Удаление книги
     * @param $request - HTTP-запрос
     * @param $book - экземпляр книги с id из url
     * @param $bookRepository - репозиторий для книг
     * @return Response - HTTP-ответ. Редирект на обработчик для получения каталога книг
     */
    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $bookRepository->remove($book, true);
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
