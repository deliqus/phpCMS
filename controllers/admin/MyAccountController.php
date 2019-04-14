<?php



// Controller for user account

class MyAccountController extends Controller
{
    public function zpracuj($parametry)
    {
        // Do administrace mají přístup jen přihlášení uživatelé
        $this->isAdmin();
        // Hlavička stránky
        $this->hlavicka['title'] = 'Můj účet';
        // Získání dat o přihlášeném uživateli
        $spravceUzivatelu = new UsersManager();
        $id = $_SESSION['user']['user_id'];
        $user = $spravceUzivatelu->getUserById($id);




        $this->data = $user;

        if (!empty($parametry[0])){

            if($parametry[0] == 'logout'){
                $spravceUzivatelu->logout();
                $this->presmeruj('login');
            }
            elseif($parametry[0] == 'edit') {
                $this->data = $user;
                $this->pohled = 'edit-profile';


                // check if form is submitted
                if($_POST){

                    var_dump($_POST);

                    foreach($user as $key1 => $value1){

                        foreach($_POST as $key2 => $value2){
                            if($key1 == $key2){

                                echo "i = " . $key1 . " a j = " . $key2 . "<br>";

                            }
                            else{
                                echo "nerova se <br>";
                            }
                        }

                    }

                    for($i=0;$i<count($user);$i++){

                        for($j=0;$j<count($_POST);$j++){
                            if($i == $j){
                                //echo "i = " . $i . " a j = " . $j;

                            }
                            //echo $user[$i] = $_POST[$j];
                        }
                    }

                    var_dump($user);


                    //die();


                    if(!empty($_FILES['file']['name'])){
                        $image = $spravceUzivatelu->uploadImage($_FILES['file']);
                    }
                    else {
                        $image = $user['image_path'];
                    }
                    try
                        {
                            $spravceUzivatelu = new UsersManager();
                            $user = array(
                            "firstname" =>$_POST['firstname'],
                            "lastname" =>$_POST['lastname'],
                            "image_path" =>$image ,
                            "email" =>$_POST['email']
                        );
                            $spravceUzivatelu->updateUserById($id, $user);



                            $type = "success";
                            $this->pridejZpravu('Your account has been updated', $type);

                            $this->presmeruj('my-account/edit');
                        }
                        catch (ChybaUzivatele $chyba)
                        {
                            $type = "error";
                            $this->pridejZpravu($chyba->getMessage(), $type);
                        }
                }
            }
        }
        else {

            // Nastavení šablony
            $this->pohled = 'my-account';
        }




    }
}