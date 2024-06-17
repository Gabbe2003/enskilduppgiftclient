<?php
$productId = $_GET['productId'] ?? '';
$format = $_GET['format'] ?? 'xml';  

if (empty($productId)) {
    die('Product ID is required.');
}

$url = "http://localhost:9000/getOneProduct/" . $productId;

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => ["cache-control: no-cache"],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
    exit;
} else {
    $product = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($product)) {
        echo "Error fetching product data or invalid product ID.";
        exit;
    }
}

if ($format == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="product_' . $productId . '.csv"');
    echo generate_csv_from_products([$product]); 
} else {
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="product_' . $productId . '.xml"');
    echo generate_xml_from_products([$product]); 
}

function generate_xml_from_products($products) {
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products></products>');
    foreach ($products as $product) {
        $product_node = $xml->addChild('product');
        $product_node->addChild('id', htmlspecialchars($product['_id']));
        $product_node->addChild('name', htmlspecialchars($product['name']));
        $product_node->addChild('description', htmlspecialchars($product['description'] ?? 'No description available'));
        $product_node->addChild('price', htmlspecialchars($product['price']));
        $product_node->addChild('imageUrl', htmlspecialchars($product['imageUrl']));
    }
    return $xml->asXML();
}

function generate_csv_from_products($products) {
    ob_start();
    $csvOutput = fopen('php://output', 'w');
    fputcsv($csvOutput, ['ID', 'Name', 'Description', 'Price', 'Image URL']);
    foreach ($products as $product) {
        fputcsv($csvOutput, [
            htmlspecialchars($product['_id']),
            htmlspecialchars($product['name']),
            htmlspecialchars($product['description'] ?? 'No description available'),
            htmlspecialchars($product['price']),
            htmlspecialchars($product['imageUrl'])
        ]);
    }
    fclose($csvOutput);
    return ob_get_clean();
}
?>
