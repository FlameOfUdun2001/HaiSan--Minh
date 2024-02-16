<?php
session_start();
include 'common/header.php';
include 'config.php';
include 'common/common/headerSingleProducts.php';
?>
<div class="main-inner">
    <div class="container">
        <div class="row">
            <div class="span12">
                <div class="widget">
                    <div class="widget-header">
                        <i class="icon-edit"></i>
                        <h3>Thêm xuất sứ</h3>
                    </div>

                    <div class="widget-content">
                        <form method="post" enctype="multipart/form-data">
                            <fieldset>
                                <table>
                                    <tr>
                                        <td><label class="control-label" for="name">Nơi xuất sứ<span class="red"> *</span></label></td>
                                        <td>
                                            <div class="controls"><input type="text" class="span6" id="name" name="name"><br>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="active" value="1" checked> Còn Hàng
                                                </label>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="controls">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="active" value="1" checked> Còn Hàng
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="control-label" for="link">Nổi bật<span class="red"> *</span></label></td>
                                        <td>
                                            <div class="controls"><input type="text" class="span6" id="link" name="link"><br>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label class="control-label" for="sort_order">Thứ tự sắp xếp<span class="red"> *</span></label></td>
                                        <td>
                                            <div class="controls"><input type="number" class="form-control" id="sort_order" name="sort_order"><br>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-primary">Thêm xuất sứ</button>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'common/footer.php'; ?>
