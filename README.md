## Набор интерфейсов для получения orderBundle(ФЗ-54 ФФД 1.05)

Реализует набор интерфейсов для системы заказов, сущности: заказ, товар, клиент.  
Пакет включает реализацию получения orderBundle для платежного шлюза ПАО Сбербанк (онлайн-кассами по ФЗ-54).

Идея унифицирует и расширяет подход пакета [https://github.com/pinguinjkeke/omnipay-paymentgateru] 

Класс заказа должен реализовывать интерфейс ```RFAct54\OrderInterface```
```php
class Order extends EloquentModel implements OrderInterface
{
    // Должен вернуть массив товаров, реализовывающих OrderItemInterface
    public function getItems(): iterable
    {
        return $this->cart;
    }
    
    // Должен вернуть пользователя CustomerInterface
    public function getCustomer(): ?string
    {
        return $this->customer;
    }
    
    public function getCreationDate(): int
    {
        return $order->created_at->getTimestamp();
    }
}
```
Для работы с функционалом доставки, заказ должен реализовывать интерфейс ```RFAct54\OrderDeliverableInterface```.
```php
class Order extends Eloquent implements OrderInterface, OrderDeliverableInterface
{
    // Наименование способа доставки или null
    public function getDeliveryType(): ?string
    {
        $this->delivery->name;
    }
    
    // Двухсимвольный код страны доставки RU, EN
    public function getCountry(): ?string
    {
        return $this->delivery->country;
    }
    
    // Город доставки
    public function getCity(): ?string
    {
        return $this->delivery->city;
    }
    
    // Адрес доставки
    public function getPostAddress(): ?string
    {
        return $this->delivery->address;
    }
}
```
Дополнительные интерфейсы заказа (ФФД 1.05) ```RFAct54\OfdParamsInterface```, ```RFAct54\OfdParamsAgentInfoInterface```, ```RFAct54\OfdParamsSupplierInfoInterface```

Метод заказа ```getCustomer()``` должен возвращать null, если функционал не используется или ```RFAct54\CustomerInterface```.
Для расширения до версии ФФД 1.05 использовать в связке с ```RFAct54\CustomerDetailsInterface```

```php
class User extends Eloquent implements CustomerInterface, [CustomerDetailsInterface]
{
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function getPhone(): ?string
    {
        return preg_replace('/\D/', '', $this->phone);
    }
    
    // Альтернативный способ связи с пользователем
    public function getContact(): ?string
    {
        return "Fax: {$this->user->fax}";
    }

    ......
}
```
Товар должен реализовывать основной интерфейс `RFAct54\ItemInterface`.
Обязательные интерфейсы для ФФД 1.05  `RFAct54\ItemTaxableInterface`,   `RFAct54\ItemAttributesInterface`
Дополнительные интерфейсы (ФФД 1.05) `RFAct54\ItemAgentInterestInterface`, ```RFAct54\OfdParamsInterface```, ```RFAct54\OfdParamsAgentInfoInterface```, ```RFAct54\OfdParamsSupplierInfoInterface```

```php
class CartProduct extends Eloquent implements ItemInterface, [ItemTaxableInterface, ItemAttributesInterface, ItemAgentInterestInterface, OfdParamsInterface, OfdParamsAgentInfoInterface, OfdParamsSupplierInfoInterface]
{
    // Название товара
    public function getName(): string
    {
        return $this->name;
    }
    
    // Артикул товара
    public function getCode()
    {
        return $this->product->article;
    }
    
    // Единицы измерения
    public function getMeasure(): string
    {
        return 'шт.';
    }
    
    // Количество товара
    public function getQuantity(): float
    {
        return $this->quantity;
    }
    
    // Цена на один товар
    public function getPrice(): float
    {
        return $this->product->price;
    }
    
    // Валюта в формате ISO-4217
    // По правилам банка, все товары, переданные в одном заказе должны быть в одной валюте!
    public function getCurrency(): string
    {
        return $this->product->currency;
    }
    
    .....
}
```

##  Пример использования в связке с пакетом `omnipay/common`

К методу авторизации заказа в банке необходимо прикрепить ```RFAct54\OrderBundle```
и в качестве аргумента фабрики передать ид реализации и заказ ```OrderInterface```
```php
//заказ в торговой системе 
$order = Order::find($id); 

$orderBundle = RFAct54\Factory::create('SberbankPay', $order);

$response = Gateway::authorize([
       ....
    ])
    ->setUserName('merchant_login')
    ->setPassword('merchant_password')    
    ->setOrderBundle($orderBundle)
    ->send();
```
