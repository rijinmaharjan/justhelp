<?php
require '../config/function.php';

if (isset($_POST['saveUser'])) {

    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $is_ban = isset($_POST['is_ban']) ? 1 : 0;
    $role = validate($_POST['role']);

    if ($name != '' && $email != '' && $phone != '' && $password != '') {

        $query = "INSERT INTO users (name, phone, email, password, is_ban, role)
                  VALUES ('$name','$phone','$email','$password','$is_ban','$role')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            redirect('users.php', 'User/Admin Added Successfully');
        } else {
            redirect('users-create.php', 'Something Went Wrong');
        }

    } else {
        redirect('users-create.php', 'Please fill all input fields');
    }
}

if (isset($_POST['updateUser'])) {
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $is_ban = isset($_POST['is_ban']) ? 1 : 0;
    $role = validate($_POST['role']);

    $userId = validate($_POST['userId']);

    $user = getById('users', $userId);
    if ($user['status'] != 200) {
        redirect('users-edit.php?id=' . $userId, 'No such id found');
    }

    if ($name != '' && $email != '' && $phone != '' && $password != '') {

        $query = "UPDATE users SET 
        name='$name',
        phone='$phone',
        email='$email',
        password='$password',
        is_ban='$is_ban',
        role='$role'
        WHERE id='$userId' ";

        $result = mysqli_query($conn, $query);

        if ($result) {
            redirect('users.php?id=' . $userId, 'User/Admin Updated Successfully');
        } else {
            redirect('users-create.php', 'Something Went Wrong');
        }

    } else {
        redirect('users-create.php', 'Please fill all input fields');
    }
}


//====product add ===============================
if (isset($_POST['saveProduct'])) {

    if ($_FILES['image']['size'] > 0) {
        $image = $_FILES['image']['name'];

        $path = "../assets/uploads/products/";
        $imgExt = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time() . '.' . $imgExt;

        $finalImage = 'assets/uploads/products/' . $filename;
    } else {
        $finalImage = NULL;
    }

    $name = validate($_POST['name']);
    $price = validate($_POST['price']);
    $description = validate($_POST['description']);

    if (isset($_POST['size'])) {
        if (is_array($_POST['size'])) {
            $size = implode(',', $_POST['size']); // multiple sizes
        } else {
            $size = validate($_POST['size']); // single size fallback
        }
    } else {
        $size = '';
    }

    $status = isset($_POST['status']) ? '1' : '0';

    $query = "INSERT INTO products 
              (image, name, price, size, description, status) 
              VALUES 
              ('$finalImage', '$name', '$price', '$size', '$description', '$status')";

    $result = mysqli_query($conn, $query);

    if ($result) {
        if ($_FILES['image']['size'] > 0) {
            move_uploaded_file($_FILES['image']['tmp_name'], $path . $filename);
        }
        redirect('products.php', 'Product Added Successfully');
    } else {
        redirect('products.php', 'Something Went Wrong!');
    }
}



//==== Update ===========

if (isset($_POST['updateProduct'])) {

    $productID = validate($_POST['productId']);
    $product = getById('products', $productID);

    if ($_FILES['image']['size'] > 0) {
        $image = $_FILES['image']['name'];
        $path = "../assets/uploads/products/";

        $deleteImage = "../" . $product['data']['image'];
        if (file_exists($deleteImage)) {
            unlink($deleteImage);
        }

        $imgExt = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time() . '.' . $imgExt;
        $finalImage = 'assets/uploads/products/' . $filename;
    } else {
        $finalImage = $product['data']['image'];
    }

    $name = validate($_POST['name']);
    $price = validate($_POST['price']);
    $description = validate($_POST['description']);

    $size = '';
    if (isset($_POST['size'])) {
        if (is_array($_POST['size'])) {
            $size = implode(',', $_POST['size']);
        } else {
            $size = validate($_POST['size']);
        }
    }

    $status = isset($_POST['status']) ? '1' : '0';

    $query = "UPDATE products SET 
                image = '$finalImage',
                name = '$name',
                price = '$price',
                size = '$size',
                description = '$description',
                status = '$status'
              WHERE id = '$productID' ";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if ($_FILES['image']['size'] > 0) {
            move_uploaded_file($_FILES['image']['tmp_name'], $path . $filename);
        }
        redirect('products-edit.php?id=' . $productID, 'Product Updated Successfully');
    } else {
        redirect('products-edit.php?id=' . $productID, 'Something Went Wrong!');
    }
}

?>