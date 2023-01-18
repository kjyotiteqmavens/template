 <nav class="navbar navbar-expand-lg navbar-light bg-dark">

   <div class="collapse navbar-collapse" id="navbarSupportedContent">
     <ul class="navbar-nav mr-auto">
       <li class="nav-item active">
         <?= $this->Html->link(__('Login'), ['action' => 'login'], ['class' => 'nav-link active']) ?>
       </li>
       <li class="nav-item">
         <?= $this->Html->link(__('Signup'), ['action' => 'add'], ['class' => 'nav-link active']) ?>
       </li>
     </ul>
     <ul class="navbar-nav ml-auto">
       <li class="nav-item">
         <?= $this->Html->link(__('Logout'), ['action' => 'logout'], ['class' => 'nav-link active', 'id' => 'test']) ?>
       </li>
     </ul>
   </div>
 </nav>