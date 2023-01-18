<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\Mailer\TransportFactory;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;


/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Model = $this->loadModel('Profile');
        $users = $this->paginate($this->Users, [
            'contain' => ['Profile']
        ]);
        // echo '<pre>';
        // print_r($users);
        // die;
        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
           $user = $this->Users->patchEntity($user, $this->request->getData());

            $image=$this->request->getData('image_file');

            $name = $image->getClientFilename();

            $targetPath = WWW_ROOT . 'img' . DS . $name;

            if($name)

            $image->moveTo($targetPath);

            $user->image=$name;

            // echo $user;
            // die;
            
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function edit($id = null)
    // {
    //     $user = $this->Users->get($id, [
    //         'contain' => [],
    //     ]);
    //     if ($this->request->is(['patch', 'post', 'put'])) {
    //         $user = $this->Users->patchEntity($user, $this->request->getData());
    //         if ($this->Users->save($user)) {
    //             $this->Flash->success(__('The user has been saved.'));

    //             return $this->redirect(['action' => 'index']);
    //         }
    //         $this->Flash->error(__('The user could not be saved. Please, try again.'));
    //     }
    //     $this->set(compact('user'));
    // }
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        $file = $user['image'];
        if ($this->request->is(['patch', 'post', 'put'])) {
            // $user = $this->Users->patchEntity($user, $this->request->getData());
            $image = $this->request->getData('image');
            $name = $image->getClientFilename();
            if($name == ''){
                $name = $file;
            }
            $data["image"] = $name;
            // echo $data;die;
            $user = $this->Users->patchEntity($user, $data);
            // $targetPath = WWW_ROOT . 'img' . DS . $name;

            // if ($name)

            //     $image->moveTo($targetPath);

            // $user->image = $name;


            
            if ($this->Users->save($user)) {
                $hasfileerror = $image->getError();
                if($hasfileerror > 0){
                    $data['image_file'] = '';
                }else{
                    $filetype = $image->getClientMediaType();
                    if($filetype == 'image_file/png' || $filetype =='image_file/jpg' || $filetype == 'image_file/jpeg'){
                        $targetPath = WWW_ROOT . 'img' . DS . $name;
                        $image->moveTo($targetPath);
                        $user->image = $name;


                    }
                }

                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            // redirect to /articles after login success
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Users',
                'action' => 'index',
            ]);

            return $this->redirect($redirect);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }
    public function logout()
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }
    public function verification($token)
    {
        $userTable = TableRegistry::get('Users');
        $verify = $userTable->find('all')->where(['token' => $token])->first();
        $verify->verified = '1';
        $verify->status = '1';
        $userTable->save($verify);
        $this->Flash->success(__('Your email has been verified, and please login now.'));
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }

    public function forgotpassword()
    {
        if ($this->request->is('post')) {
            $email = $this->request->getData('email');
            $token = Security::hash(Security::randomBytes(25));

            $userTable = TableRegistry::get('Users');
            if ($email == NULL) {
                $this->Flash->error(__('Please insert your email address'));
            }
            if ($user = $userTable->find('all')->where(['email' => $email])->first()) {
                $user->token = $token;
                if ($userTable->save($user)) {
                    $mailer = new Mailer('default');
                    $mailer->setTransport('gmail');
                    $mailer->setFrom(['jyotikmri2599@gmail.com' => 'jyoti'])
                    ->setTo($email)
                    ->setEmailFormat('html')
                    ->setSubject('Forgot Password Request')
                    ->deliver('Hello<br/>Please click link below to reset your password<br/><br/><a href="http://localhost:8765/users/resetpassword/' . $token . '">Reset Password</a>');
                }
                $this->Flash->success('Reset password link has been sent to your email (' . $email . '), please check your email');
            }
            if ($total = $userTable->find('all')->where(['email' => $email])->count() == 0) {
                $this->Flash->error(__('Email is not registered in system'));
            }
        }
    }
    public function resetpassword($token)
    {
        if ($this->request->is('post')) {
            
            $newPass = $this->request->getData('password');

            $userTable = TableRegistry::get('Users');
            $user = $userTable->find('all')->where(['token' => $token])->first();
            $user->password = $newPass;
            if ($userTable->save($user)) {
                $this->Flash->success('Password successfully reset. Please login using your new password');
                return $this->redirect(['action' => 'login']);
            }
        }
    }
    
    public $paginate =[
        'limit'=> 4
        
    ];
    public function home()
    {
        
    }
   
    public function pageregister(){
        
    }
    public function pagelogin(){

    }
    public function pageprofile(){
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            $image = $this->request->getData('image_file');

            $name = $image->getClientFilename();

            $targetPath = WWW_ROOT . 'img' . DS . $name;

            if ($name)

                $image->moveTo($targetPath);

            $user->image = $name;

            // echo $user;
            // die;

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        

    }
    public function pageerror(){

    }
    

    
}

