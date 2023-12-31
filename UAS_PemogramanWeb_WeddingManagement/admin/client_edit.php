<?php include 'include/init.php'; ?>
<?php
    if (!isset($_SESSION['id'])) { redirect_to("../"); }

    $booking_id = $_GET['booking'];
    $user_id = $_GET['user_id'];

    $accounts =  Accounts::find_by_user_id($user_id);
    $account_detail =  Account_Details::find_by_user_id($user_id);
    $booking_detail =  Booking::find_by_booking_id($booking_id);
    $categories = Category::find_all();
    
    if (isset($_POST['submit'])) {

        if ($booking_detail) {

            $firstname = clean($_POST['firstname']);
            $lastname = clean($_POST['lastname']);
            $email = clean($_POST['email']);
            $wedding_date = clean($_POST['wedding_date']);
            $bride = clean($_POST['bride']);
            $groom = clean($_POST['groom']);
            $phone = clean($_POST['phone']);
            $city = clean($_POST['city']);
            $wedding_type = clean($_POST['wedding_type']);
            $organizer_id = clean($_POST['organizer_id']);

            $booking_detail->bride = $bride;
            $booking_detail->groom = $groom;
            $booking_detail->wedding_type = $wedding_type;
            $accounts->user_email = $booking_detail->user_email = $email;
            $booking_detail->wedding_date = $wedding_date;
            $booking_detail->organizer_id = $organizer_id;
            
            $account_detail->firstname = $firstname;
            $account_detail->lastname = $lastname;
            $account_detail->phone = $phone;
            $account_detail->city = $city;
            $account_detail->save_account();
             
            if ($booking_detail->save_booking()) {
                $accounts->save_account();
            }

            redirect_to("client.php");
            $session->message("
            <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
              <strong><i class='mdi mdi-approval'></i></strong> {$account_detail->firstname} {$account_detail->lastname} berhasil diubah.
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
            </div>");
        }

    }


?>
<?php $users_profile = Users::find_by_id($_SESSION['id']); ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Edit Client Information - Administrator</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/dashboard.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.materialdesignicons.com/2.1.19/css/materialdesignicons.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
        <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
        <style>
            body {
                margin-bottom: 2%;
            }
            .box-shadow {
                box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.3);
                font-size: 12px;
            }
            .form-control {
                font-size: 12px;
            }
            .datepicker {
                font-size: 12px;
            }
        </style>
    </head>

<body>

<?php include_once 'include/sidebar.php'; ?>

    <div class="container">

        <div class="row">

            <div class="col-lg-8 offset-2 pl-3 pb-3 box-shadow mt-4">

                <form method="post" action="">

                    <h4 class="h4 mt-4 pb-2" style="border-bottom: 1px solid #dee2e6!important;">Edit Informasi Klien
                        <a href="client.php" class="btn btn-sm btn-danger float-right" style="font-size: 12px;"><i class="mdi mdi-close-circle mr-2"></i> Kembali</a>
                        <button type="submit" name="submit" class="btn btn-sm btn-success float-right mr-2" style="font-size: 12px;"><i class="mdi mdi-account-plus mr-2"></i> Edit Pelanggan</button>
                    </h4>
                    <div class="form-row">

                        <div class="form-group col-md-6">
                            <label for="inputFirstname">Nama Depan</label>
                            <input type="text" name="firstname" class="form-control" id="inputFirstname" value="<?= $account_detail->firstname; ?>" placeholder="Nama Depan">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="inputLastname">Nama Belakang</label>
                            <input type="text" name="lastname" class="form-control" id="inputLastname" value="<?= $account_detail->lastname; ?>" placeholder="Nama Belakang">
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="inputEmail">Email</label>
                        <input type="text" name="email" class="form-control" id="inputEmail" value="<?= $booking_detail->user_email; ?>" placeholder="Alamat Email">
                    </div>

                    <div class="form-row form-group">

                        <div class="col-md-6">
                            <label for="wedding_date">Tanggal Pernikahan</label>
                        </div>

                        <div class="col-md-6">
                            <label for="wedding_type">Kelas Pernikahan</label>
                        </div>

                        <div class="input-group col-md-6">
                            <input type="text" class="form-control"
                                   name="wedding_date" value="<?= $booking_detail->wedding_date; ?>" data-provide="datepicker" id="wedding_date"
                                   placeholder="Tanggal Pernikahan">
                            <div class="input-group-append">
                                    <span class="input-group-text"
                                          style="background: white;">
                                        <i style="color:#19b5bc;" class="mdi mdi-calendar-check"
                                            id="review" aria-hidden="true"></i>
                                    </span>
                            </div>
                        </div>

                        <!-- <div class="input-group col-md-6">
                            <select class="form-control" id="wedding_type" name="wedding_type">
                                <?php foreach($categories as $category) : ?>
                                    <option value="<?= $category->wedding_type;?>"><?= $category->wedding_type; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> -->


                        <div class="input-group col-md-6">
                            <select class="form-control" id="wedding_type" name="wedding_type">

                                <?php foreach($categories as $category) : ?>
                                    <?php if ($category->id == $booking_detail->wedding_type): ?>
                                        <option value="<?= $category->id;?>" selected><?= $category->wedding_type; ?> - <?= number_format($category->price); ?> </option>
                                        <?php else: ?>
                                        <option value="<?= $category->id;?>"><?= $category->wedding_type; ?> - <?= number_format($category->price); ?> </option>
                                            
                                    <?php endif ?>

                                <?php endforeach; ?>

                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="brideName">Nama Pengantin Wanita</label>
                        <input type="text" name="bride" class="form-control" value="<?= $booking_detail->bride; ?>" id="brideName" placeholder="Nama Pengantin Wanita">
                    </div>
                    <div class="form-group">
                        <label for="GroomsName">Nama Pengantin Pria</label>
                        <input type="text" name="groom" class="form-control" value="<?= $booking_detail->groom; ?>" id="GroomsName" placeholder="Nama Pengantin Pria">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputPhone">Telepon</label>
                            <input type="text" class="form-control" value="<?= $account_detail->phone; ?>" id="inputPhone" name="phone" placeholder="Nomor Telepon">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputcity">Kota</label>
                            <input type="text" class="form-control" value="<?= $account_detail->city; ?>" id="inputcity" name="city" placeholder="Kota">
                        </div>
                    </div>
                </form><!-- end of input form -->
            </div>
        </div>
    </div>




</main>
</div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery-3.2.1.slim.min.js"></script>
<script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="js/popper.min.js"></script>
<script src="../js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="../js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('#wedding_date').datepicker();
    });
</script>
</body>
</html>
