<?php

$options = getopt('i:o:');

if (!isset($options['i']) || !isset($options['o'])) {
    echo "Error: input file (CSV) and output filename must be supplied." . PHP_EOL;
    echo "  usage: php reviews.php -- -i reviews.csv -o reviews.db" . PHP_EOL;
    exit;
}

$infile  = $options['i'];
$outfile = $options['o'];

$row = 1;

$db = new SQLite3($outfile);

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

if (($handle = fopen($infile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
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
    fclose($handle);
}

echo "Processed $row records" . PHP_EOL;
