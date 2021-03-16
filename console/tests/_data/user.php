<?php

$current_time = time() + 5; // give us a five second buffer
$future_time = time() + 60*60*24*7; // next week
$past_time = time() - 60*60*24*7; // last week, (within the token lifetime, should not be purged)
$very_past_time = time() - 60*60*24*7*52; // last year

return [
    // should not be deleted
    [
        'id' => 1,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l1',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l1',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312311',
        'verify_email_token' => null,
        'email' => 'test1@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $current_time,
        'updated_at' => $current_time,
    ],
    // should not be deleted
    [
        'id' => 2,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l2',
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l2',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312312',
        'verify_email_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312312_confirmed',
        'email' => 'test2@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $future_time,
        'updated_at' => $future_time,
    ],
    // should not be deleted
    [
        'id' => 3,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l3',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l3',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312313',
        'verify_email_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312313_confirmed',
        'email' => 'test3@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $past_time,
        'updated_at' => $past_time,
    ],
    [
        // should not be deleted
        'id' => 4,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l4',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l4',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312314',
        'verify_email_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312314_confirmed',
        'email' => 'test4@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $very_past_time,
        'updated_at' => $very_past_time,
    ],
    [
        // should be deleted
        'id' => 5,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l5',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l5',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312315',
        'verify_email_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312315',
        'email' => 'test5@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $very_past_time,
        'updated_at' => $very_past_time,
    ],
    [
        // should not be deleted
        'id' => 6,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l6',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l6',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312316',
        'verify_email_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312316',
        'email' => 'test4@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $past_time,
        'updated_at' => $past_time,
    ],
    [
        // should not be deleted
        'id' => 7,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l7',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l7',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312317',
        'verify_email_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312317',
        'email' => 'test7@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $current_time,
        'updated_at' => $current_time,
    ],
    [
        // should not be deleted
        'id' => 8,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l8',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l8',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312318',
        'verify_email_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312318_confirmed',
        'email' => 'test8@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $current_time,
        'updated_at' => $current_time,
    ],
    [
        // should not be deleted
        'id' => 9,
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_l9',
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3l9',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312319',
        'verify_email_token' => null,
        'email' => 'test9@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $very_past_time,
        'updated_at' => $very_past_time,
    ],
    [
        // should not be deleted
        'id' => 10,
        'auth_key' => 'HP187vq7Mmm3CTU80dLkGmni_FUH_l10',
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s33l10',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402313110',
        'verify_email_token' => null,
        'email' => 'test10@fasterscaleapp.com',
        'role' => 10,
        'status' => 10,
        'created_at' => $past_time,
        'updated_at' => $past_time,
    ],
];