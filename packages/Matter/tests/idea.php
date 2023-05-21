<?php
use Evident\Matter\Driver\PDO\Driver as PDODriver;
use Evident\Matter\DataSource\RemoteDataSetInterface;

$pdo = new PDO("sqlite:./database.sql");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$driver = new PDODriver($pdo);


$contacts = $driver->from('contacts');
$orders = $driver->from('orders');

$query = $contacts
    ->combine($orders, fn($c, $o) => $order->contactId == $contact->id)
    ->map(fn($contact, $order) => (object) [
        'contactId' => $contact->id,
        'salesOrderId' => $order->salesOrderId,
        'firstName' => $contact->firstname,
        'lastName' => $contact->lastname,
        'TotalDue' => $order->TotalDue,
    ])
    ->groupBy(fn($record) => $record->contactId);
