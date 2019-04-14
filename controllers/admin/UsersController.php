<?php



// Controller for user account

class UsersController extends Controller
{
    public function zpracuj($parametry)
    {
        if(empty($parametry[0]))
        {
            $usersManager = new UsersManager();
            $users = $usersManager->getAllUsers();
            $this->data['users'] = $users;
            log_as_json($this->data);
            log_as_json($users);

            var_dump($this->data);
            var_dump($users);

            $this->pohled = 'admin/users';
        }



    }

}

echo "Výpis uživatelů";
