<?php

namespace App\Controller;

use App\Application\RevolutApi\Response\OrderPlacedResponse;
use App\Entity\Order;
use App\Event\OnOrderPlacedEvent;
use App\Form\OrderCreateType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/order')]
class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly EventDispatcherInterface $dispatcher
    ) {}

    #[Route('/', name: 'order_list')]
    public function list(): Response
    {
        $orders = $this->orderRepository->findAll();

        return $this->render('page/order/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/create', name: 'order_create')]
    public function create(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderCreateType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $orderId = Uuid::v4();
            $this->orderRepository->save($order, true, $orderId);

            $this->dispatcher->dispatch(new OnOrderPlacedEvent($orderId), OnOrderPlacedEvent::NAME);

            $this->addFlash('success', 'Order has been created');

            return $this->redirectToRoute('order_list');
        }

        return $this->render('page/order/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
