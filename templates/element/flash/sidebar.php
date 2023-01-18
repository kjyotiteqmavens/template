 <!-- ======= Sidebar ======= -->
 <?= $this->element('flash/header') ?>

 <aside id="sidebar" class="sidebar">

   <ul class="sidebar-nav" id="sidebar-nav">

     <li class="nav-item">
       <a class="nav-link " href="home">
         <i class="fa-solid fa-shop"></i>
         <span>Dashboard</span>
       </a>
     </li><!-- End Dashboard Nav -->

     <li class="nav-item">
       <a class="nav-link collapsed" href="pageprofile">
         <i class="fa-solid fa-shop"></i>
         <span>Profile</span>
       </a>
     </li><!-- End Profile Page Nav -->

     <li class="nav-item">
       <a class="nav-link collapsed" href="add">
         <i class="fa-solid fa-shop"></i>
         <span>Register</span>
       </a>
     </li><!-- End Register Page Nav -->

     <li class="nav-item">
       <a class="nav-link collapsed" href="login">
         <i class="fa-solid fa-shop"></i>
         <span>Login</span>
       </a>
     </li><!-- End Login Page Nav -->

     <li class="nav-item">
       <a class="nav-link collapsed" href="pageerror">
         <i class="fa-solid fa-shop"></i>
         <span>Error 404</span>
       </a>
     </li><!-- End Error 404 Page Nav -->

   </ul>
 </aside>
 <?= $this->element('flash/footer') ?>