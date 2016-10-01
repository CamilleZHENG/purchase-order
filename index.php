<?php

include 'utilities.php';

//var_dump($bdo);

$sql = '
          SELECT
                orderNumber,
                orderDate,
                shippedDate,
                status
          FROM
                orders
          ORDER BY
                orderDate
                ;
          ';
$query = $bdo -> prepare($sql);
$query -> execute();
$orders = $query->fetchAll(PDO::FETCH_ASSOC);

//var_dump($orders);



include 'index.phtml';
