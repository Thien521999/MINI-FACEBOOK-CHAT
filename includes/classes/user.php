<?php

    include('class.smtp.php');//thư viện PHP MAILER
    include('class.phpmailer.php');//thư viện PHP MAILER
// user.php
class User{
    protected $db;
    protected $user_name;
    protected $user_email;
    protected $user_re_enter_email;
    protected $user_pass;
    protected $hash_pass;
    
    //Kết nối database
    function __construct($db_connection){
        $this->db = $db_connection;
    }

    // SING UP USER (Dang ky)
    function singUpUser($username, $email, $re_enter_email, $password){
        try{
            $this->user_name           = trim($username);
            $this->user_email          = trim($email);
            $this->user_re_enter_email = trim($re_enter_email);
            $this->user_pass           = trim($password);
            if(!empty($this->user_name) && !empty($this->user_email) && !empty($this->user_re_enter_email) && !empty($this->user_pass)){
                if (filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) { //kiem tra email co hop le khong
                    //Prepared câu SQL
                    $check_email = $this->db->prepare("SELECT * FROM `users` WHERE user_email = ?");//? là tham số ẩn
                    //Thuc thi câu SQL
                    $check_email->execute([$this->user_email]);

                    if($check_email->rowCount() > 0){//Phương thức rowCount() trả về số lượng row
                        return ['errorMessage' => 'This Email Address is already registered. Please Try another.'];
                    }else if(($this->user_email) !== ($this->user_re_enter_email)){
                        return ['errorMessage' => 'Email is not the same!'];
                    }
                    else{                      

                        //Image
                        $user_image = rand(1,12);////rand(min, max): min, max là tùy chọn. Kết quả sẽ cho ra số ngẫu nhiên trong khoảng min max (có thể dùng số âm).

                        $this->hash_pass = password_hash($this->user_pass, PASSWORD_DEFAULT);//password_hash:giúp password bao mat hon
                        $sql = "INSERT INTO `users` (username, user_email, user_password, user_image) VALUES(:username, :user_email, :user_pass, :user_image)";
                        
                        //Prepared câu SQL
                        $sign_up_stmt = $this->db->prepare($sql);
                        //BIND VALUES
                        $sign_up_stmt->bindValue(':username',htmlspecialchars($this->user_name), PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_email',$this->user_email, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_pass',$this->hash_pass, PDO::PARAM_STR);
                        // INSERTING RANDOM IMAGE NAME
                        $sign_up_stmt->bindValue(':user_image',$user_image.'.png', PDO::PARAM_STR);
                        //Thuc thi câu SQL
                        $sign_up_stmt->execute();

                        //----------------gui mail bằng PHP Mailer--------------
                        $nFrom = "CTY.tranhoangthien.net";    		//mail duoc gui tu dau, thuong de ten cong ty ban
                        $mFrom = 'trhoangthien1999@gmail.com';  //dia chi email cua ban 
                        $mPass = '264534296';       			//mat khau email cua ban
                        $nTo   = 'Hoang Thien'; 					//Ten nguoi nhan
                        $mTo   = 'trhoangthien1999@gmail.com';   //dia chi nhan mail
                        $mail  = new PHPMailer();

                        $titles = "Đăng nhập thành công";
                        $body = "<h1>Chúc mừng <i>" . $this->user_email . "</i> đã đăng ký thành công</h1>";  // Noi dung email
                        
                        $mail->IsSMTP();             
                        $mail->CharSet  = "utf-8";
                        $mail->SMTPDebug  = 0;   // enables SMTP debug information (for testing)
                        $mail->SMTPAuth   = true;    // enable SMTP authentication
                        $mail->SMTPSecure = "ssl";   // sets the prefix to the servier
                        $mail->Host       = "smtp.gmail.com";    // sever gui mail.
                        $mail->Port       = 465;  

                        // xong phan cau hinh bat dau phan gui mail
                        $mail->Username   = $mFrom;  // khai bao dia chi email
                        $mail->Password   = $mPass;              // khai bao mat khau
                        $mail->SetFrom($mFrom, $nFrom);
                        $mail->AddReplyTo('trhoangthien1999@gmail.com', 'Freetuts.net'); //khi nguoi dung phan hoi se duoc gui den email nay
                        $mail->Subject    = $titles;// tieu de email 
                        $mail->MsgHTML($body);// noi dung chinh cua mail se nam o day.
                        $mail->AddAddress($mTo, $nTo);

                        // thuc thi lenh gui mail 
                        if(!$mail->Send()){
                            //echo "<script>alert('Mail không được gửi.Error!!!!!')</script>";
                            echo 'Mailer ERROR: ' . $mail->ErrorInfo;
                        }else{
                            echo "<script>alert('Mail của bạn đã được gửi đi hãy kiếm tra hộp thư đến để xem kết quả.')</script>";
                        }
                    //----------------end gui mail bằng PHP Mailer--------------

                        return ['successMessage' => '<h3 style="color:green;">You have signed up successfully.</h3>'];                         
                        
                    }
                }
                else{
                    return ['errorMessage' => 'Invalid email address!'];
                }    
            }
            else{
                return ['errorMessage' => 'Please fill in all the required fields.'];
            } 
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // LOGIN USER(Dang nhap)
    function loginUser($email, $password){  
        try{
            $this->user_email = trim($email);//trim:xoa khoang trang dau cuoi
            $this->user_pass = trim($password);

            //Prepared câu SQL
            $find_email = $this->db->prepare("SELECT * FROM `users` WHERE user_email = ?");
            //Thuc thi câu SQL
            $find_email->execute([$this->user_email]);
            
            if($find_email->rowCount() === 1){
                ////Thiết lập kiểu dữ liệu trả về
                $row = $find_email->fetch(PDO::FETCH_ASSOC);//Kiểu fetch này sẽ tạo ra một mảng kết hợp lập chỉ mục theo tên column 
                                                            //    (nghĩa là các key của mảng chính là tên của column)
                $match_pass = password_verify($this->user_pass, $row['user_password']);//kiem tra xem password có khớp không
                if($match_pass){//nếu đúng
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['email']   = $row['user_email'];                              
                    
                    $sub_query = "
                                    INSERT INTO user_details 
                                    (id) 
                                    VALUES ('".$row['id']."')
                                ";
					$statement = $this->db->prepare($sub_query);
					$statement->execute();
					$_SESSION['login_details_id'] = $this->db->lastInsertId();
                    
                    //echo "<script>window.open('background.php')</script>";
                    header('location: Home.php');
                }
                else{
                    return ['errorMessage' => 'Invalid password'];//password không hợp lệ
                }              
            }
            else{
                return ['errorMessage' => 'Invalid email address!'];
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // FIND USER BY ID==>Tra ve true/false
    function find_user_by_id($id){
        try{
            //Prepared câu SQL
            $find_user = $this->db->prepare("SELECT * FROM `users` WHERE id = ?");
            //Thuc thi cau sql
            $find_user->execute([$id]);
            if($find_user->rowCount() === 1){
                return $find_user->fetch(PDO::FETCH_OBJ);//Trả về một Object của stdClass (link is external) với tên thuộc tính của Object là tên của column.
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    function all_users($id){
        try{
            //Prepared câu SQL
            $get_users = $this->db->prepare("SELECT id, username, user_image FROM `users` WHERE id != ?");
            //Thuc thi cau sql
            $get_users->execute([$id]);
            if($get_users->rowCount() > 0){
                return $get_users->fetchAll(PDO::FETCH_OBJ);//tra ve mot mang
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
?>