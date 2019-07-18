<?php

###########################
### TODO: MOVE TO CLASS ###
###########################
#clean for inputs
function clean($text) {
    $text = str_replace("\n", ' ', $text);
    $text = str_replace(",", '\,', $text);
    $text = str_replace("'", "\'", $text);
    $text = strip_tags($text); // entfernt alle HTML-Tags aus dem $text
    $text = htmlspecialchars($text); 
    $text = htmlentities($text);
    return $text;
}
###########################
### TODO: MOVE TO CLASS ###
###########################
#check for email format
function is_mail($text) {
    $part = explode('@', $text);
    if(isset($part[1])){
        $secondPart = explode('.', $part[1]);
        if (count($part) != 2) {
            return false;
        }
        if (strlen($part[0]) < 1 || strlen($part[1]) < 1) {
            return false;
        }
        if (count($secondPart) < 2) {
            return false;
        }
        if (strlen($secondPart[0]) < 1 || strlen(end($secondPart)) < 2) {
            return false;
        }
    }
    else{
        return false;
    }
    if (strlen($text) > 254) {
        return false;
    }
    return true;
}



##############################################################
### AS I USE CLASSES, NO NEED ANYMORE, MARKED FOR DELETION ###
##############################################################
#authorise user
/*function authUser($nickname, $password, $link) {
    if (empty($nickname) || empty($password)) {
        return false;
    } 
    $sql = "SELECT nickname, password FROM user WHERE nickname = '$nickname'";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        echo 'Error: ' . mysqli_error($link) . '<br>';
        echo 'SQL: ' . $sql;
        die('Can not send query to database');
    } else {
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            $record = mysqli_fetch_array($result);
            $record['password'];
            $check = password_verify($password, $record['password']);
            if($check){
                return true;
            }
            else{
                return false;
            }
        } else {
            return false;
        }
    }
}*/


##############################################################
### AS I USE CLASSES, NO NEED ANYMORE, MARKED FOR DELETION ###
##############################################################
#get id of the store, where works logged in user
/*function getStoreId($nickname, $link){
    $sql = "SELECT store.id FROM store INNER JOIN staff ON store.id = staff.store_id INNER JOIN user ON user.id = staff.user_id WHERE user.nickname = '" . $nickname . "'";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        echo 'Error: ' . mysqli_error($link);
        echo '<br> SQL: ' . $sql;
        return false;
    } else {
        $datensatz = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $store_id = $datensatz['id'];
    }
    if ($store_id) {
        return $store_id;
    } else {
        return false;
    }    
}*/

##############################################################
### AS I USE CLASSES, NO NEED ANYMORE, MARKED FOR DELETION ###
##############################################################
#get id of logged in user 
/*function getUserId($nickname, $link) {
    $sql = "SELECT id FROM user WHERE nickname = '$nickname'";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        echo 'Error: ' . mysqli_error($link);
        echo '<br> SQL: ' . $sql;
        return false;
    } else {
        $datensatz = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $user_id = $datensatz['id'];
    }
    if ($user_id) {
        return $user_id;
    } else {
        return false;
    }
}*/

?>