<?php
require '../inc/dbcon.php';

function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
    ];
    header("HTTP/1.0 422 Unprocessable Entity");
    echo json_encode($data);
    exit();
}

function storeCustomer($customerInput)
{
    global $conn;
    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $instock = mysqli_real_escape_string($conn, $customerInput['in_stock']);
    if(empty(trim($name))){
        return error422('Enter your name');
    }elseif(empty(trim($instock))){
        return error422('Enter your instock');
    } else {
        $query = "INSERT INTO products (name,in_stock) VALUES ('$name','$instock')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $data = [
                'status' => 201,
                'message' => 'Product Created Successfully',
            ];
            header("HTTP/1.0 201 Created");
            return json_encode($data);


        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);

        }
    }
}
function getCustomerList()
{
    global $conn;

    $query = "SELECT * FROM products";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        if(mysqli_num_rows($query_run) > 0){
            $res = mysqli_fetch_all($query_run,  MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'Product List Fetch Successfully',
                'data' => $res
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
        } else {
            $data = [
                'status' => 404,
                'message' => 'No Products Found',
            ];
            header("HTTP/1.0 404 No Products Found");
            return json_encode($data);

        }
    } else {
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }

}
?>