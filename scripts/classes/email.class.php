<?php
require_once dirname(__DIR__) . '/../vendor/autoload.php';
use Mailgun\Mailgun;

class Email
{
    const TYPE_WATER = 'water';
    const TYPE_BEER = 'beer';
    const TYPE_DRINK = 'drinks';
    const TYPE_FRIES = 'fries';
    const TYPE_SALAD = 'salads';
    const TYPE_BURGER = 'burgers';
    const TYPE_SAUCES = 'sauces';

    public function sendEmail($message, $state = true, $order)
    {
        $data = require dirname(__DIR__) . '/params.php';
        $mgClient = new Mailgun($data['mailGun']['apiKey']);
//        $mgClient->sendMessage($data['mailGun']['domain'],
//            [
//                'from'    => "Burger Joint <postmaster@sandbox826ba91f3f2e476dbd8feefea0b862c6.mailgun.org>",
//                'to'      => "alexandr.vasiliev@iqria.com",
//                'subject' => 'Замовлення '.$order,
//                'html'    => ($state)? $this->setMessage($message) : $message,
//            ]);
//        $mgClient->sendMessage($data['mailGun']['domain'],
//            [
//                'from'    => "Burger Joint <postmaster@sandbox826ba91f3f2e476dbd8feefea0b862c6.mailgun.org>",
//                'to'      => "alexandr.sharygin@iqria.com",
//                'subject' => 'Замовлення '.$order,
//                'html'    => ($state)? $this->setMessage($message) : $message,
//            ]);
//        $mgClient->sendMessage($data['mailGun']['domain'],
//            [
//                'from'    => "Burger Joint <postmaster@sandbox826ba91f3f2e476dbd8feefea0b862c6.mailgun.org>",
//                'to'      => "lidiya.chuhlib@iqria.com",
//                'subject' => 'Замовлення '.$order,
//                'html'    => ($state)? $this->setMessage($message) : $message,
//            ]);

        return $mgClient->sendMessage($data['mailGun']['domain'],
            [
                'from'    => "Burger Joint <postmaster@sandbox826ba91f3f2e476dbd8feefea0b862c6.mailgun.org>",
                'to'      => "Burger <{$data['mailGun']['email']}>",
                'subject' => 'Замовлення '.$order,
                'html'    => ($state)? $this->setMessage($message) : $message,
            ]);
    }
    public function setMessage($data)
    {
        $result = '<table border="1">';
        $result .= "<tr>
                        <td>Тип</td>
                        <td>Iмя</td>
                        <td>Деталi</td>
                        <td>Цiна</td>
                        <td>Кiлькiсть</td>
                        <td>Сума</td>
                    </tr>";
        $countBurger = $this->countType($data->ordered, self::TYPE_BURGER);
        $countBeer = $this->countType($data->ordered, self::TYPE_BEER);
        $countSalad = $this->countType($data->ordered, self::TYPE_SALAD);
        $countFries = $this->countType($data->ordered, self::TYPE_FRIES);
        $countDrink = $this->countType($data->ordered, self::TYPE_DRINK);
        $countWater = $this->countType($data->ordered, self::TYPE_WATER);
        $countSauces = $this->countType($data->ordered, self::TYPE_SAUCES);
        $stateBurger = false;
        for ($i=0; $i<count($data->ordered); $i++) {
            $order = $data->ordered[$i];
            if ($order->type == self::TYPE_SAUCES) { // sauces start
                $result .= "<tr>
                                <td rowspan='".$countSauces."'>Соуси</td>
                                <td colspan='2'>{$order->name}</td>
                                <td>{$order->price}</td>
                                <td>{$order->qty}</td>
                                <td>" .($order->qty * $order->price) . "</td>
                            </tr>";
                if ($countSauces > 1) {
                    $countSauces--;
                    while ($countSauces != 0) {
                        $order = $data->ordered[++$i];
                        $result .= "<tr>
                                        <td colspan='2'>{$order->name}</td>
                                        <td>{$order->price}</td>
                                        <td>{$order->qty}</td>
                                        <td>" .($order->qty * $order->price) . "</td>
                                    </tr>";
                        $countSauces--;
                    }
                }
            } elseif ($order->type == self::TYPE_BEER) { // beer start
                $result .= "<tr>
                                <td rowspan='".$countBeer."'>Пиво</td>
                                <td>{$order->name}</td>
                                <td>{$order->details}</td>
                                <td>{$order->price}</td>
                                <td>{$order->qty}</td>
                                <td>" .($order->qty * $order->price) . "</td>
                            </tr>";
                if ($countBeer > 1) {
                    $countBeer--;
                    while ($countBeer != 0) {
                        $order = $data->ordered[++$i];
                        $result .= "<tr>
                                        <td>{$order->name}</td>
                                        <td>{$order->details}</td>
                                        <td>{$order->price}</td>
                                        <td>{$order->qty}</td>
                                        <td>" .($order->qty * $order->price) . "</td>
                                    </tr>";
                        $countBeer--;
                    }
                }
            } elseif ($order->type == self::TYPE_BURGER) { // burger start
                $doneness = $this->donenessToString($order->doneness);
                $currentKey = key($doneness);

                if (!$stateBurger) {
                    $result .= "<tr>
                                    <td rowspan='" . ($this->countBurgers($data->ordered)) . "'>Бургери</td>
                                    <td rowspan='" . count($doneness) . "'>{$order->name}</td>
                                    <td>" . $currentKey . "</td>
                                    <td>{$order->price}</td>
                                    <td>{$doneness[$currentKey]}</td>
                                    <td>" . ($doneness[$currentKey] * $order->price) . "</td>
                                </tr>";
                } else {
                    $result .= "<tr>
                                    <td rowspan='" . count($doneness) . "'>{$order->name}</td>
                                    <td>" . $currentKey . "</td>
                                    <td>{$order->price}</td>
                                    <td>{$doneness[$currentKey]}</td>
                                    <td>" . ($doneness[$currentKey] * $order->price) . "</td>
                                </tr>";
                    $countBurger = count($doneness);
                }
                $stateBurger = true;
                if ($countBurger >= 1) {
                    if (count($doneness) > 1) {
                        unset($doneness[$currentKey]);
                        foreach($doneness as $key => $value) {
                            $result .= "<tr>
                                                <td>{$key}</td>
                                                <td>{$order->price}</td>
                                                <td>{$value}</td>
                                                <td>" .($value * $order->price) . "</td>
                                            </tr>";
                        }

                    }
                }
            } elseif ($order->type == self::TYPE_SALAD) { // salad start
                $result .= "<tr>
                                <td rowspan='".$countSalad."'>САЛАТИ</td>
                                <td colspan='2'>{$order->name}</td>
                                <td>{$order->price}</td>
                                <td>{$order->qty}</td>
                                <td>" .($order->qty * $order->price) . "</td>
                            </tr>";
                if ($countSalad > 1) {
                    $countSalad--;
                    while ($countSalad != 0) {
                        $order = $data->ordered[++$i];

                        $result .= "<tr>
                                        <td colspan='2'>{$order->name}</td>
                                        <td>{$order->price}</td>
                                        <td>{$order->qty}</td>
                                        <td>" .($order->qty * $order->price) . "</td>
                                    </tr>";
                        $countSalad--;
                    }
                }
            } elseif ($order->type == self::TYPE_FRIES) { //fries start
                $result .= "<tr>
                                    <td rowspan='".$countFries."'>КАРТОПЛЯ ФРІ</td>
                                    <td colspan='2'>{$order->name}</td>
                                    <td>{$order->price}</td>
                                    <td>{$order->qty}</td>
                                    <td>" .($order->qty * $order->price) . "</td>
                                </tr>";
                if ($countFries > 1) {
                    $countFries--;
                    while ($countFries != 0) {
                        $order = $data->ordered[++$i];

                        $result .= "<tr>
                                        <td colspan='2'>{$order->name}</td>
                                        <td>{$order->price}</td>
                                        <td>{$order->qty}</td>
                                        <td>" .($order->qty * $order->price) . "</td>
                                    </tr>";
                        $countFries--;
                    }
                }
            } elseif ($order->type == self::TYPE_DRINK) { // drink start
                $result .= "<tr>
                                <td rowspan='".$countDrink."'>НАПОЇ</td>
                                <td>{$order->name}</td>
                                <td>{$order->details}</td>
                                <td>{$order->price}</td>
                                <td>{$order->qty}</td>
                                <td>" .($order->qty * $order->price) . "</td>
                            </tr>";
                if ($countDrink > 1) {
                    $countDrink--;
                    while ($countDrink != 0) {
                        $order = $data->ordered[++$i];

                        $result .= "<tr>
                                        <td>{$order->name}</td>
                                        <td>{$order->details}</td>
                                        <td>{$order->price}</td>
                                        <td>{$order->qty}</td>
                                        <td>" .($order->qty * $order->price) . "</td>
                                    </tr>";
                        $countDrink--;
                    }
                }
            } elseif ($order->type == self::TYPE_WATER) { // water start
                $result .= "<tr>
                                <td  rowspan='".$countWater."'>ВОДА</td>
                                <td>{$order->name}</td>
                                <td>{$order->details}</td>
                                <td>{$order->price}</td>
                                <td>{$order->qty}</td>
                                <td>" .($order->qty * $order->price) . "</td>
                            </tr>";
                if ($countWater > 1) {
                    $countWater--;
                    while ($countWater != 0) {
                        $order = $data->ordered[++$i];
                        $result .= "<tr>
                                        <td>{$order->name}</td>
                                        <td>{$order->details}</td>
                                        <td>{$order->price}</td>
                                        <td>{$order->qty}</td>
                                        <td>" .($order->qty * $order->price) . "</td>
                                    </tr>";
                        $countWater--;
                    }
                }
            }

        }
        $result .= "<tr>
                        <td style='border: none;'></td>
                        <td style='border: none;'></td>
                        <td style='border: none;'></td>
                        <td style='border: none;'></td>
                        <td>Всього</td>
                        <td>{$data->totalPrice->sum}</td>
                    </tr>";
        $result .= '</table>';
        $result .= "Номер телефона - {$data->phoneNumber}<br>";
        $result .= "Адреса - {$data->textMessage}<br>";
        $result .= "Спосiб оплати - {$data->paymentMethod}<br>";
        return (string)$result;
    }

    public function donenessToString($doneness)
    {
        $uniqueDoneness = [];
        foreach($doneness as $item) {
            if (array_key_exists($item, $uniqueDoneness)) {
                $uniqueDoneness[$item]++;
            } else {
                $uniqueDoneness[$item] = 1;
            }
        }
        return $uniqueDoneness;
    }

    public function countType($data, $type)
    {
        $count = 0;
        foreach($data as $item) {
            if ($item->type == $type) {
                $count++;
            }
        }

        return $count;
    }

    public function countBurgers($data)
    {
        $count = 0;
        foreach($data as $item) {
            if ($item->type == self::TYPE_BURGER) {
                $doneness = $this->donenessToString($item->doneness);
                $count += count($doneness);
            }
        }
        return ($count);
    }

    public function postDataToString($dataPost)
    {
        $res = '<table border="1">';
        foreach($dataPost as $key => $value) {
            $res .= "<tr>
                        <td>{$key}</td>
                        <td>{$value}</td>
                    </tr>";
        }
        $res .= '</table>';

        return $res;
    }
}
