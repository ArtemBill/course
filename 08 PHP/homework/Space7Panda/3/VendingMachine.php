<?php

interface VendingMachineInterface 
{
    public function vend($code, $cash);
}

interface DefaultProductInterface
{
    public function checkCode($code);
    public function getName($code);
    public function getQuantity($code);
    public function getPrice($code);
    public function decreaseQuantity($code);
}

class DefaultProduct implements DefaultProductInterface 
{
    public $items;

    public function __construct($items) {
        $this->items = $this->sortArr($items);
    }

    public function sortArr($arr) 
    {
        $sortedArr = array();

        foreach ($arr as $key => $value) {
            $sortedArr[$value["code"]] = $value; 
        }

        return $sortedArr;
    }

    public function checkCode($code)
    {
        if ($this->items[$code]) {
            return true;
        }

        return false;
    }

    public function getName($code) 
    {
        return $this->items[$code]["name"];
    }

    public function getQuantity($code) 
    {
        return $this->items[$code]["quantity"];
    }

    public function getPrice($code) 
    {
        return $this->items[$code]["price"];
    }

    public function decreaseQuantity($code)
    {
        $this->items[$code]["quantity"] -= 1;
    }
    
    public function getItems()
    {
        return $this->items;
    }
}

class VendingMachine implements VendingMachineInterface
{
    public $product;
    public $vendCash; 

    public function __construct($product) {
        $this->product = $product;
        $this->vendCash = 0;
    }

    public function addCash($cash)
    {
        $this->vendCash += $cash;
    }

    public function vend($code, $cash) 
    {
        if ($this->product->checkCode($code) == false) {
            echo "Такого товара нет в автомате!\n";

            return;
        }

        $name = $this->product->getName($code);
        $quantity = $this->product->getQuantity($code);
        $price = $this->product->getPrice($code);

        if ($quantity <= 0) {

            echo "$name закончился!\n";

            return;
        }

        if ($cash < $price) {

            echo 'Недостаточно денег!' . "\n";
        
            return;
        }

        if ($cash == $price) {
            $this->addCash($cash);
            $this->product->decreaseQuantity($code);

            echo "Возьмите $name\n";
            return;
        }

        if ($cash > $price) {
            $this->addCash($price);
            $this->product->decreaseQuantity($code);

            $change = $cash - $price;

            echo "Возьмите $name. Ваша сдача - $change\n";
            return;
        }

    }

    public function showCash()
    {
        echo "В торгомате " . $this->vendCash . " валюты.\n";
    }

    public function showItems()
    {
        $items = $this->product->getItems();

        foreach ($items as $key=>$value) {

            echo $items[$key]['name']
                . " "
                . $items[$key]['quantity']
                . "шт.\n";
        }
    }
}