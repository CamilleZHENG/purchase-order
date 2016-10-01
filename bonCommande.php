<?php

include 'utilities.php';

$orderNumber = $_GET['orderNumber'];

$sql = '
        SELECT
              customerName,
              contactLastName,
              contactFirstName,
              phone,
              addressLine1,
              addressLine2,
              city,
              state,
              postalCode,
              country
        FROM
              customers
        JOIN
              orders
        ON
              customers.customerNumber = orders.customerNumber
        AND
              orderNumber = ?
              ;
      ';
$query = $bdo -> prepare($sql);
$query -> execute([$orderNumber]);
$customerInfos = $query -> fetch(PDO::FETCH_ASSOC);
//var_dump($customerInfos);

$sql = '
          SELECT
                productName,
                priceEach,
                quantityOrdered,
                (priceEach*quantityOrdered) AS prixTotal
          FROM
                orderdetails
          JOIN
                products
          ON
                products.productCode = orderdetails.productCode
          AND
                orderNumber = ?
                ;
      ';
$query = $bdo -> prepare($sql);
$query -> execute([$orderNumber]);
$commande_products = $query -> fetchAll(PDO::FETCH_ASSOC);
//var_dump($commande_products);

$sql ='
      SELECT
            SUM(priceEach*quantityOrdered) AS montantHT
      FROM
            orderdetails
      WHERE
            orderNumber = ?
            ;
      ';
$query = $bdo -> prepare($sql);
$query -> execute([$orderNumber]);
$sum = $query -> fetch(PDO::FETCH_ASSOC);
$montantHT = $sum['montantHT'];
$montantHT = number_format($montantHT,2, '.', ' ');

$montantTVA = $montantHT*0.2;
$montantTVA = number_format($montantTVA,2, '.', ' ');

$montantTTC = $montantHT + $montantTVA;
$montantTTC = number_format($montantTTC,2, '.', ' ');

//var_dump($sum, $montantHT, $montantTVA, $montantTTC);



include 'bonCommande.phtml';
