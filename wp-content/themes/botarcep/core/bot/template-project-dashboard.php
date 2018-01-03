<?php


if (isset($_POST) && !empty($_POST)) {

        $data = (object)$_POST;
        $WP_array = array(
            'user_login' => $data->user_login,
            'user_email' => $data->user_email,
            'user_pass' => $data->user_pass,
            'user_url' => "",
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'nickname' => "",
            'description' => "",
            'role' => "candidat"
        );

        $userMeta = array(
            'street' => $data->street,
            'city' => $data->city,
            'bp' => $data->bp,
            'codePays' => $data->codePays,
        );

        if($data->user_pass==$data->user_pass1){
            $user_id = wp_insert_user($WP_array);



            if ($user_id && !is_wp_error($user_id)) {
                add_user_meta($user_id, 'userData', json_encode($userMeta));
                wp_authenticate(   $data->user_email, $data->user_pass );
                $code = sha1($user_id . time());

                $succes = true;
            }
        }else{
            $passwordError = false;
        }


}


?>

<?php /* Template Name: Project Dashboard */
get_header(); ?><main id="main" class="site-main">
    <div class="page-title background-page">
        <div class="container">
            <h1>Profile</h1>
            <div class="breadcrumbs">
                <ul>
                    <li><a href="index.html">Home</a><span>/</span></li>
                    <li>Profile</li>
                </ul>
            </div><!-- .breadcrumbs -->
        </div>
    </div><!-- .page-title -->
    <div class="account-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <nav class="account-bar">
                        <ul>
                            <li><a href="dashboard.html">Dashboard</a></li>
                            <li class="active"><a href="profile.html">Profile</a></li>
                            <li><a href="account_my_campaigns.html">My Campaigns</a></li>
                            <li><a href="account_backed_campaigns.html">Backed Campaigns</a></li>
                            <li><a href="account_pledges_received.html">Pledges Received</a></li>
                            <li><a href="account_rewards.html">Rewards</a></li>
                            <li><a href="account_payments.html">Payments</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-9">
                    <div class="account-content profile">
                        <h3 class="account-title">My Profiles</h3>
                        <div class="account-main">
                            <div class="author clearfix">
                                <a class="author-avatar" href="#"><img src="images/dashboard-avatar.png" alt=""></a>
                                <div class="author-content">
                                    <div class="author-title"><h3><a href="#">Melvin Martinez</a></h3></div>
                                    <div class="author-info">
                                        <p>melvin@themegoals.com</p>
                                        <p>Ideabox Member since July 2017</p>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-box">
                                <h3>Profile Infomations</h3>
                                <ul>
                                    <li>
                                        <strong>About Me:</strong>
                                        <div class="profile-text"><p>I feel the presence of the Almighty, who formed us in his own image, and the breath of that universal love which bears and sustains us, as it floats around us in an eternity of bliss; and then, my friend, when darkness overspreads my eyes, and heaven and earth seem to dwell in my soul and absorb its power, like the form of a beloved mistress, then I often think with longing</p></div>
                                    </li>
                                    <li>
                                        <strong>Bio:</strong>
                                        <div class="profile-text"><p>Oh, would I could describe these conceptions, could impress upon paper all that is living so full and warm within me, that it might be the mirror of my soul, as my soul is the mirror of the infinite God!</p></div>
                                    </li>
                                    <li>
                                        <strong>First Name</strong>
                                        <div class="profile-text"><p>Martinez</p></div>
                                    </li>
                                    <li>
                                        <strong>Last Name:</strong>
                                        <div class="profile-text"><p>Melvin</p></div>
                                    </li>
                                    <li>
                                        <strong>Mobile:</strong>
                                        <div class="profile-text"><p>(+1) 123 456 789</p></div>
                                    </li>
                                    <li>
                                        <strong>Fax:</strong>
                                        <div class="profile-text"><p>(+1) 987 654 321</p></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="profile-box">
                                <h3>Shipping Address</h3>
                                <ul>
                                    <li>
                                        <strong>First Name</strong>
                                        <div class="profile-text"><p>Martinez</p></div>
                                    </li>
                                    <li>
                                        <strong>Last Name:</strong>
                                        <div class="profile-text"><p>Melvin</p></div>
                                    </li>
                                    <li>
                                        <strong>Company:</strong>
                                        <div class="profile-text"><p>ThemeGoals Inc.</p></div>
                                    </li>
                                    <li>
                                        <strong>Address 1:</strong>
                                        <div class="profile-text"><p>PO Box 16122 Collins Street West</p></div>
                                    </li>
                                    <li>
                                        <strong>Address 2:</strong>
                                        <div class="profile-text"><p></p></div>
                                    </li>
                                    <li>
                                        <strong>City:</strong>
                                        <div class="profile-text"><p>Melbourne</p></div>
                                    </li>
                                    <li>
                                        <strong>Post Code:</strong>
                                        <div class="profile-text"><p>3000</p></div>
                                    </li>
                                    <li>
                                        <strong>State:</strong>
                                        <div class="profile-text"><p>Victoria</p></div>
                                    </li>
                                    <li>
                                        <strong>Country:</strong>
                                        <div class="profile-text"><p>Australia</p></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="profile-box">
                                <h3>Billing Address</h3>
                                <ul>
                                    <li>
                                        <strong>First Name</strong>
                                        <div class="profile-text"><p>Martinez</p></div>
                                    </li>
                                    <li>
                                        <strong>Last Name:</strong>
                                        <div class="profile-text"><p>Melvin</p></div>
                                    </li>
                                    <li>
                                        <strong>Company:</strong>
                                        <div class="profile-text"><p>ThemeGoals Inc.</p></div>
                                    </li>
                                    <li>
                                        <strong>Address 1:</strong>
                                        <div class="profile-text"><p>PO Box 16122 Collins Street West</p></div>
                                    </li>
                                    <li>
                                        <strong>Address 2:</strong>
                                        <div class="profile-text"><p></p></div>
                                    </li>
                                    <li>
                                        <strong>City:</strong>
                                        <div class="profile-text"><p>Melbourne</p></div>
                                    </li>
                                    <li>
                                        <strong>Post Code:</strong>
                                        <div class="profile-text"><p>3000</p></div>
                                    </li>
                                    <li>
                                        <strong>State:</strong>
                                        <div class="profile-text"><p>Victoria</p></div>
                                    </li>
                                    <li>
                                        <strong>Country:</strong>
                                        <div class="profile-text"><p>Australia</p></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="profile-box">
                                <h3>Payment Infomation</h3>
                                <ul>
                                    <li>
                                        <strong>First Name</strong>
                                        <div class="profile-text"><p>Martinez</p></div>
                                    </li>
                                    <li>
                                        <strong>Last Name:</strong>
                                        <div class="profile-text"><p>Melvin</p></div>
                                    </li>
                                    <li>
                                        <strong>Payment Email:</strong>
                                        <div class="profile-text"><p>malvin@themegoals.com</p></div>
                                    </li>
                                </ul>
                            </div>
                            <a href="#" class="btn-primary">Edit Profiles</a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .container -->
    </div><!-- .account-content -->
</main>
<?php get_footer(); ?>
