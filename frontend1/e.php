<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "sell_fishs");

// Check if the connection was successful
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
include 'common/header.php';
// Retrieve the calculated calories from the query parameter
$calories = isset($_GET['calories']) ? floatval($_GET['calories']) : 0;

// Constants for calorie percentages for different weight loss scenarios
define('MAINTAIN_WEIGHT_PERCENTAGE', 100);
define('MILD_WEIGHT_LOSS_PERCENTAGE', 89);
define('WEIGHT_LOSS_PERCENTAGE', 78);
define('EXTREME_WEIGHT_LOSS_PERCENTAGE', 56);

// Calculate calories for different weight loss scenarios
$maintainWeightCalories = $calories * (MAINTAIN_WEIGHT_PERCENTAGE / 100);
$mildWeightLossCalories = $calories * (MILD_WEIGHT_LOSS_PERCENTAGE / 100);
$weightLossCalories = $calories * (WEIGHT_LOSS_PERCENTAGE / 100);
$extremeWeightLossCalories = $calories * (EXTREME_WEIGHT_LOSS_PERCENTAGE / 100);

// Your existing HTML code for t.php goes here
// Use the calculated values where you want to display the results
?>
<div class="main-inner">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="widget">

                    <h3 id="order_review_heading">Bảng tính calories cần nạp(cần mất)</h3>

                    <div id="order_review" style="position: relative;">
                        <table class="shop_table">
                            <thead>
                                <tr>
                                    <th class="product-name">Duy trì cân nặng</th>
                                    <th class="product-total"><?php echo number_format($maintainWeightCalories, 2); ?> Calories/ngày</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr class="cart_item">
                                    <td class="product-name">
                                        <strong class="product-quantity">Giảm cân nhẹ <br>0.25 kg/tuần </strong>
                                    </td>
                                    <td class="product-total">
                                        <strong class="product-quantity"><?php echo number_format($mildWeightLossCalories, 2); ?> Calories/ngày</span>
                                    </td>

                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="cart-subtotal">
                                    <th>Giảm cân<br>0.5 kg/tuần</th>
                                    <td><strong><span class="amount"><?php echo number_format($weightLossCalories, 2); ?> Calories/ngày</span></strong>
                                    </td>

                                </tr>

                                <tr class="shipping">
                                    <th>GIẢM CÂN CỰC ĐỈNH<br>1 kg/tuần</th>
                                    <td>
                                        <strong><?php echo number_format($extremeWeightLossCalories, 2); ?> Calories/ngày<strong>
                                        <input type="hidden" class="shipping_method" value="free_shipping" id="shipping_method_0" data-index="0" name="shipping_method[0]">
                                    </td>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>