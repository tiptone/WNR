<?php

$infile = 'https://docs.google.com/spreadsheets/d/1X1HTxkI6SqsdpNSkSSivMzpxNT-oeTbjFFDdEkXD30o/gviz/tq?tqx=out:csv&sheet=Review+Archive';

$today = date('Y-m-d');

$row = 1;

$db = new SQLite3(sprintf("reviews-%s.db", $today));

$sql = "CREATE TABLE reviews (
  timestamp text,
  whiskey text,
  reviewer text,
  url text,
  rating integer,
  region text,
  price text,
  date text
)";

$db->exec($sql);

$sql = "insert into reviews values (:timestamp, :whiskey, :reviewer, :url, :rating, :region, :price, :date)";

$stmt = $db->prepare($sql);

$lines = file($infile);
array_shift($lines);

foreach ($lines as $line) {
    $data = str_getcsv($line);
    $row++;

    $stmt->bindValue(':timestamp', trim($data[0]));
    $stmt->bindValue(':whiskey', trim($data[1]));
    $stmt->bindValue(':reviewer', trim($data[2]));
    $stmt->bindValue(':url', trim($data[3]));
    $stmt->bindValue(':rating', trim($data[4]));
    $stmt->bindValue(':region', trim($data[5]));
    $stmt->bindValue(':price', trim($data[6]));
    $stmt->bindValue(':date', trim($data[7]));

    $stmt->execute();

    $stmt->clear();
}

echo "Processed $row records" . PHP_EOL;

