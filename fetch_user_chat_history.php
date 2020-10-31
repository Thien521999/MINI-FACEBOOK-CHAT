<?php
    //fetch_user_chat_history.php=>//lay tin nhan  chat user(xem lai)

    include('database_connection.php');

    session_start();

    //fetch_user_chat_history:
    //Chức năng này lấy tin nhắn trò chuyện mới nhất từ ​​cơ sở dữ liệu mysql và trả về dữ liệu ở định dạng html.
    //(Nhớ nếu status=2 thì tin nhắn đã bị xóa và status=1 không xoa)
    echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);

?>