<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\AllegroAccount;
use App\Entity\Order;
use App\Enum\Currency;
use App\Form\Transformers\CentsToPriceTransformer;
use App\Form\Transformers\MultiplyNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class OrderCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'app.order.create.form.email'
            ])
            ->add('amount', MoneyType::class, [
                'label' => 'app.order.create.form.amount',
                'divisor' => 100,
                'scale' => 2,
                'html5' => true,
                'attr' => array(
                    'step' => '.01',
                ),
                'currency' => null,
            ])
            ->add('currency', ChoiceType::class, [
                'label' => 'app.order.create.form.currency',
                'choices' => Currency::array(),
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'app.order.create.form.submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}