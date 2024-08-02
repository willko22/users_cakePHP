<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue

        $this->Authentication->addUnauthenticatedActions(['login', 'register']);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // debug($this->Authentication->getIdentity()->username);
        $query = $this->Users->find();
        $users = $this->paginate($query);

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
        if ($id === null || !$this->Users->exists(['username' => $id])) {
            $this->Flash->error(__('Incorrect user id. Cannot view user.'));
            return $this->redirect(['action' => 'index']);
        }

        $user = $this->Users->get($id, contain: []);
    
        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function register()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $username = $this->request->getData('username');
            $password = $this->request->getData('password');
            $confirmPassword = $this->request->getData('confirm_password');

            if ($this->Users->find()->where(['username' => $username])->first()) {
                $this->Flash->error(__('User already exists.'));
            } else {
                if ($password === $confirmPassword) {
                    $user->username = $username;
                    $user->password = $password;

                    $user = $this->Users->patchEntity($user, [
                        'username' => $username,
                        'password' => $password
                    ]);

                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('The user has been saved.'));
                        $this->set(compact('user'));
                        $this->redirect(['action' => 'login']);

                    } else {
                        $errors = $user->getErrors();
                        if (!empty($errors)) {
                            foreach ($errors as $field => $validationErrors) {
                                foreach ($validationErrors as $error) {
                                    $this->Flash->error(__($error));
                                }
                            }
                        }
                    }
                    

                } else {
                    $this->Flash->error(__('Password and confirm password do not match.'));
                }
                
            }
        }
    }


    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id === null || !$this->Users->exists(['username' => $id])) {
            $this->Flash->error(__('Incorrect user id. Cannot edit user.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            // get password and confirm password
            $password = $this->request->getData('password');
            $confirmPassword = $this->request->getData('confirm_password');

            // if password and confirm password do not match
            if ($password !== $confirmPassword) {
                $this->Flash->error(__('Password and confirm password do not match.'));
            } else {
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('The user has been saved.'));
                    $this->redirect(['action' => 'index']);
                } else {
                    $errors = $user->getErrors();
                    if (!empty($errors)) {
                        foreach ($errors as $field => $validationErrors) {
                            foreach ($validationErrors as $error) {
                                $this->Flash->error(__($error));
                            }
                        }
                    }
                }
            }
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ($id === null || !$this->Users->exists(['username' => $id])) {
            $this->Flash->error(__('Incorrect user id. Cannot delete user.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        // debug($this->Authentication->getIdentity());
        if ($this->Users->delete($user)) {
            // $this->Flash->success(__('The user has been deleted.'));
            $identity = $this->Authentication->getIdentity();
            if ($identity !== null && isset($identity['username']))
                if ($identity['username'] === $user->username) {
                    return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
                }

        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login() : void
    {
        $this->request->allowMethod(['get', 'post']);
        
        $result = $this->Authentication->getResult();
        // // // // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            // redirect to /articles after login success
            $this->redirect(['action' => 'index']);
        } else if ($this->request->is('post') && $result !== null && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }

    public function logout() : void
    {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            // if there is no user in database =, redirect to add page
            if (!$this->Users->find()->count()) {
                $this->redirect(['controller' => 'Users', 'action' => 'register']);
            } else {
                $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
        }
    }
}
