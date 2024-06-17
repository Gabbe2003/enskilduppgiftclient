<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture data from the form
    $name = $_POST['name'];
    $description = $_POST['description'] ?? 'No description is availble'; 
    $price = floatval($_POST['price']);
    $imageUrl = $_POST['imageUrl'] ?? '';

    // Data array
    $data = [
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'imageUrl' => $imageUrl
    ];

    // Initialize cURL session
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://localhost:9000/createProduct",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "cache-control: no-cache"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response; 
    }
}
?>


<div class="container mt-5">
    <h2>Create New Product</h2>
    <form action="index.php" method="post" class="mt-3">
        <div class="mb-3">
            <label for="name" class="form-label">Name *</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price *</label>
            <input type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="imageUrl" class="form-label">Image URL</label>
            <input type="url" class="form-control" id="imageUrl" name="imageUrl">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
