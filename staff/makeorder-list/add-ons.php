<?php
    session_start(); //starts the session
    if($_SESSION['user']){ // checks if the user is logged in
    } else {
        header("location: login.php"); // redirects if user is not logged in
    }

    $user = $_SESSION['user']; //assigns user value
    

    // if session not logged in OR accountype not admin go to login
    if ($_SESSION["account_type"] !== "staff") {
        header("Location: ../../index.php");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="../../styles/makeorder-list.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Zen+Kaku+Gothic+Antique%3A400%2C700"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Zen+Kaku+Gothic+New%3A400"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins%3A400"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro%3A400"/>
    <script src="https://kit.fontawesome.com/99316c4bd5.js" crossorigin="anonymous"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateCartUI(); // Ensure cart UI is updated on page load
            var productRows = document.querySelectorAll('.product-list table tr');
            var cartList = document.getElementById('cart-list');
            var amountReceivedInput = document.getElementById('amount-received');
            var totalAmountElement = document.getElementById('total-amount');
            var changeAmountElement = document.getElementById('change-amount');    

            productRows.forEach(function (row) {
                var addButton = row.querySelector('.button-plus');
                var minusButton = row.querySelector('.button-minus');

                if (addButton) {
                    addButton.addEventListener('click', function () {
                        addToCart(row);
                    });
                }

                if (minusButton) {
                    minusButton.addEventListener('click', function () {
                        removeFromCart(row);
                    });
                }
            });

            document.addEventListener('DOMContentLoaded', function () {
                updateCartUI();
            });

            function addToCart(row) {
                var productName = row.querySelector('td:first-child').textContent;
                var productPrice = parseFloat(row.querySelector('td:nth-child(2)').textContent);

                // AJAX call to update server-side session with cart data
                var addPromise = new Promise(function (resolve) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../../config/update_cart.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    var data = 'action=add&productName=' + encodeURIComponent(productName + 'Add On') + '&productPrice=' + productPrice;
                    xhr.onload = function () {
                        resolve();
                    };
                    xhr.send(data);
                });

                addPromise.then(function () {
                    console.log('Adding from cart...', productName, productPrice);
                    updateCartUI();

                    // Clear the amount received input box
                    amountReceivedInput.value = ''
                    changeAmountElement.textContent = '₱0.00';

                    // Clear the discount input and amount
                    document.getElementById('discount-code').value = '';
                    document.getElementById('discounted-amount').textContent = '0.00';
                });
            }

            function removeFromCart(row) {
                var productName = row.querySelector('td:first-child').textContent;

                // AJAX call to update server-side session with cart data
                var removePromise = new Promise(function (resolve) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../../config/update_cart.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    var data = 'action=remove&productName=' + encodeURIComponent(productName);
                    xhr.onload = function () {
                        resolve();
                    };
                    xhr.send(data);
                });

                removePromise.then(function () {
                    console.log('Removing from cart...');
                    updateCartUI();

                    // Clear the amount received input box
                    amountReceivedInput.value = '';
                    changeAmountElement.textContent = '₱0.00';

                    // Clear the discount input and amount
                    document.getElementById('discount-code').value = '';
                    document.getElementById('discounted-amount').textContent = '0.00';
                });
            }

            function removeFromCartByName(productName) {
                // AJAX call to remove the item from the cart on the server
                var removePromise = new Promise(function (resolve) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '../../config/update_cart.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    var data = 'action=remove&productName=' + encodeURIComponent(productName);
                    xhr.onload = function () {
                        resolve();
                    };
                    xhr.send(data);
                });

                removePromise.then(function () {
                    console.log('Removing item from cart:', productName);
                    updateCartUI();

                    // Clear the amount received input box
                    amountReceivedInput.value = '';
                    changeAmountElement.textContent = '₱0.00';

                    // Clear the discount input and amount
                    document.getElementById('discount-code').value = '';
                    document.getElementById('discounted-amount').textContent = '0.00';
                });
            }
            
            function updateCartUI() {
                // AJAX call to fetch the current cart information
                console.log('Updating cart UI...');
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '../../config/get_cart.php', true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var cartData = JSON.parse(xhr.responseText);

                        // Update the cart UI in the cart-head section
                        updateCartHead(cartData);

                        // Update the total amount in the cart-foot section
                        updateTotalAmount(cartData);
                    }
                };
                xhr.send();
            }

            function updateCartHead(cartData) {
                // Clear the existing cart list
                cartList.innerHTML = '';

                // Iterate through each item in the cart and update the UI
                for (var i = 0; i < cartData.length; i++) {
                    var item = cartData[i];
                    var listItem = document.createElement('li');
                    var minusButton = document.createElement('button');

                    minusButton.textContent = '-';
                    minusButton.classList.add('minus-btn');
                    listItem.textContent = item.productName + ' x' + item.quantity + ' - ₱' + (item.productPrice * item.quantity).toFixed(2);
                    
                    // Add event listener to the minus button to remove the item
                    minusButton.addEventListener('click', function (productName) {
                        return function(event) {
                            event.stopPropagation(); // Prevent the click event from propagating to the parent elements
                            removeFromCartByName(productName); // Remove the item
                        };
                    }(item.productName));


                    // Append the minus button and list item to the cart list
                    listItem.appendChild(minusButton);
                    cartList.appendChild(listItem);
                }
            }


            function updateTotalAmount(cartData) {
                var totalAmount = 0;

                // Calculate the total amount based on the cart data
                for (var i = 0; i < cartData.length; i++) {
                    totalAmount += cartData[i].productPrice * cartData[i].quantity;
                }

                // Update the total amount in the cart-foot section
                totalAmountElement.textContent = isNaN(totalAmount) ? '₱0.00' : '₱' + totalAmount.toFixed(2);
            }

            // Void the Cart
            var voidCartBtn = document.getElementById('void-cart-btn');
            voidCartBtn.addEventListener('click', function () {
                voidCart();
            });

            function voidCart() {
                if (confirm('Are you sure you want to void the cart?')) {
                    // Call the server-side script to clear the cart data
                    clearCartDataOnServer();

                    // Clear the cart UI
                    var cartList = document.getElementById('cart-list');
                    cartList.innerHTML = '';

                    // Reset discounted amount and total amount
                    var discountedAmountElement = document.getElementById('discounted-amount');
                    var totalAmountElement = document.getElementById('total-amount');
                    discountedAmountElement.textContent = '0.00';
                    totalAmountElement.textContent = '₱0.00';

                    // Remove applied discount and discount code
                    appliedDiscounts = [];
                    discountCodes = [];

                    // Clear amount received and change amount
                    var amountReceivedInput = document.getElementById('amount-received');
                    var changeAmountElement = document.getElementById('change-amount');
                    amountReceivedInput.value = '';
                    changeAmountElement.textContent = '₱0.00';
                }
            }

            function clearCartDataOnServer() {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../../config/clear_cart.php', true);  // Update with your actual server endpoint
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = xhr.responseText;
                        if (response === 'success') {
                            console.log('Cart data cleared on the server.');
                        } else {
                            console.error('Failed to clear cart data on the server.');
                        }
                    }
                };
                xhr.send();
            }
        });
    
        document.addEventListener('DOMContentLoaded', function () {
            var applyDiscountBtn = document.getElementById('apply-discount-btn');
            var discountedAmountElement = document.getElementById('discounted-amount')

            applyDiscountBtn.addEventListener('click', function () {
                var discountCodeInput = document.getElementById('discount-code');
                var discountCode = discountCodeInput.value.trim();
                var totalAmountElement = document.getElementById('total-amount');

                // AJAX call to fetch discount from the server
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '../../config/get_discount_cart.php?code=' + encodeURIComponent(discountCode), true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        console.log(xhr.responseText);
                        var discountData = JSON.parse(xhr.responseText);

                        if (discountData.success) {
                            // Apply the discount to the total amount
                            applyDiscount(discountData.discount, totalAmountElement, discountedAmountElement);

                            // Add a class to change the button color
                            applyDiscountBtn.classList.add('discount-applied');
                        } else {
                            alert('Invalid discount code. Please try again.');
                        }
                    }
                };
                xhr.send();
            });

            var removeDiscountBtn = document.getElementById('remove-discount-btn');

            removeDiscountBtn.addEventListener('click', function () {
                removeDiscount();

                // Remove the class to revert the button color
                applyDiscountBtn.classList.remove('discount-applied');
            });
        });


        var appliedDiscounts = [];
        var discountCodes = [];

        function applyDiscount(discount, totalAmountElement, discountedAmountElement) {
            console.log('Discount:', discount);

            // Check if the discount is a valid number
            if (!isNaN(discount) && discount >= 0 && discount <= 100) {
                // Check if the discount code is already applied
                if (appliedDiscounts.length === 0) {
                    var totalAmountText = totalAmountElement.textContent;
                    console.log('Total Amount (Text):', totalAmountText);

                    // Extract the numeric part of the string and convert it to a float
                    var totalAmount = parseFloat(totalAmountText.replace(/[^\d.]/g, ''));

                    console.log('Total Amount (Parsed):', totalAmount);

                    // Apply the discount to the total amount
                    var discountedAmount = totalAmount - (totalAmount * discount / 100);
                    discountedAmount = Math.max(discountedAmount, 0);

                    // Display the discounted amount
                    console.log('Discounted Amount:', discountedAmount);

                    // Display the discount percentage in the UI
                    discountedAmountElement.textContent = discount + '%';

                    // Display the updated total amount in the UI
                    totalAmountElement.textContent = '₱' + discountedAmount.toFixed(2);

                    // Add the discount and discount code to the applied lists
                    appliedDiscounts.push(discount);
                    discountCodes.push(discount);

                } else {
                    // If the discount is already applied, prevent applying it again
                    console.error('Discount is already applied.');
                    alert('Discount is already applied. Remove the existing discount to apply a new one.');
                }
            } else {
                // If the discount is not a valid number, display an error or handle it as needed
                console.error('Invalid discount value.');
                alert('Invalid discount value. Please enter a valid discount percentage.');
            }
        }

        function calculateOriginalTotalAmount(callback) {
            // AJAX call to fetch the current cart information
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '../../config/get_cart.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var cartData = JSON.parse(xhr.responseText);
                    var originalTotalAmount = 0;

                    // Calculate the total amount based on the cart data
                    for (var i = 0; i < cartData.length; i++) {
                        originalTotalAmount += cartData[i].productPrice * cartData[i].quantity;
                    }

                    callback(originalTotalAmount);
                }
            };
            xhr.send();
        }

        // Modify the removeDiscount function to handle asynchronous calculation
        function removeDiscount() {
            // Reset the applied discounts and discount codes
            appliedDiscounts = [];
            discountCodes = [];

            // Fetch the original total amount without discount asynchronously
            calculateOriginalTotalAmount(function(originalTotalAmount) {
                // Reset UI elements
                var discountedAmountElement = document.getElementById('discounted-amount');
                var totalAmountElement = document.getElementById('total-amount');
                var discountCodeInput = document.getElementById('discount-code');

                // Display the original total amount
                totalAmountElement.textContent = '₱' + originalTotalAmount.toFixed(2);

                // Clear the discounted amount display
                discountedAmountElement.textContent = '0.00';

                discountCodeInput.value = '';
            });
        }

        function clearUI() {
            // Clear the cart list
            var cartList = document.getElementById('cart-list');
            cartList.innerHTML = '';

            // Reset discounted amount and total amount
            var discountedAmountElement = document.getElementById('discounted-amount');
            var totalAmountElement = document.getElementById('total-amount');
            var discountCodeInput = document.getElementById('discount-code');
            discountCodeInput.value = '';
            discountedAmountElement.textContent = '0.00';
            totalAmountElement.textContent = '₱0.00';

            // Remove applied discount and discount code
            appliedDiscounts = [];
            discountCodes = [];

            // Clear amount received and change amount
            var amountReceivedInput = document.getElementById('amount-received');
            var changeAmountElement = document.getElementById('change-amount');
            amountReceivedInput.value = '';
            changeAmountElement.textContent = '₱0.00';

            // Clear the cart data on the server (this is a placeholder, update it based on your server communication)
            clearCartDataOnServer();
        }

        function clearCartDataOnServer() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../../config/clear_cart.php', true);  // Update with your actual server endpoint
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    if (response === 'success') {
                        console.log('Cart data cleared on the server.');
                    } else {
                        console.error('Failed to clear cart data on the server.');
                    }
                }
            };
            xhr.send();
        }

        document.addEventListener('DOMContentLoaded', function () {

            var amountReceivedInput = document.getElementById('amount-received');
            var changeAmountElement = document.getElementById('change-amount');
            var totalAmountElement = document.getElementById('total-amount');
            var discountedAmountElement = document.getElementById('discounted-amount');


            amountReceivedInput.addEventListener('input', function () {
                calculateChange(amountReceivedInput.value, totalAmountElement, discountedAmountElement, changeAmountElement);
            });


            function calculateChange(amountReceived, totalAmountElement, discountedAmountElement, changeAmountElement) {
                var totalAmountText = totalAmountElement.textContent;
                var discountedAmountText = discountedAmountElement.textContent;

                // Extract the numeric part of the string and convert it to a float
                var totalAmount = parseFloat(totalAmountText.replace(/[^\d.]/g, '')) || 0;

                // Convert the amountReceived to a float
                var amountReceivedValue = parseFloat(amountReceived.replace(/[^\d.]/g, '')) || 0;

                // Calculate the change
                var changeAmount = amountReceivedValue - totalAmount;

                // Display the change amount
                changeAmountElement.textContent = '₱' + changeAmount.toFixed(2);
            }
        });      

        function recordTransaction() {

            var amountReceivedInput = document.getElementById('amount-received');
            var changeAmountElement = document.getElementById('change-amount');
            var totalAmountElement = document.getElementById('total-amount');

            var totalAmount = parseFloat(totalAmountElement.textContent.replace(/[^\d.]/g, '')) || 0;
            var amountReceived = parseFloat(amountReceivedInput.value) || 0;
            var changeAmount = amountReceived - totalAmount;
            console.log(changeAmount);
            // Check if the change amount is negative
            if (changeAmount < 0) {
                alert('Change amount cannot be negative. Please check the amount received.');
                return; // Stop recording transaction
            }

            // Populate the preview modals
            var previewList = document.getElementById('preview-list');
            var cartList = document.getElementById('cart-list');
            previewList.innerHTML = '';
            cartList.childNodes.forEach(function (item) {
                var listItem = document.createElement('li');
                var itemText = item.textContent.replace(/-$/, ''); // Remove the trailing minus sign
                var parts = itemText.split(' - ₱');
                var productName = parts[0].trim();
                var price = parts[1].trim();
                // Add item name and price to the list item
                listItem.innerHTML = '<div class="item">' +
                                    '<div class="product-name">' + productName + '</div>' +
                                    '<div class="product-price">₱' + price + '</div>' +
                                    '</div>';
                previewList.appendChild(listItem);
            });

            // Add a line separator
            var separatorItem = document.createElement('li');
            separatorItem.className = 'divider';
            previewList.appendChild(separatorItem);

            // Add discount code and value to the preview modal
            var discountCode = document.getElementById('discount-code').value;
            var discountValue = document.getElementById('discounted-amount').textContent;
            var discountItem = document.createElement('li');
            if (discountValue === "0.00") {
                discountItem.innerHTML = '<div class="item">' +
                                        '<div>Discount:</div>' +
                                        '<div class="bold">----</div>' +
                                        '</div>';
            } else {
                discountItem.innerHTML = '<div class="item">' +
                                        '<div>Discount: ' + discountCode + '</div>' +
                                        '<div class="bold">' + discountValue + '</div>' +
                                        '</div>';
            }
            previewList.appendChild(discountItem);


            // Add total amount to the preview modal
            var totalAmountItem = document.createElement('li');
            var totalAmountElement = document.getElementById('total-amount');
            totalAmountItem.innerHTML = '<div class="item total">' +
                                        '<div>Total Amount:</div>' +
                                        '<div class="bold">' + totalAmountElement.textContent + '</div>' +
                                        '</div>';
            previewList.appendChild(totalAmountItem);

            // Add a line separator
            var separatorItem = document.createElement('li');
            separatorItem.className = 'divider';
            previewList.appendChild(separatorItem);

            // Add amount received to the preview modal
            var amountReceivedItem = document.createElement('li');
            var amountReceivedInput = document.getElementById('amount-received');
            amountReceivedItem.innerHTML = '<div class="item total">' +
                                            '<div>Amount Received:</div>' +
                                            '<div class="bold">₱' + amountReceived + '</div>' +
                                            '</div>';
            previewList.appendChild(amountReceivedItem);

            // Add change amount to the preview modal
            var changeAmountItem = document.createElement('li');
            var changeAmountElement = document.getElementById('change-amount');
            changeAmountItem.innerHTML = '<div class="item ">' +
                                        '<div>Change:</div>' +
                                        '<div class="bold">' + changeAmountElement.textContent + '</div>' +
                                        '</div>';
            previewList.appendChild(changeAmountItem);

            // Open the confirmation modal
            openModal();
        }

        function openModal() {
            var modal = document.getElementById('confirmation-modal');
            modal.style.display = 'block';
        }

        function closeModal() {
            var modal = document.getElementById('confirmation-modal');
            modal.style.display = 'none';
        }

        function printReceipt() {
            var printContents = document.querySelector('.receipt').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }


        function confirmTransaction() {

            function getCartData() {
                var cartData = [];
                var cartList = document.getElementById('cart-list');

                cartList.childNodes.forEach(function (item) {
                    var parts = item.textContent.split(' - ₱');
                    var productName = parts[0].trim();
                    var quantityAndPrice = parts[1].trim().split(' x ');
                    var quantity = parseInt(quantityAndPrice[0]);
                    var productPrice = parseFloat(quantityAndPrice[1]);
                    var productPrice = parseFloat(parts[1]);

                    cartData.push({
                        productName: productName,
                        productPrice: productPrice
                    });
                });

                return cartData;
            }

            // Proceed to record the transaction
            var cartList = document.getElementById('cart-list');
            var totalAmountElement = document.getElementById('total-amount');
            var discountedAmountElement = document.getElementById('discounted-amount');
            var paymentType = document.getElementById('payment-type').value;
            var discount = parseFloat(document.getElementById('discount-code').value) || 0;
            var amountReceived = parseFloat(document.getElementById('amount-received').value) || 0;

            var orderDetails = JSON.stringify(getCartData()); // Replace getCartData with your actual function to get cart data
            var discountCode = document.getElementById('discount-code').value;
            var discountAmount = document.getElementById('discounted-amount').textContent;
            var finalTotalAmount = document.getElementById('total-amount').textContent;
            var changeAmountElement = document.getElementById('change-amount');
            var changeAmount = changeAmountElement.textContent.replace(/[^\d.]/g, ''); // Remove non-numeric characters
            changeAmount = parseFloat(changeAmount) || 0;
            var username = '<?php echo $user; ?>';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../../config/record_transaction_cart.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200 && xhr.responseText === 'success') {
                    alert('Transaction recorded successfully!');
                    // Optionally, clear the cart or perform any other necessary actions
                } else {
                    alert('Failed to record transaction. Please try again.');
                }
            };

            var data = 'username=' + encodeURIComponent(username) +
                    '&orderDetails=' + encodeURIComponent(orderDetails) +
                    '&discountCode=' + encodeURIComponent(discountCode) +
                    '&discountAmount=' + encodeURIComponent(discountAmount) +
                    '&finalTotalAmount=' + encodeURIComponent(finalTotalAmount) +
                    '&paymentType=' + encodeURIComponent(paymentType) +
                    '&amountReceived=' + encodeURIComponent(amountReceived) +
                    '&changeAmount=' + encodeURIComponent(changeAmount);
            xhr.send(data);
            
            clearUI();
            removeDiscount();
            
            // Close the modal (optional)
            closeModal();
            printReceipt();
        }

        
    </script>
