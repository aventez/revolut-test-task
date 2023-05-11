<?php

namespace App\Event;
use App\Application\RevolutApi\Client\RevolutClientInterface;
use App\Enum\OrderStatus;
use App\Mailer\MailerService;
use App\Repository\OrderRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/* TODO:
 * Although it may be beneficial for the system's scalability and consistency
 * to use a solid message queue implementation like RabbitMQ or Kafka, due to
 * time constraints I have opted for an alternative solution for message passing.
 * While this approach may not offer the same level of reliability and scalability,
 * I believe it is a reasonable tradeoff given the project's timeline.
 */
class OnOrderPlacedEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly RevolutClientInterface $revolutClient,
        private readonly LoggerInterface $logger,
        private readonly MailerService $mailerService,
    ) {
    }

    public function onOrderPlaced(OnOrderPlacedEvent $event): void
    {
        $order = $this->orderRepository->find($event->id);
        if (!$order) throw new NotFoundHttpException();

        try {
            $this->orderRepository->updateStatus($order, OrderStatus::PROCESSING);

            $this->revolutClient->createOrder(
                $order->getAmount(),
                $order->getCurrency(),
                $order->getEmail()
            );

            $mailTitle = sprintf('Deposit %s', $order->getCurrency());
            $mailBody = sprintf('Successfully placed an order for %0.2f %s.',
                $order->getAmount(),
                $order->getCurrency()
            );
            $this->mailerService->sendMail(
                'test@epam.com',
                $order->getEmail(),
                $mailTitle,
                $mailBody,
            );

            $this->orderRepository->updateStatus($order, OrderStatus::COMPLETED);
        } catch(Exception $e) {
            $order->setStatus(OrderStatus::FAILED);
            $this->orderRepository->save($order, true);

            $this->logger->error(sprintf('An error occured while trying to process OnOrderPlacedEvent. Message: %s', $e->getMessage()));
        }
    }


    public static function getSubscribedEvents()
    {
        return [
            OnOrderPlacedEvent::NAME => 'onOrderPlaced',
        ];
    }
}
