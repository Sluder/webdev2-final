<!DOCTYPE html>
<html lang="en">
    <?php
        $page_title = 'Settings';
        $description = 'Change your personal settings';

        include('includes/header.php');

        if (isset($_POST['save-info'])) {
            $response = updateUserInformation($db, $_SESSION['current_user'], $_POST);
        }

        $user_information = getUserInformation($db, $_SESSION['current_user']);
    ?>

    <body>
        <?php
            $page = 'settings';
            include('includes/navigation.php');

            // Redirect to user if this page is not allowed to be accessed
            if (!isset($_SESSION['current_user'])) {
                header("Location: index.php");
            }
        ?>

        <main>
            <form action="settings.php" method="post">
                <h3>
                    <i class="fa fa-truck fa-sm"></i> Shipping Information
                    <button type="submit" name="save-info">Update</button>
                </h3>

                <!-- Handle messages -->
                <?php if (isset($response)) { ?>
                    <p class="message <?= $response['success'] ? 'success' : 'fail' ?>"><?= $response['message'] ?></p>
                <?php } ?>

                <table class="clear-table">
                    <tr>
                        <td>
                            <label for="name">Name</label><br>
                            <input type="text" name="name" value="<?= isset($user_information) ? $user_information['name'] : '' ?>" required>
                        </td>
                        <td>
                            <label for="email">Email</label><br>
                            <input type="email" name="email" value="<?= isset($user_information) ? $user_information['email'] : '' ?>" required>
                        </td>
                        <td>
                            <label for="phone">Phone</label><br>
                            <input type="text" name="phone" value="<?= isset($user_information) ? $user_information['phone'] : '' ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="street">Shipping Street</label>
                            <input type="text" name="street" value="<?= isset($user_information) ? $user_information['shipping_street'] : '' ?>" required>
                        </td>
                        <td>
                            <label for="city">Shipping City</label>
                            <input type="text" name="city" value="<?= isset($user_information) ? $user_information['shipping_city'] : '' ?>" required>
                        </td>
                        <td>
                            <label for="zipcode">Shipping Zipcode</label>
                            <input type="text" name="zipcode" value="<?= isset($user_information) ? $user_information['shipping_zipcode'] : '' ?>" required>
                        </td>
                    </tr>
                </table>
            </form>
        </main>

        <?php
            include('includes/footer.php');
        ?>
    </body>
</html>