</head>

<body>       
    <div class="wrapper">
        <div class="sidebar">
            <button id="closeButton">&times;</button>
            <h2>SEAT AND SIP CAFE</h2>
            <hr class="line">

            <nav>
                <ul>
                    <li><a href="../dashboard.php" ><i class="fas fa-solid fa-calendar"></i>Dashboard</a></li>
                    <li><a href="../makeorder.php" class="current"><i class="fas fa-solid fa-clipboard"></i>Make Order</a></li>
                    <li><a href="../products.php"><i class="fas fa-solid fa-box"></i>Products</a></li>
                    <li><a href="../transaction_history.php"><i class="fas fa-solid fa-receipt"></i>Transaction History</a></li>
                    <li><a href="../sales.php"><i class="fas fa-solid fa-square-poll-vertical"></i>Sales Report</a></li>
                    <li><a href="../config/logout.php"><i class="fas fa-solid fa-door-open"></i>Log-Out</a></li>
                </ul>
            </nav>
            
        </div>
        
        <div class="main_content">
            <div class="header">
                <button id="hamburger"><i class="fa-solid fa-bars"></i></button>
                <div>Hello, <?php Print "$user"?>! <span id='date-time'></span></div>
            </div> 

            <div class="info">
                <div class="product-list">
                    <div class="head">
                        <a href="../makeorder.php" class="back"> <i class="fa-solid fa-arrow-left"></i>  <span class="heading">Add Ons</span>  </a>   
                    </div>

                    <div class="body">
                        <table>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                            </tr>
                        
                            <?php
                                $servername = "localhost";
                                $username_db = "root";
                                $password_db = "";
                                $db_name = "pos";
                                // Create connection
                                $conn = mysqli_connect($servername, $username_db, $password_db, $db_name);
                                // Check connection
                                if (!$conn) {
                                    die("Connection failed: " . mysqli_connect_error());
                                }
                                $query = mysqli_query($conn, "SELECT product_name, price FROM products WHERE category_id ='4' ORDER BY product_name ASC "); //SQL Query
                                while($row = mysqli_fetch_array($query)){
                                    //Display all the rows from query
                                    Print "<tr>";
                                        Print "<td> " . $row['product_name'] . " </td>";
                                        Print "<td> " . $row["price"] . " </td>";
                                        Print '<td class="action-row"><button class="button button-plus"><i class="fa-solid fa-plus"></i></button></td>';
                                        Print '<td class="action-row"><button class="button button-minus"><i class="fa-solid fa-minus"></i></button></td>';
                                    Print "</tr>";
                                }
                            ?>
                        </table>
                    </div>                     
                </div>

                <div class="cart-section">
                    
                    <div class="cart-head">
                        <div class="cart-void">
                            <span >Cart </span>
                            <button id="void-cart-btn" class="void-cart-btn" title="Void Cart"><i class="fa-solid fa-delete-left"></i></button>
                        </div>
                        <ul id="cart-list" class="cart-list"></ul>
                    </div>

                    <div class="cart-foot">
                        <hr class="liner">
                        <div class="discount-option">
                            <label for="discount-code">Discount:</label>
                            <div class="discount-input">
                                <input type="text" id="discount-code" class="discount-code-input" placeholder="Enter Code">
                                <div class="button-container">
                                    <button id="apply-discount-btn" class="apply-discount-btn" title="Apply Discount"><i class="fa-solid fa-check"></i></button>
                                    <button id="remove-discount-btn" class="remove-discount-btn" title="Remove Discount"> <i class="fa-solid fa-xmark"></i> </button>
                                </div>
                            </div>
                        </div>

                        <p class="discount-amount">Discounted Amount: <span id="discounted-amount" class="impotant-cart-details">0.00</span></p>

                        <p class="total-amount" >Total: <span id="total-amount" class="impotant-cart-details">0.00</span></p>

                        <div class="payment-option">
                            <label for="payment-type">Payment Type:</label>
                            <select id="payment-type"  class="payment-type" name="payment-type">
                                <option value="cash">Cash</option>
                                <option value="gcash">Gcash</option>
                            </select>
                        </div>

                        <div class="amount-received">
                            <label for="amount-received">Amount Received:</label>
                            <input type="number" id="amount-received" class="amount-recieved-input" placeholder="Enter Amount">
                        </div>

                        <p>Change: <span id="change-amount" class="impotant-cart-details">0.00</span></p>

                        <button class="payment-btn" onclick="recordTransaction()">Record Transaction</button>

                        <div id="confirmation-modal" class="modal">
                            <div class="modal-content">
                                <div class="modal-title">
                                    <h2>Transaction Receipt</h2>
                                    <span class="close" onclick="closeModal()">&times;</span>
                                </div>

                                <div class="receipt">
                                    <div class="logo">SEAT & SIP CAFE</div>

                                    <div class="receipt-head">Windfarm Tanay, Pilila, Rizal</div>

                                    <div class="title receipt-head">----------RECEIPT----------</div>

                                    <div class="date receipt-head"><span id='date-receipt'></span></div>

                                    <div class="item">
                                        <div>Cashier:</div>
                                        <div class="amount"><?php echo $user; ?></div>
                                    </div>
                                    <div class="divider"></div>


                                    <div class="body">
                                        <ul id="preview-list"></ul>
                                    </div>
                                </div>
                                <button class="confirm-btn" onclick="confirmTransaction()">Confirm Transaction</button>
                            </div>
                        </div>
                    </div>       
                </div>
            </div> 
        </div>
        
    </div>
    
    <!-- Date Script -->
    <script>
        var dt = new Date();
        var options = {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            timeZone: 'Asia/Manila', 
        hour12: true 
        
        };

        var formattedDate = dt.toLocaleString('en-PH', options);
        document.getElementById('date-time').innerHTML = formattedDate;
        document.getElementById('date-receipt').innerHTML = formattedDate;
    </script>


    <!-- Hamburger Menu Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamburger = document.getElementById("hamburger");
            const closeButton = document.getElementById("closeButton");
            const sidebar = document.querySelector(".sidebar");
           
            hamburger.addEventListener("click", function() {
                sidebar.classList.toggle("active");
            });

            // Close sidebar when close button is clicked
            closeButton.addEventListener("click", function() {
                sidebar.classList.remove("active");
            });

        });
    </script>
</body>
</html>